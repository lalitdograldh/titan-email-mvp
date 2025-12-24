@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    {{-- Page header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-xs font-medium uppercase tracking-[0.16em] text-indigo-500 mb-1">
                Campaigns
            </p>
            <h2 class="text-3xl font-semibold text-slate-900">Create new campaign</h2>
            <p class="mt-2 text-sm text-slate-500 max-w-2xl">
                Craft a simple outbound email. All current leads will be queued when you save and send this campaign.
            </p>
        </div>
        <a href="{{ route('campaigns.index') }}"
           class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-700">
            <span class="mr-1">←</span> Back to campaigns
        </a>
    </div>

    {{-- Errors --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 h-5 w-5 rounded-full bg-red-100 flex items-center justify-center">
                    <span class="text-xs font-bold">!</span>
                </div>
                <div>
                    <p class="font-semibold mb-1">Please fix the following:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Main layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,2fr)_minmax(0,1.2fr)] gap-6">

        {{-- Left: form --}}
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-indigo-500 via-sky-500 to-purple-500"></div>

            <div class="px-6 pt-6 pb-4 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-900">Campaign details</h3>
                <p class="mt-1 text-xs text-slate-500">
                    Give the campaign a clear internal name and subject line that matches your outreach goal.
                </p>
            </div>

            <form method="POST" action="{{ route('campaigns.store') }}" class="px-6 pb-6 pt-4 space-y-6">
                @csrf

                {{-- Name & subject in 2 columns --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Campaign name
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="January warm outreach"
                                required
                                class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-0"
                            >
                        </div>
                        <p class="mt-1 text-xs text-slate-500">
                            Used only inside the app for reporting and analytics.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Email subject
                        </label>
                        {{-- Subject placeholder --}}
                        <input
                            type="text"
                            name="subject"
                            value="{{ old('subject', 'Quick question about your growth at {company}') }}"
                            placeholder="Quick question about your growth at {company}"
                            required
                            class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-0"
                        />
                        <p class="mt-1 text-xs text-slate-500">
                            Keep it under 60 characters for better open rates.
                        </p>
                    </div>
                </div>

                {{-- Body --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Email body
                            </label>
                            <p class="mt-0.5 text-xs text-slate-500">
                                Simple HTML supported. Use paragraphs, line breaks and bold text for clarity.
                            </p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-slate-600">
                            Preview is visible in Loom demo
                        </span>
                    </div>

                    <div class="rounded-xl border border-slate-300 bg-slate-50/60 overflow-hidden focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/70">
                        <textarea
                                name="body_html"
                                rows="10"
                                required
                                class="block w-full bg-transparent px-3.5 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none"
                                placeholder="@php echo '<p>Hi {name},</p>
                            <p>Noticed your work at {company} and thought this might help...</p>
                            <p>Best regards,<br>Team Titan</p>'; @endphp"
                            >{{ old('body_html', "<p>Hi {name},</p>\n<p>Noticed your work at {company} and thought this might help...</p>\n<p>Best regards,<br>Team Titan</p>") }}
                        </textarea>
                    </div>

                    <p class="mt-1 text-xs text-slate-500">
                        Later you can implement real merge tags for <code class="font-mono text-[11px] bg-slate-100 px-1 rounded">{name}</code> and <code class="font-mono text-[11px] bg-slate-100 px-1 rounded">{company}</code> per lead.
                    </p>
                </div>

                {{-- Footer actions --}}
                <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-slate-100 text-[10px] font-semibold text-slate-600">
                            i
                        </span>
                        <span>
                            Campaign is saved as <span class="font-semibold text-slate-700">Draft</span>. You can send it from the campaigns list.
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('campaigns.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                            Cancel
                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center whitespace-nowrap rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:from-indigo-700 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1"
                        >
                            Save campaign
                        </button>

                    </div>
                </div>
            </form>
        </div>

        {{-- Right: helper panel --}}
        <aside class="space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-slate-900 text-slate-50 p-5">
                <h4 class="text-sm font-semibold mb-2 flex items-center gap-2">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-slate-800 text-[11px]">?</span>
                    Writing tips
                </h4>
                <ul class="space-y-2 text-xs text-slate-200">
                    <li>Start with a short, personal first line instead of a generic intro.</li>
                    <li>One clear call‑to‑action per email works better than multiple asks.</li>
                    <li>Keep it under 120–150 words for cold outreach.</li>
                </ul>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <h4 class="text-sm font-semibold text-slate-900 mb-2">What happens after saving?</h4>
                <ol class="mt-1 space-y-2 text-xs text-slate-600 list-decimal list-inside">
                    <li>The campaign is stored as a draft in the database.</li>
                    <li>When you click “Send” from the list, all leads are queued into <code class="font-mono text-[11px] bg-slate-100 px-1 rounded">email_logs</code>.</li>
                    <li>Outlook accounts are rotated to respect daily send limits.</li>
                </ol>
            </div>
        </aside>

    </div>
</div>
@endsection
