@extends('layouts.app')

@section('content')
<div class="space-y-8">

    {{-- Page header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <p class="text-xs font-medium uppercase tracking-[0.18em] text-indigo-500 mb-1">
                Sending infrastructure
            </p>
            <h2 class="text-3xl font-semibold text-slate-900">Outlook accounts</h2>
            <p class="mt-2 text-sm text-slate-500 max-w-2xl">
                Connect multiple Outlook mailboxes and rotate them safely to keep deliverability high and stay under daily limits.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                <span class="mr-1 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                Rotation enabled in code
            </span>
        </div>
    </div>

    {{-- Main layout --}}
    <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1.25fr)_minmax(0,2fr)] gap-6">

        {{-- Left: add account card --}}
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-indigo-500 via-sky-500 to-purple-500"></div>

            {{-- Card header with icon --}}
            <div class="px-6 pt-6 pb-4 border-b border-slate-100 flex items-start gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <span class="text-sm font-semibold">O</span>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Connect a new Outlook sender</h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Add a mailbox used for campaigns. The system will automatically rotate between active senders.
                    </p>
                </div>
            </div>

            <div class="px-6 pb-6 pt-4 space-y-5">
                {{-- alerts --}}
                @if(session('success'))
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-800">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('outlook-accounts.store') }}" class="space-y-5">
                    @csrf

                    {{-- Email + label pill --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between gap-2">
                            <label class="block text-sm font-medium text-slate-700">
                                Outlook email
                            </label>
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-medium text-slate-600">
                                e.g. sender@outlook.com
                            </span>
                        </div>
                        <input
                            type="email"
                            name="email"
                            placeholder="sender@outlook.com"
                            required
                            class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-0"
                        >
                    </div>

                    {{-- Password / token + helper box --}}
                    <div class="grid grid-cols-1 sm:grid-cols-[minmax(0,1.5fr)_minmax(0,1.2fr)] gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-slate-700">
                                App password / token
                            </label>
                            <input
                                type="password"
                                name="app_password"
                                placeholder="16‑character app password or OAuth token"
                                required
                                class="block w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-0"
                            >
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-[11px] text-slate-600">
                            <p class="font-medium mb-1">For this MVP</p>
                            <p>Using any dummy value here is enough. In your Loom you can explain how real OAuth/app passwords would plug in.</p>
                        </div>
                    </div>

                    {{-- Daily limit + slider style hint --}}
                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <label class="block text-sm font-medium text-slate-700">
                                Daily send limit
                            </label>
                            <span class="text-[11px] text-slate-500">Recommended: 200–300</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <input
                                type="number"
                                name="daily_limit"
                                value="300"
                                min="1"
                                class="block w-28 rounded-xl border border-slate-300 bg-slate-50/60 px-3 py-2 text-sm text-slate-900 shadow-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-0"
                            >
                            <div class="flex-1 h-1 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full w-2/3 bg-gradient-to-r from-emerald-400 to-amber-400"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="pt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-t border-slate-100 mt-2">
                        <p class="text-[11px] text-slate-500">
                            This account will start as <span class="font-semibold text-slate-700">Active</span>. You can toggle it off anytime from the table.
                        </p>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center whitespace-nowrap rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:from-indigo-700 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1"
                        >
                            Save account
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- Right: accounts table card --}}
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="px-6 pt-6 pb-4 border-b border-slate-100 flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Configured accounts</h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Active accounts participate in rotation when campaigns are sent.
                    </p>
                </div>
                @if($accounts->count())
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-[11px] font-medium text-slate-600">
                        {{ $accounts->count() }} total
                    </span>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-[11px] font-medium text-slate-500 uppercase tracking-wide">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-[11px] font-medium text-slate-500 uppercase tracking-wide">
                                Daily limit
                            </th>
                            <th class="px-6 py-3 text-left text-[11px] font-medium text-slate-500 uppercase tracking-wide">
                                Sent today
                            </th>
                            <th class="px-6 py-3 text-left text-[11px] font-medium text-slate-500 uppercase tracking-wide">
                                Status
                            </th>
                            <th class="px-6 py-3 text-right text-[11px] font-medium text-slate-500 uppercase tracking-wide">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($accounts as $account)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-6 py-3 align-middle">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-900">{{ $account->email }}</span>
                                        <span class="text-[11px] text-slate-500">
                                            Last used: {{ $account->last_used_at ? $account->last_used_at->diffForHumans() : 'Never' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 align-middle text-slate-700">
                                    {{ $account->daily_limit }}
                                </td>
                                <td class="px-6 py-3 align-middle text-slate-700">
                                    {{ $account->daily_sent }}
                                </td>
                                <td class="px-6 py-3 align-middle">
                                    @if($account->is_active)
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-medium text-emerald-700">
                                            <span class="mr-1 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-medium text-slate-600">
                                            <span class="mr-1 h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 align-middle text-right">
                                    <form method="POST" action="{{ route('outlook-accounts.toggle', $account) }}" class="inline">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="inline-flex items-center rounded-lg border border-slate-300 px-3 py-1.5 text-[11px] font-medium text-slate-700 hover:bg-slate-50"
                                        >
                                            {{ $account->is_active ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
                                    No Outlook accounts yet. Add one on the left to start rotating senders and logging usage.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
