<?php
namespace App\Http\Controllers;
use App\Models\OutlookAccount;
use Illuminate\Http\Request;

class OutlookAccountController extends Controller {
    public function index() {
        $accounts = OutlookAccount::latest()->get();
        return view('outlook_accounts.index', compact('accounts'));
    }

    public function store(Request $request) {
        $request->validate([
            'email' => 'required|email|unique:outlook_accounts,email',
            'app_password' => 'required|string',
        ]);

        OutlookAccount::create($request->all());
        return redirect()->route('outlook-accounts.index')->with('success', 'Outlook account added!');
    }

    public function toggle(OutlookAccount $outlookAccount) {
        $outlookAccount->update(['is_active' => !$outlookAccount->is_active]);
        return back()->with('success', 'Account status updated!');
    }
}
