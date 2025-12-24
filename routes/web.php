<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\OutlookAccountController;
use App\Http\Controllers\AnalyticsController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// ✅ Leads - Explicit only
Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
Route::get('/leads/create', [LeadController::class, 'create'])->name('leads.create');
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
Route::put('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
Route::post('/leads/scrape', [LeadController::class, 'scrape'])->name('leads.scrape');

// ✅ Campaigns - EXPLICIT ONLY (REMOVED resource())
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
Route::get('/campaigns/{campaign}/send', [CampaignController::class, 'sendForm'])->name('campaigns.send.form');
Route::post('/campaigns/{campaign}/send', [CampaignController::class, 'send'])->name('campaigns.send');
Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])
    ->name('campaigns.destroy');

// Outlook & Analytics
Route::get('/outlook-accounts', [OutlookAccountController::class, 'index'])->name('outlook-accounts.index');
Route::post('/outlook-accounts', [OutlookAccountController::class, 'store'])->name('outlook-accounts.store');
Route::post('/outlook-accounts/{outlookAccount}/toggle', [OutlookAccountController::class, 'toggle'])->name('outlook-accounts.toggle');
Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
