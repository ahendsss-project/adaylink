<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Features\SupportFileUploads\FilePreviewController;
use Livewire\Features\SupportFileUploads\FileUploadController;
use Livewire\Livewire;
use Livewire\Mechanisms\FrontendAssets\FrontendAssets;
use Livewire\Mechanisms\HandleRequests\EndpointResolver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'Tour' => \App\Models\TourPackage::class,
            'Vehicle' => \App\Models\Vehicle::class,
        ]);

        // Configure Livewire routes for subdirectory deployment.
        // When the app is served behind a reverse proxy (e.g., WordPress at root,
        // Laravel at /app/panel), set LIVEWIRE_ROUTE_PREFIX in .env (e.g., "app/panel").
        // This ensures Livewire's JS, update, upload, and preview endpoints are
        // accessible under the same prefix as the rest of the Laravel routes.
        $prefix = config('app.livewire_route_prefix', '');
        if ($prefix) {
            $this->app->booted(function () use ($prefix) {
                $prefix = '/' . trim($prefix, '/');

                // Override the Livewire update endpoint route
                Livewire::setUpdateRoute(function ($handle, $path) use ($prefix) {
                    return Route::post($prefix . $path, $handle);
                });

                // Override the Livewire JavaScript asset route
                Livewire::setScriptRoute(function ($handle) use ($prefix) {
                    $scriptPath = config('app.debug')
                        ? EndpointResolver::scriptPath(minified: false)
                        : EndpointResolver::scriptPath(minified: true);

                    return Route::get($prefix . $scriptPath, $handle);
                });

                // Register prefixed file upload route
                Route::post($prefix . EndpointResolver::uploadPath(), [FileUploadController::class, 'handle'])
                    ->name('livewire.upload-file');

                // Register prefixed file preview route
                Route::get($prefix . EndpointResolver::previewPath(), [FilePreviewController::class, 'handle'])
                    ->name('livewire.preview-file');

                // Register prefixed source map routes
                Route::get($prefix . EndpointResolver::mapPath(csp: false), [FrontendAssets::class, 'maps']);
                Route::get($prefix . EndpointResolver::mapPath(csp: true), [FrontendAssets::class, 'cspMaps']);
            });
        }
    }
}
