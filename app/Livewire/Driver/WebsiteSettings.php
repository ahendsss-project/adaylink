<?php

namespace App\Livewire\Driver;

use App\Models\Template;
use App\Models\Website;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class WebsiteSettings extends Component
{
    // Website fields (stored on `websites` table)
    #[Validate('nullable|string|max:255')]
    public string $logo_url = '';

    #[Validate('nullable|string|max:50')]
    public string $contact_whatsapp = '';

    #[Validate('nullable|string|max:5|in:id,en')]
    public string $default_locale = 'id';

    // WebsiteSetting fields
    #[Validate('nullable|string|max:255')]
    public string $site_title = '';

    #[Validate('nullable|string|max:7')]
    public string $primary_color = '#40ac98';

    #[Validate('nullable|string|max:7')]
    public string $secondary_color = '#333333';

    #[Validate('nullable|string|max:255')]
    public string $font_family = 'Inter';

    #[Validate('nullable|string|max:255')]
    public string $font_heading = 'Inter';

    #[Validate('nullable|string|max:255')]
    public string $font_body = 'Inter';

    #[Validate('nullable|string|max:255')]
    public string $hero_title = '';

    #[Validate('nullable|string')]
    public string $hero_subtitle = '';

    #[Validate('nullable|string|max:255')]
    public string $hero_image_url = '';

    #[Validate('nullable|string|max:255')]
    public string $seo_meta_title = '';

    #[Validate('nullable|string')]
    public string $seo_meta_description = '';

    #[Validate('required|exists:templates,id')]
    public ?int $template_id = null;

    // Gallery images (stored as JSON on website_settings)
    public array $gallery_images = [];

    // Temporary input for adding gallery image
    public string $new_gallery_image = '';

    // Custom domain
    #[Validate('nullable|string|max:255')]
    public string $custom_domain = '';

    public bool $customDomainEnabled = false;
    public bool $customDomainVerified = false;
    public string $customDomainDnsToken = '';

    public string $allowedTier = 'Basic';

    public function mount(): void
    {
        $user = Auth::guard('web')->user();
        $website = $user->websites->first();

        if (! $website) {
            $this->redirect(route('onboarding.subdomain'), navigate: true);

            return;
        }

        // Determine allowed template tier from user's plan
        $plan = $user->plan;
        $this->allowedTier = $plan ? $plan->allowed_template_tier : 'Basic';

        // Load website fields
        $this->logo_url = $website->logo_url ?? '';
        $this->contact_whatsapp = $website->contact_whatsapp ?? '';
        $this->default_locale = $website->default_locale ?? 'id';

        // Load custom domain fields
        $this->custom_domain = $website->custom_domain ?? '';
        $this->customDomainVerified = ! is_null($website->custom_domain_verified_at);
        $this->customDomainDnsToken = $website->custom_domain_dns_token ?? '';
        $this->customDomainEnabled = $plan ? $plan->hasFeature('custom_domain') : false;

        // Load website settings
        $setting = $website->websiteSetting;

        if ($setting) {
            // Fill all settings except fields that may be NULL in DB but are typed string here
            $settingsData = $setting->toArray();
            unset($settingsData['gallery_images']);
            unset($settingsData['site_title']);
            unset($settingsData['secondary_color']);
            unset($settingsData['font_heading']);
            unset($settingsData['font_body']);
            $this->fill($settingsData);
            $this->site_title = $setting->site_title ?? '';
            $this->gallery_images = $setting->gallery_images ?? [];
            $this->secondary_color = $setting->secondary_color ?? '#333333';
            $this->font_heading = $setting->font_heading ?? 'Inter';
            $this->font_body = $setting->font_body ?? 'Inter';
        }
    }

    public function addGalleryImage(): void
    {
        $url = trim($this->new_gallery_image);

        if (empty($url)) {
            return;
        }

        // Basic URL validation
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            session()->flash('gallery_error', 'URL gambar tidak valid.');

            return;
        }

        $this->gallery_images[] = $url;
        $this->new_gallery_image = '';
    }

    public function removeGalleryImage(int $index): void
    {
        if (isset($this->gallery_images[$index])) {
            unset($this->gallery_images[$index]);
            $this->gallery_images = array_values($this->gallery_images);
        }
    }

    public function save(): void
    {
        Log::info('WebsiteSettings SAVE called', [
            'custom_domain' => $this->custom_domain,
            'customDomainEnabled' => $this->customDomainEnabled,
            'template_id' => $this->template_id,
        ]);

        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('WebsiteSettings VALIDATION FAILED', [
                'errors' => $e->errors(),
            ]);
            throw $e;
        }

        // Verify the selected template is allowed for this user's tier
        $selectedTemplate = Template::find($this->template_id);
        if ($selectedTemplate && $selectedTemplate->tier === 'Premium' && $this->allowedTier !== 'All' && $this->allowedTier !== 'Premium') {
            session()->flash('error', 'Anda tidak bisa memilih template Premium. Silakan upgrade paket Anda.');

            return;
        }

        $user = Auth::guard('web')->user();
        $website = $user->websites->first();

        // Prepare website update data
        $websiteData = [
            'logo_url' => $this->logo_url ?: null,
            'contact_whatsapp' => $this->contact_whatsapp ?: null,
            'default_locale' => $this->default_locale ?: 'id',
        ];

        // Handle custom domain changes
        if ($this->customDomainEnabled) {
            $newDomain = strtolower(trim($this->custom_domain));
            $oldDomain = $website->custom_domain;

            Log::info('Custom domain processing', [
                'newDomain' => $newDomain,
                'oldDomain' => $oldDomain,
                'websiteData_before' => $websiteData,
            ]);

            // Remove protocol and trailing slashes
            $newDomain = preg_replace('#^https?://#', '', $newDomain);
            $newDomain = rtrim($newDomain, '/');

            // Validate domain format
            if ($newDomain && ! preg_match('/^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,}$/', $newDomain)) {
                session()->flash('error', 'Format domain tidak valid. Contoh: tour.bali.com');

                return;
            }

            // Check if domain is already taken by another website
            if ($newDomain && $newDomain !== $oldDomain) {
                $taken = Website::where('custom_domain', $newDomain)
                    ->where('id', '!=', $website->id)
                    ->exists();

                if ($taken) {
                    session()->flash('error', 'Domain ini sudah digunakan oleh website lain.');

                    return;
                }
            }

            // If domain changed, reset verification and generate new token
            if ($newDomain !== $oldDomain) {
                $websiteData['custom_domain'] = $newDomain ?: null;
                $websiteData['custom_domain_verified_at'] = null;
                $websiteData['custom_domain_dns_token'] = $newDomain
                    ? 'adaylink-verify-' . Str::random(12)
                    : null;

                // Update local state
                $this->custom_domain = $newDomain;
                $this->customDomainDnsToken = $websiteData['custom_domain_dns_token'] ?? '';
                $this->customDomainVerified = false;
            }
        }

        Log::info('About to update website', [
            'websiteData' => $websiteData,
        ]);

        // Update website fields
        $website->update($websiteData);

        Log::info('Website updated, checking DB', [
            'custom_domain_in_db' => $website->fresh()->custom_domain,
        ]);

        // Update website settings
        WebsiteSetting::updateOrCreate(
            ['website_id' => $website->id],
            [
                'template_id' => $this->template_id,
                'site_title' => $this->site_title ?: null,
                'primary_color' => $this->primary_color,
                'secondary_color' => $this->secondary_color,
                'font_family' => $this->font_family,
                'font_heading' => $this->font_heading,
                'font_body' => $this->font_body,
                'hero_title' => $this->hero_title,
                'hero_subtitle' => $this->hero_subtitle,
                'hero_image_url' => $this->hero_image_url,
                'seo_meta_title' => $this->seo_meta_title,
                'seo_meta_description' => $this->seo_meta_description,
                'gallery_images' => ! empty($this->gallery_images) ? $this->gallery_images : null,
            ]
        );

        session()->flash('success', 'Pengaturan website berhasil disimpan!');
    }

    public function verifyDomain(): void
    {
        $user = Auth::guard('web')->user();
        $website = $user->websites->first();

        if (! $website->custom_domain) {
            session()->flash('error', 'Tidak ada custom domain yang dikonfigurasi.');

            return;
        }

        if (! $website->custom_domain_dns_token) {
            session()->flash('error', 'Token DNS tidak ditemukan. Simpan pengaturan terlebih dahulu.');

            return;
        }

        $platformDomain = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';

        // Check CNAME record
        $dnsRecords = @dns_get_record($website->custom_domain, DNS_CNAME);
        $cnameValid = false;

        if ($dnsRecords) {
            foreach ($dnsRecords as $record) {
                $target = rtrim($record['target'] ?? '', '.');
                if ($target === $platformDomain || str_ends_with($target, '.' . $platformDomain)) {
                    $cnameValid = true;
                    break;
                }
            }
        }

        // Also check TXT record for verification token
        $txtRecords = @dns_get_record($website->custom_domain, DNS_TXT);
        $txtValid = false;

        if ($txtRecords) {
            foreach ($txtRecords as $record) {
                $txt = $record['txt'] ?? '';
                if ($txt === $website->custom_domain_dns_token) {
                    $txtValid = true;
                    break;
                }
            }
        }

        if ($cnameValid && $txtValid) {
            $website->update([
                'custom_domain_verified_at' => now(),
            ]);
            $this->customDomainVerified = true;

            session()->flash('success', 'Custom domain berhasil diverifikasi! Domain Anda sekarang aktif.');
        } else {
            $errors = [];
            if (! $cnameValid) {
                $errors[] = 'CNAME record tidak ditemukan atau belum mengarah ke <strong>' . $platformDomain . '</strong>';
            }
            if (! $txtValid) {
                $errors[] = 'TXT record dengan token <strong>' . $website->custom_domain_dns_token . '</strong> tidak ditemukan';
            }

            session()->flash('error', 'Verifikasi gagal: ' . implode('. ', $errors));
        }
    }

    public function removeCustomDomain(): void
    {
        $user = Auth::guard('web')->user();
        $website = $user->websites->first();

        $website->update([
            'custom_domain' => null,
            'custom_domain_dns_token' => null,
            'custom_domain_verified_at' => null,
        ]);

        $this->custom_domain = '';
        $this->customDomainDnsToken = '';
        $this->customDomainVerified = false;

        session()->flash('success', 'Custom domain berhasil dihapus.');
    }

    public function render()
    {
        $templates = Template::where('is_active', true)
            ->orderBy('tier', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.driver.website-settings', compact('templates'))
            ->layout('components.layouts.driver')
            ->title('Pengaturan Website - adaylink');
    }
}
