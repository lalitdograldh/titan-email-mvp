@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center bg-slate-950/5">
    <div class="w-full max-w-xl">
        <div
            class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-indigo-50 via-slate-50 to-blue-50 shadow-2xl">
            
            {{-- Top accent bar --}}
            <div class="h-1.5 bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-400"></div>

            <div class="p-8 sm:p-9">
                {{-- Header --}}
                <div class="flex items-start gap-4 mb-6">
                    <div
                        class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600/10 ring-8 ring-indigo-500/10">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-500/90">
                            Confirmation
                        </p>
                        <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">
                            Send this campaign now?
                        </h2>
                        <p class="mt-1.5 text-sm text-slate-500">
                            Review the campaign details before sending emails to all queued leads.
                        </p>
                    </div>
                </div>

                {{-- Campaign overview --}}
                <div class="mb-6 grid gap-4 rounded-2xl border border-slate-100 bg-white/70 p-5 backdrop-blur">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-[0.16em] text-slate-400">
                                Campaign
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-900">
                                {{ $campaign->name }}
                            </p>
                        </div>
                        <span
                            class="inline-flex items-center gap-1 rounded-full border border-indigo-100 bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                            Ready to send
                        </span>
                    </div>

                    <div class="flex items-center justify-between border-t border-dashed border-slate-200 pt-4">
                        <div class="space-y-1">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-400">
                                Queued leads
                            </p>
                            <p class="text-sm text-slate-600">
                                Emails will be sent using Outlook account rotation.
                            </p>
                        </div>
                        <div
                            class="inline-flex min-w-[4.5rem] items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-md shadow-indigo-500/30">
                            {{ $campaign->emailLogs()->where('status', 'queued')->count() }}
                        </div>
                    </div>
                </div>

                {{-- Warning / note --}}
                <div class="mb-6 rounded-2xl border border-amber-100 bg-amber-50/80 px-4 py-3 text-xs text-amber-800">
                    <div class="flex items-start gap-2.5">
                        <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-amber-500" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v4m0 4h.01M4.93 19h14.14a2 2 0 001.74-3l-7.07-12a2 2 0 00-3.42 0l-7.07 12a2 2 0 001.74 3z" />
                        </svg>
                        <p class="leading-relaxed">
                            Once this campaign is sent, its status will change to <span class="font-semibold">“Sent”</span>
                            and it can no longer be edited. Make sure your subject, content, and audience are correct.
                        </p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-4 flex flex-col gap-3 border-t border-slate-200 pt-4 sm:flex-row sm:justify-end">
                    <a href="{{ route('campaigns.index') }}"
                       class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all duration-150 hover:border-slate-300 hover:bg-slate-50 hover:shadow">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </a>

                    <form method="POST" action="{{ route('campaigns.send', $campaign) }}" class="flex-1">
                        @csrf
                        <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 via-blue-600 to-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition-all duration-150 hover:from-indigo-700 hover:via-blue-700 hover:to-sky-600 hover:-translate-y-0.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Send campaign
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
