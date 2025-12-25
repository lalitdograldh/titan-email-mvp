<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Lead;
use App\Models\OutlookAccount;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Microsoft\Kiota\Authentication\Oauth\ClientCredentialContext;
use Microsoft\Graph\GraphServiceClient;
use Microsoft\Graph\Generated\Models\Message;
use Microsoft\Graph\Generated\Models\Recipient;
use Microsoft\Graph\Generated\Models\EmailAddress;
use Microsoft\Graph\Generated\Models\BodyType;
use Microsoft\Graph\Generated\Models\ItemBody;
use Microsoft\Graph\Generated\Users\Item\SendMail\SendMailPostRequestBody;

class CampaignController extends Controller
{
    private static $graphClient = null;

    // 1) Build Graph client using client credentials
    private static function getGraphClient()
    {
        if (self::$graphClient) {
            return self::$graphClient;
        }

        $tenantId = env('MSGRAPH_TENANT_ID');
        $clientId = env('MSGRAPH_CLIENT_ID');
        $clientSecret = env('MSGRAPH_CLIENT_SECRET');

        if (!$tenantId || !$clientId || !$clientSecret) {
            throw new \Exception('Microsoft Graph credentials not configured in .env');
        }

        $tokenRequestContext = new ClientCredentialContext($tenantId, $clientId, $clientSecret);
        self::$graphClient = new GraphServiceClient(
            $tokenRequestContext,
            ['https://graph.microsoft.com/.default']
        );

        return self::$graphClient;
    }

    public function index()
    {
        $campaigns = Campaign::withCount([
            'emailLogs',
            'emailLogs as sent_email_logs_count' => function ($q) {
                $q->where('status', 'sent');
            },
        ])->latest()->paginate(15);

        return view('campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('campaigns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'subject'   => 'required|string|max:255',
            'body_html' => 'required',
        ]);

        // Use first lead only for preview token replacement
        $previewLead = Lead::first();

        if ($previewLead) {
            $replacements = [
                '{name}'    => $previewLead->name ?? '',
                '{company}' => $previewLead->company ?? '',
            ];

            $request->merge([
                'subject'   => str_replace(
                    array_keys($replacements),
                    array_values($replacements),
                    $request->subject
                ),
                'body_html' => str_replace(
                    array_keys($replacements),
                    array_values($replacements),
                    $request->body_html
                ),
            ]);
        }

        $campaign = Campaign::create(array_merge(
            $request->all(),
            ['status' => 'draft']
        ));

        // Get Outlook account (from DB)
        $outlookAccount = OutlookAccount::where('is_active', true)
            ->orderBy('daily_sent')
            ->first();

        if (!$outlookAccount) {
            return back()->with('error', 'No active Outlook accounts available.');
        }

        // Create logs for all leads
        $leads = Lead::all();

        foreach ($leads as $lead) {
            EmailLog::create([
                'campaign_id'        => $campaign->id,
                'lead_id'            => $lead->id,
                'outlook_account_id' => $outlookAccount->id,
                'to_email'           => $lead->email,
                'status'             => 'queued',
            ]);
        }

        return redirect()
            ->route('campaigns.index')
            ->with('success', 'Campaign created and queued for ' . $leads->count() . ' leads!');
    }

    public function edit(Campaign $campaign)
    {
        return view('campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'subject'   => 'required|string|max:255',
            'body_html' => 'required',
        ]);

        $campaign->update($request->all());

        return redirect()
            ->route('campaigns.index')
            ->with('success', 'Campaign updated!');
    }

    public function send(Campaign $campaign)
    {
        if (!$campaign->subject || !$campaign->body_html) {
            return back()->with('error', 'Campaign subject/body missing.');
        }

        $logs = $campaign->emailLogs()
            ->where('status', 'queued')
            ->with('lead')
            ->get();

        if ($logs->isEmpty()) {
            return back()->with('warning', 'No queued emails.');
        }

        // IMPORTANT: this email must be an existing mailbox in your tenant
        $outlookAccount = OutlookAccount::first();
        if (!$outlookAccount) {
            return back()->with('error', 'No Outlook account configured.');
        }

        try {
            $graphClient = self::getGraphClient();
        } catch (\Exception $e) {
            Log::error('Graph Client Error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to initialize Microsoft Graph client: ' . $e->getMessage());
        }

        $sent   = 0;
        $failed = 0;

        foreach ($logs as $log) {
            try {
                $this->sendEmailWithGraph($log, $campaign, $outlookAccount->email, $graphClient);
                $sent++;
                // optional: small delay to avoid throttling
                // sleep(1);
            } catch (\Exception $e) {
                $log->update([
                    'status'        => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
                $failed++;
                Log::error('Email Send Failed', [
                    'log_id' => $log->id,
                    'error'  => $e->getMessage()
                ]);
            }
        }

        $campaign->update([
            'status' => $sent > 0 ? 'sent' : 'failed',
        ]);

        return redirect()
            ->route('campaigns.index')
            ->with('success', "Emails sent: {$sent}, Failed: {$failed}");
    }

    // 2) Build and send the email using Graph
    private function sendEmailWithGraph(
        EmailLog $log,
        Campaign $campaign,
        string $userEmail,
        GraphServiceClient $graphClient
    ) {
        // Replace tokens per-lead
        $replacements = [
            '{name}'    => $log->lead?->name ?? 'Customer',
            '{company}' => $log->lead?->company ?? '',
        ];

        $subject = str_replace(array_keys($replacements), array_values($replacements), $campaign->subject);
        $body    = str_replace(array_keys($replacements), array_values($replacements), $campaign->body_html);

        $log->update([
            'subject'   => $subject,
            'body_html' => $body,
            'status'    => 'sending',
        ]);

        $message = new Message();
        $message->setSubject($subject);

        $bodyContent = new ItemBody();
        $bodyContent->setContentType(new BodyType(BodyType::HTML));
        $bodyContent->setContent($body);
        $message->setBody($bodyContent);

        $toRecipient  = new Recipient();
        $emailAddress = new EmailAddress();
        $emailAddress->setAddress($log->to_email);
        $toRecipient->setEmailAddress($emailAddress);
        $message->setToRecipients([$toRecipient]);

        $sendMailRequestBody = new SendMailPostRequestBody();
        $sendMailRequestBody->setMessage($message);
        $sendMailRequestBody->setSaveToSentItems(true);

        // CRITICAL: userEmail must be a valid user id or UPN that can send mail
        $graphClient
            ->users()
            ->byUserId($userEmail)
            ->sendMail()
            ->post($sendMailRequestBody);

        $log->update([
            'status'        => 'sent',
            'error_message' => null,
        ]);
    }

    public function destroy(Campaign $campaign)
    {
        EmailLog::where('campaign_id', $campaign->id)->delete();
        $campaign->delete();

        return redirect()
            ->route('campaigns.index')
            ->with('success', 'Campaign deleted successfully!');
    }

    public function sendForm(Campaign $campaign)
    {
        return view('campaigns.send-form', compact('campaign'));
    }
}
