<?php
namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::latest()->paginate(20);
        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        return view('leads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', Rule::unique('leads', 'email')],
            'name' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:13',
        ]);

        Lead::create($request->all());
        return redirect()->route('leads.index')->with('success', 'Lead created!');
    }

    public function edit(Lead $lead)
    {
        return view('leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $request->validate([
            'email' => ['required', 'email', Rule::unique('leads', 'email')->ignore($lead->id)],
            'name' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:13',
        ]);

       $lead->update($request->all());
        return redirect()->route('leads.index')->with('success', 'Lead updated!');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')->with('success', 'Lead deleted!');
    }

    // Fixed scrape method above
    public function scrape(Request $request)
    {
        $request->validate(['url' => 'required|url|max:2048']);        
        $response = Http::timeout(30)->get($request->url);        
        if (!$response->successful()) {
            return back()->with('error', 'Unable to fetch website.');
        }        
        $html = $response->body();
        preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/i', $html, $matches);        
        $created = 0;
        $emails = [];        
        foreach (array_unique($matches[0]) as $email) {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || 
                Lead::where('email', $email)->exists()) {
                continue;
            }            
            Lead::create([
                'email' => $email,
                'website' => parse_url($request->url, PHP_URL_HOST),
                'name' => null, // Add nullable fields
                'company' => null,
                'phone' => null,
            ]);
            
            $created++;
            $emails[] = $email;
        }
        
        return back()->with('success', 
            "Scraped {$created} leads: " . implode(', ', array_slice($emails, 0, 3))
        );
    }

}
