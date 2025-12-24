@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <h2 class="text-2xl font-semibold text-slate-900">Edit campaign</h2>
    <p class="mt-1 text-sm text-slate-500">
        Update name, subject and body for this campaign.
    </p>

    <div class="card p-6 mt-4">
        <form method="POST" action="{{ route('campaigns.update', $campaign) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $campaign->name) }}" required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Subject</label>
                <input type="text" name="subject" value="{{ old('subject', $campaign->subject) }}" required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Body (HTML)</label>
                <textarea name="body_html" rows="6" required
                          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('body_html', $campaign->body_html) }}</textarea>
            </div>

            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('campaigns.index') }}"
                   class="text-xs text-slate-500 hover:text-slate-700">
                    Cancel
                </a>
                <a href="{{ route('campaigns.send.form', $campaign) }}"
                   class="text-xs text-slate-500 hover:text-slate-700">
                    Send
                </a>
                <button type="submit" class="btn-primary">
                    Update campaign
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
