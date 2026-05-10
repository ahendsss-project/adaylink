<?php

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\TemplateDemoController;
use App\Models\Admin;
use App\Models\AuditLog;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\PublicWebsiteController;
use App\Http\Controllers\ReviewController;
use App\Livewire\Admin\PendingApprovals;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\StockImageForm;
use App\Livewire\Admin\StockImages;
use App\Livewire\Admin\SubscriptionPlanForm;
use App\Livewire\Admin\SubscriptionPlans;
use App\Livewire\Admin\TemplateForm;
use App\Livewire\Admin\Templates;
use App\Livewire\Driver\Dashboard as DriverDashboard;
use App\Livewire\Driver\PageForm as DriverPageForm;
use App\Livewire\Driver\Pages as DriverPages;
use App\Livewire\Driver\Reviews as DriverReviews;
use App\Livewire\Driver\TourPackageForm;
use App\Livewire\Driver\TourPackages;
use App\Livewire\Driver\VehicleForm;
use App\Livewire\Driver\Vehicles;
use App\Livewire\Driver\WebsiteSettings;
use App\Livewire\Onboarding\Paywall;
use App\Livewire\Onboarding\SelectPlan;
use App\Livewire\Onboarding\SubdomainClaim;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Custom Domain Routing (verified custom domains)
|--------------------------------------------------------------------------
| These routes are checked first. The middleware resolves the website from
| the request host. If the host is not a custom domain, it passes through.
*/

Route::middleware('custom_domain.resolve')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store'])->name('custom-domain.reviews.store');
    Route::get('/page/{slug}', [PublicWebsiteController::class, 'showPage'])->name('custom-domain.page');
    Route::get('/tour/{slug}', [PublicWebsiteController::class, 'showTour'])->name('custom-domain.tour');
});

/*
|--------------------------------------------------------------------------
| Public Subdomain Routing (Tourist-facing)
|--------------------------------------------------------------------------
| Matches: {subdomain}.adaylink.com OR /s/{subdomain} (for local dev)
*/

// Path-based subdomain access (for local development)
Route::get('/s/{subdomain}', [PublicWebsiteController::class, 'show'])
    ->name('public.website')
    ->middleware('subdomain.validate');
Route::post('/s/{subdomain}/reviews', [ReviewController::class, 'store'])
    ->name('public.reviews.store')
    ->middleware('subdomain.validate');
Route::get('/s/{subdomain}/page/{slug}', [PublicWebsiteController::class, 'showPage'])
    ->name('public.page')
    ->middleware('subdomain.validate');
Route::get('/s/{subdomain}/tour/{slug}', [PublicWebsiteController::class, 'showTour'])
    ->name('public.tour.path')
    ->middleware('subdomain.validate');

// Domain-based subdomain access (for production)
$domain = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
Route::domain('{subdomain}.' . $domain)
    ->middleware('subdomain.validate')
    ->group(function () {
        Route::get('/', [PublicWebsiteController::class, 'show']);
        Route::post('/reviews', [ReviewController::class, 'store'])->name('public.reviews.store.domain');
        Route::get('/page/{slug}', [PublicWebsiteController::class, 'showPage']);
        Route::get('/tour/{slug}', [PublicWebsiteController::class, 'showTour'])->name('public.tour.domain');
    });

