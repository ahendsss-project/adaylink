<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Import Controllers
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\TemplateDemoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\PublicWebsiteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TemplatePreviewController; // Contoh jika ada

// Import Livewire Components
use App\Livewire\Admin\{PendingApprovals, UserManagement, StockImageForm, StockImages, SubscriptionPlanForm, SubscriptionPlans, TemplateForm, Templates};
use App\Livewire\Driver\{Dashboard as DriverDashboard, PageForm as DriverPageForm, Pages as DriverPages, Reviews as DriverReviews, TourPackageForm, TourPackages, VehicleForm, VehicleForms, Vehicles, WebsiteSettings};
use App\Livewire\Onboarding\{Paywall, SelectPlan, SubdomainClaim};

use App\Models\Admin;
use App\Models\AuditLog;

/*
|--------------------------------------------------------------------------
| 1. Custom Domain & Subdomain (Tourist Facing)
|--------------------------------------------------------------------------
*/
Route::middleware('custom_domain.resolve')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store'])->name('custom-domain.reviews.store');
    Route::get('/page/{slug}', [PublicWebsiteController::class, 'showPage'])->name('custom-domain.page');
    Route::get('/tour/{slug}', [PublicWebsiteController::class, 'showTour'])->name('custom-domain.tour');
});

// Domain-based subdomain access (Production)
$domain = parse_url(config('app.url'), PHP_URL_HOST) ?: 'adaylink.com';
Route::domain('{subdomain}.' . $domain)->middleware('subdomain.validate')->group(function () {
    Route::get('/', [PublicWebsiteController::class, 'show']);
    Route::post('/reviews', [ReviewController::class, 'store'])->name('public.reviews.store.domain');
    Route::get('/page/{slug}', [PublicWebsiteController::class, 'showPage']);
    Route::get('/tour/{slug}', [PublicWebsiteController::class, 'showTour'])->name('public.tour.domain');
});

/*
|--------------------------------------------------------------------------
| 2. Public Platform Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function (Request $request) {
    if ($request->attributes->get('is_custom_domain')) {
        return app(PublicWebsiteController::class)->show($request);
    }
    // Jika di server pakai WordPress di depan, halaman ini jarang terakses
    return view('welcome');
})->name('home')->middleware('custom_domain.resolve');

Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::get('/demo/{template}', [TemplateDemoController::class, 'show'])->name('demo.template');

/*
|--------------------------------------------------------------------------
| 3. Admin Panel (Prefix: /admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login.post');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
        
        // Resource Management
        Route::get('/users', UserManagement::class)->name('users.index');
        Route::get('/plans', SubscriptionPlans::class)->name('plans.index');
        Route::get('/templates', Templates::class)->name('templates.index');
        Route::get('/stock-images', StockImages::class)->name('stock-images.index');
        // ... (Tambahkan route form lainnya di sini)
    });
});

/*
|--------------------------------------------------------------------------
| 4. Tenant/Driver System (Prefix: /app)
|--------------------------------------------------------------------------
| Gunakan prefix 'app' agar tidak bentrok dengan root WordPress
*/
Route::prefix('app')->group(function () {
    
    // Auth Routes
    Route::middleware('guest:web')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.post');
        Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
    });

    // Protected Routes
    Route::middleware(['auth:web', 'not.blocked'])->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        // Onboarding
        Route::get('/onboarding/select-plan', SelectPlan::class)->name('onboarding.select-plan');
        Route::get('/onboarding/subdomain', SubdomainClaim::class)->name('onboarding.subdomain');
        Route::get('/onboarding/paywall', Paywall::class)->name('onboarding.paywall');

        // Central Dashboard Redirector
        Route::get('/dashboard', function () {
            $user = auth('web')->user();
            if ($user->subscription_status === 'Pending') {
                return $user->plan_id ? redirect()->route('onboarding.subdomain') : redirect()->route('onboarding.select-plan');
            }
            return $user->subscription_status === 'Expired' ? view('expired') : redirect()->route('driver.dashboard');
        })->name('dashboard');

        // Driver Panel (Active Subscription Only)
        Route::prefix('panel')->name('driver.')->middleware('subscription.active')->group(function () {
            Route::get('/', DriverDashboard::class)->name('dashboard');
            Route::get('/settings', WebsiteSettings::class)->name('settings');
            Route::get('/vehicles', Vehicles::class)->name('vehicles.index');
            Route::get('/tours', TourPackages::class)->name('tours.index');
            Route::get('/reviews', DriverReviews::class)->name('reviews.index');
            Route::get('/pages', DriverPages::class)->name('pages.index');
        });
    });
});