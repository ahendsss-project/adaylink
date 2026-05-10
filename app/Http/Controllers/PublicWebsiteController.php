<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Review;
use App\Models\TourPackage;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class PublicWebsiteController extends Controller
{
    /**
     * Resolve the current visitor locale from query param or cookie.
     * Falls back to the website's default_locale.
     */
    private function resolveLocale(Request $request, Website $website, bool $multilanguage): string
    {
        $default = $website->default_locale ?? 'id';

        if (! $multilanguage) {
            return $default;
        }

        // Check query param first (?lang=en or ?lang=id)
        $queryLocale = $request->query('lang');
        if ($queryLocale && in_array($queryLocale, ['en', 'id'])) {
            return $queryLocale;
        }

        // Check cookie
        $cookieLocale = $request->cookie('visitor_locale');
        if ($cookieLocale && in_array($cookieLocale, ['en', 'id'])) {
            return $cookieLocale;
        }

        return $default;
    }

    /**
     * Build the feature flags array from the user's subscription plan.
     */
    private function resolveFeatures(Website $website): array
    {
        $user = $website->user;
        $plan = $user?->plan;

        return [
            'floating_whatsapp' => $plan?->hasFeature('floating_whatsapp') ?? false,
            'social_share' => $plan?->hasFeature('social_share') ?? false,
            'gallery_lightbox' => $plan?->hasFeature('gallery_lightbox') ?? false,
            'reviews' => $plan?->hasFeature('reviews') ?? false,
            'multilanguage' => $plan?->hasFeature('multilanguage') ?? false,
        ];
    }

    /**
     * Render the public landing page for a subdomain.
     *
     * Dynamically loads the template based on the folder_name from
     * the templates table (linked via website_settings.template_id).
     * Falls back to 'public.landing' if no template is configured.
     */
    public function show(Request $request)
    {
        $website = $request->attributes->get('website');
        $settings = $website->websiteSetting;
        $subdomain = $request->route('subdomain');

        // Determine which template to render
        $templateView = 'public.landing'; // default fallback

        if ($settings && $settings->template_id) {
            $template = $settings->template;

            if ($template && $template->folder_name) {
                $customView = 'templates.' . $template->folder_name . '.index';

                // Verify the view exists before using it
                if (View::exists($customView)) {
                    $templateView = $customView;
                }
            }
        }

        // Resolve feature flags from the owner's subscription plan
        $features = $this->resolveFeatures($website);

        // Resolve visitor locale
        $locale = $this->resolveLocale($request, $website, $features['multilanguage']);
        App::setLocale($locale);
        $altLocale = $locale === 'id' ? 'en' : 'id';

        // Eager-load images if gallery is enabled
        if ($features['gallery_lightbox']) {
            $vehicles = $website->vehicles()->with('images')->get();
            $tourPackages = $website->tourPackages()->with('images')->get();
        } else {
            $vehicles = $website->vehicles;
            $tourPackages = $website->tourPackages;
        }

        // Collect gallery images from settings, tour packages, and vehicles
        $galleryImages = collect();
        if ($features['gallery_lightbox']) {
            // Gallery images from website settings (manually uploaded by driver)
            if ($settings && $settings->gallery_images) {
                foreach ($settings->gallery_images as $url) {
                    $galleryImages->push([
                        'url' => $url,
                        'alt' => $website->site_name . ' Gallery',
                    ]);
                }
            }

            // Gallery images from tour packages
            foreach ($tourPackages as $tour) {
                if ($tour->thumbnail_url) {
                    $galleryImages->push([
                        'url' => $tour->thumbnail_url,
                        'alt' => $tour->title,
                    ]);
                }
                foreach ($tour->images as $img) {
                    $galleryImages->push([
                        'url' => $img->url,
                        'alt' => $img->alt_text ?? $tour->title,
                    ]);
                }
            }

            // Gallery images from vehicles
            foreach ($vehicles as $vehicle) {
                if ($vehicle->image_url) {
                    $galleryImages->push([
                        'url' => $vehicle->image_url,
                        'alt' => $vehicle->model_name,
                    ]);
                }
                foreach ($vehicle->images as $img) {
                    $galleryImages->push([
                        'url' => $img->url,
                        'alt' => $img->alt_text ?? $vehicle->model_name,
                    ]);
                }
            }
        }

        // Load approved reviews if the feature is enabled
        $reviews = collect();
        $reviewSchema = null;
        if ($features['reviews']) {
            $reviews = Review::where('website_id', $website->id)
                ->where('is_approved', true)
                ->orderBy('created_at', 'desc')
                ->get();

            $reviewSchema = Review::generateSchema($website);
        }

        // Load published pages for menu rendering
        $pages = Page::where('website_id', $website->id)
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->get();

        return view($templateView, compact(
            'website',
            'settings',
            'vehicles',
            'tourPackages',
            'features',
            'reviews',
            'reviewSchema',
            'galleryImages',
            'pages',
            'subdomain',
            'locale',
            'altLocale',
        ));
    }

    /**
     * Render a specific page for a subdomain website.
     *
     * Uses the template-specific page view if available,
     * falls back to the shared public.page view.
     */
    public function showPage(Request $request)
    {
        $website = $request->attributes->get('website');
        $settings = $website->websiteSetting;
        $subdomain = $request->route('subdomain');
        $slug = $request->route('slug');

        // Find the page by website_id and slug
        $page = Page::where('website_id', $website->id)
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Resolve feature flags from the owner's subscription plan
        $features = $this->resolveFeatures($website);

        // Resolve visitor locale
        $locale = $this->resolveLocale($request, $website, $features['multilanguage']);
        App::setLocale($locale);
        $altLocale = $locale === 'id' ? 'en' : 'id';

        // Load published pages for menu rendering
        $pages = Page::where('website_id', $website->id)
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->get();

        // Determine which template page view to render
        $templateView = 'public.page'; // default fallback

        if ($settings && $settings->template_id) {
            $template = $settings->template;

            if ($template && $template->folder_name) {
                $customView = 'templates.' . $template->folder_name . '.page';

                // Verify the view exists before using it
                if (View::exists($customView)) {
                    $templateView = $customView;
                }
            }
        }

        return view($templateView, compact(
            'website',
            'settings',
            'features',
            'pages',
            'page',
            'subdomain',
            'locale',
            'altLocale',
        ));
    }

    /**
     * Render a tour package detail page for a subdomain website.
     *
     * Uses the template-specific tour view if available,
     * falls back to a shared public.tour view.
     */
    public function showTour(Request $request)
    {
        $website = $request->attributes->get('website');
        $settings = $website->websiteSetting;
        $subdomain = $request->route('subdomain');
        $slug = $request->route('slug');

        // Find the tour package by slug and website_id
        $tour = TourPackage::where('slug', $slug)
            ->where('website_id', $website->id)
            ->firstOrFail();

        // Load tour images
        $tour->load('images');

        // Resolve feature flags from the owner's subscription plan
        $features = $this->resolveFeatures($website);

        // Resolve visitor locale
        $locale = $this->resolveLocale($request, $website, $features['multilanguage']);
        App::setLocale($locale);
        $altLocale = $locale === 'id' ? 'en' : 'id';

        // Load published pages for menu rendering
        $pages = Page::where('website_id', $website->id)
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->get();

        // Get related tours (same website, excluding current tour)
        $relatedTours = TourPackage::where('website_id', $website->id)
            ->where('id', '!=', $tour->id)
            ->latest()
            ->take(3)
            ->get();

        // Determine which template tour view to render
        $templateView = 'public.tour'; // default fallback

        if ($settings && $settings->template_id) {
            $template = $settings->template;

            if ($template && $template->folder_name) {
                $customView = 'templates.' . $template->folder_name . '.tour';

                // Verify the view exists before using it
                if (View::exists($customView)) {
                    $templateView = $customView;
                }
            }
        }

        return view($templateView, compact(
            'website',
            'settings',
            'features',
            'pages',
            'tour',
            'subdomain',
            'relatedTours',
            'locale',
            'altLocale',
        ));
    }
}