/*
|--------------------------------------------------------------------------
| Main Platform Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function (Request $request) {
    // If this is a custom domain request, delegate to PublicWebsiteController
    if ($request->attributes->get('is_custom_domain')) {
        return app(PublicWebsiteController::class)->show($request);
    }

    return view('welcome');
})->name('home')->middleware('custom_domain.resolve');

// Pricing page (public, regular controller - not Livewire)
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

// Template Demo/Preview routes (public, no auth required)
Route::get('/demo/{template}', [TemplateDemoController::class, 'show'])->name('demo.template');
Route::get('/demo/{template}/tour/{slug}', [TemplateDemoController::class, 'showTour'])->name('demo.tour');
Route::get('/demo/{template}/page/{slug}', [TemplateDemoController::class, 'showPage'])->name('demo.page');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login.post');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        Route::get('/pending-approvals', PendingApprovals::class)->name('pending-approvals');

        // User Management (Phase 4)
        Route::get('/users', UserManagement::class)->name('users.index');

        // Subscription Plans CRUD
        Route::get('/plans', SubscriptionPlans::class)->name('plans.index');
        Route::get('/plans/create', SubscriptionPlanForm::class)->name('plans.create');
        Route::get('/plans/{planId}/edit', SubscriptionPlanForm::class)->name('plans.edit');

        // Stock Images CRUD
        Route::get('/stock-images', StockImages::class)->name('stock-images.index');
        Route::get('/stock-images/create', StockImageForm::class)->name('stock-images.create');
        Route::get('/stock-images/{imageId}/edit', StockImageForm::class)->name('stock-images.edit');

        // Templates CRUD (Phase 3)
        Route::get('/templates', Templates::class)->name('templates.index');
        Route::get('/templates/create', TemplateForm::class)->name('templates.create');
        Route::get('/templates/{templateId}/edit', TemplateForm::class)->name('templates.edit');
    });
});

/*
|--------------------------------------------------------------------------
| Driver / Tenant Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('web')->group(function () {
    Route::middleware('guest:web')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
    });
});

Route::middleware('guest:web')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

});

Route::middleware(['auth:web', 'not.blocked'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Exit impersonation (must be inside auth:web but outside subscription.active)
    Route::get('/exit-impersonate', function () {
        $adminId = session('impersonating_admin');
        if (! $adminId) {
            return redirect()->route('home');
        }

        $admin = Admin::find($adminId);

        // Log the exit impersonation
        AuditLog::create([
            'admin_id' => $adminId,
            'target_user_id' => auth('web')->id(),
            'action' => 'Exit Impersonate',
            'details' => ['user_name' => auth('web')->user()->full_name],
        ]);

        // Logout from web guard
        Auth::guard('web')->logout();
        session()->forget('impersonating_admin');

        // Login back as admin
        if ($admin) {
            Auth::guard('admin')->login($admin);
        }

        return redirect()->route('admin.users.index');
    })->name('exit-impersonate');

    // Onboarding flow
    Route::get('/onboarding/select-plan', SelectPlan::class)->name('onboarding.select-plan');
    Route::get('/onboarding/subdomain', SubdomainClaim::class)->name('onboarding.subdomain');
    Route::get('/onboarding/paywall', Paywall::class)->name('onboarding.paywall');

    // Dashboard redirect (handles all subscription statuses)
    Route::get('/dashboard', function () {
        $user = auth('web')->user();

        if ($user->subscription_status === 'Pending') {
            // No plan selected yet → go to plan selection
            if (! $user->plan_id) {
                return redirect()->route('onboarding.select-plan');
            }

            // Plan selected but no website → claim subdomain
            if (! $user->websites()->exists()) {
                return redirect()->route('onboarding.subdomain');
            }

            // Plan + website ready → paywall
            return redirect()->route('onboarding.paywall');
        }

        if ($user->subscription_status === 'Expired') {
            return view('expired');
        }

        return redirect()->route('driver.dashboard');
    })->name('dashboard');

    // Driver Dashboard (requires Active subscription)
    Route::prefix('dashboard')->name('driver.')->middleware('subscription.active')->group(function () {
        Route::get('/home', DriverDashboard::class)->name('dashboard');
        Route::get('/settings', WebsiteSettings::class)->name('settings');

        // Vehicles CRUD
        Route::get('/vehicles', Vehicles::class)->name('vehicles.index');
        Route::get('/vehicles/create', VehicleForm::class)->name('vehicles.create');
        Route::get('/vehicles/{vehicleId}/edit', VehicleForm::class)->name('vehicles.edit');

        // Tour Packages CRUD
        Route::get('/tours', TourPackages::class)->name('tours.index');
        Route::get('/tours/create', TourPackageForm::class)->name('tours.create');
        Route::get('/tours/{tourId}/edit', TourPackageForm::class)->name('tours.edit');

        // Reviews Management (Phase 5)
        Route::get('/reviews', DriverReviews::class)->name('reviews.index');

        // Pages CRUD (Phase 6)
        Route::get('/pages', DriverPages::class)->name('pages.index');
        Route::get('/pages/create', DriverPageForm::class)->name('pages.create');
        Route::get('/pages/{pageId}/edit', DriverPageForm::class)->name('pages.edit');
    });
});
