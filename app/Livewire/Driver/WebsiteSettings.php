<?php

namespace App\Livewire\Driver;

use App\Models\Template;
use App\Models\Website;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class WebsiteSettings extends Component
{
    use WithFileUploads;
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

    public $logo_file = null;
    public $hero_image_file = null;
    public $new_gallery_image_file = null;

    // Custom domain
    #[Validate('nullable|string|max:255')]
    public string $custom_domain = '';

    public bool $customDomainEnabled = false;
    public bool $customDomainVerified = false;
    public string $customDomainDnsToken = '';

    public string $allowedTier = 'Basic';

    // Subdomain rename
    public string $subdomain = '';
    public string $currentSubdomain = '';

    // Translation support
    public bool $multilanguageEnabled = false;
    public string $secondaryLocale = 'en';

    /** @var array<string, string> Translation fields for secondary locale */
    public array $tr = [];

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

        // Determine multilanguage availability
        $this->multilanguageEnabled = $plan ? $plan->hasFeature('multilanguage') : false;
        $defaultLocale = $website->default_locale ?? 'id';
        $this->secondaryLocale = $defaultLocale === 'id' ? 'en' : 'id';

        // Load website fields
        $this->logo_url = $website->logo_url ?? '';
        $this->contact_whatsapp = $website->contact_whatsapp ?? '';
        $this->default_locale = $website->default_locale ?? 'id';
        $this->subdomain = $website->subdomain ?? '';
        $this->currentSubdomain = $website->subdomain ?? '';

        // Load custom domain fields
        $this->custom_domain = $website->custom_domain ?? '';
        $this->customDomainVerified = ! is_null($website->custom_domain_verified_at);
        $this->customDomainDnsToken = $website->custom_domain_dns_token ?? '';
        $this->customDomainEnabled = $plan ? $plan->hasFeature('custom_domain') : false;

        // Load website settings — auto-create with a default template if none exists
        $setting = $website->websiteSetting;

        if (! $setting) {
            // Pick the first active template that matches the user's allowed tier
            $defaultTemplate = Template::where('is_active', true)
                ->where('tier', '!=', 'Premium')
                ->orderBy('name')
                ->first();

            $setting = WebsiteSetting::create([
                'website_id' => $website->id,
                'template_id' => $defaultTemplate?->id,
                'primary_color' => '#40ac98',
                'secondary_color' => '#333333',
                'font_family' => 'Inter',
                'font_heading' => 'Inter',
                'font_body' => 'Inter',
            ]);
        }

        // Fill settings, filtering out null values to avoid TypeError on typed string properties
        $settingsData = collect($setting->toArray())
            ->filter(fn($value) => $value !== null)
            ->toArray();
        unset($settingsData['gallery_images']); // handled separately as array
        $this->fill($settingsData);

        // Ensure all string-typed properties have defaults (DB may have null)
        $this->site_title = $setting->site_title ?? '';
        $this->primary_color = $setting->primary_color ?? '#40ac98';
        $this->secondary_color = $setting->secondary_color ?? '#333333';
        $this->font_family = $setting->font_family ?? 'Inter';
        $this->font_heading = $setting->font_heading ?? 'Inter';
        $this->font_body = $setting->font_body ?? 'Inter';
        $this->hero_title = $setting->hero_title ?? '';
        $this->hero_subtitle = $setting->hero_subtitle ?? '';
        $this->hero_image_url = $setting->hero_image_url ?? '';
        $this->seo_meta_title = $setting->seo_meta_title ?? '';
        $this->seo_meta_description = $setting->seo_meta_description ?? '';
        $this->gallery_images = $setting->gallery_images ?? [];

        // Load translations for secondary locale
        $this->tr = [
            'site_title' => $setting->getTranslation('site_title', $this->secondaryLocale) ?? '',
            'hero_title' => $setting->getTranslation('hero_title', $this->secondaryLocale) ?? '',
            'hero_subtitle' => $setting->getTranslation('hero_subtitle', $this->secondaryLocale) ?? '',
            'seo_meta_title' => $setting->getTranslation('seo_meta_title', $this->secondaryLocale) ?? '',
            'seo_meta_description' => $setting->getTranslation('seo_meta_description', $this->secondaryLocale) ?? '',
        ];
    }

    private array $imageFileRules = [
        'file', 'mimes:jpg,jpeg,png,webp', 'max:1024', 'dimensions:min_width=50,min_height=50',
    ];

    private array $imageFileMessages = [
        'mimes' => 'Format gambar harus WEBP, PNG, atau JPG.',
        'max' => 'Ukuran gambar maksimal 1 MB.',
        'dimensions' => 'Dimensi gambar minimal 50×50 piksel.',
    ];

    public function updatedLogoFile(): void
    {
        $this->validateOnly('logo_file', ['logo_file' => ['nullable', ...$this->imageFileRules]],
            array_combine(array_map(fn ($k) => "logo_file.{$k}", array_keys($this->imageFileMessages)), $this->imageFileMessages));
    }

    public function updatedHeroImageFile(): void
    {
        $this->validateOnly('hero_image_file', ['hero_image_file' => ['nullable', ...$this->imageFileRules]],
            array_combine(array_map(fn ($k) => "hero_image_file.{$k}", array_keys($this->imageFileMessages)), $this->imageFileMessages));
    }

    public function updatedNewGalleryImageFile(): void
    {
        $this->validateOnly('new_gallery_image_file', ['new_gallery_image_file' => ['nullable', ...$this->imageFileRules]],
            array_combine(array_map(fn ($k) => "new_gallery_image_file.{$k}", array_keys($this->imageFileMessages)), $this->imageFileMessages));
    }

    public function addGalleryImage(): void
    {
        // Handle file upload for gallery
        if ($this->new_gallery_image_file) {
            $this->validate(['new_gallery_image_file' => $this->imageFileRules],
                array_combine(array_map(fn ($k) => "new_gallery_image_file.{$k}", array_keys($this->imageFileMessages)), $this->imageFileMessages));
            $url = upload_url(upload_store('gallery', $this->new_gallery_image_file));
            $this->gallery_images[] = $url;
            $this->new_gallery_image_file = null;

            return;
        }

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
        $this->validate();

        // Handle logo file upload
        if ($this->logo_file) {
            $this->validate(['logo_file' => $this->imageFileRules],
                array_combine(array_map(fn ($k) => "logo_file.{$k}", array_keys($this->imageFileMessages)), $this->imageFileMessages));
            $this->logo_url = upload_url(upload_store('logos', $this->logo_file));
            $this->logo_file = null;
        }

        // Handle hero image file upload
        if ($this->hero_image_file) {
            $this->validate(['hero_image_file' => $this->imageFileRules],
                array_combine(array_map(fn ($k) => "hero_image_file.{$k}", array_keys($this->imageFileMessages)), $this->imageFileMessages));
            $this->hero_image_url = upload_url(upload_store('hero-images', $this->hero_image_file));
            $this->hero_image_file = null;
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

        // Update website fields
        $website->update($websiteData);

        // Build settings data
        $settingsData = [
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
        ];

        // Handle translations for secondary locale
        if ($this->multilanguageEnabled) {
            $existingSetting = $website->websiteSetting;
            $existingTranslations = $existingSetting ? ($existingSetting->translations ?? []) : [];

            $hasTranslation = !empty(trim($this->tr['site_title'] ?? ''))
                || !empty(trim($this->tr['hero_title'] ?? ''))
                || !empty(trim($this->tr['hero_subtitle'] ?? ''))
                || !empty(trim($this->tr['seo_meta_title'] ?? ''))
                || !empty(trim($this->tr['seo_meta_description'] ?? ''));

            if ($hasTranslation) {
                $existingTranslations[$this->secondaryLocale] = array_filter([
                    'site_title' => $this->tr['site_title'] ?: null,
                    'hero_title' => $this->tr['hero_title'] ?: null,
                    'hero_subtitle' => $this->tr['hero_subtitle'] ?: null,
                    'seo_meta_title' => $this->tr['seo_meta_title'] ?: null,
                    'seo_meta_description' => $this->tr['seo_meta_description'] ?: null,
                ], fn($v) => $v !== null);
            } else {
                unset($existingTranslations[$this->secondaryLocale]);
            }

            $settingsData['translations'] = !empty($existingTranslations) ? $existingTranslations : null;
        }

        // Update website settings
        WebsiteSetting::updateOrCreate(
            ['website_id' => $website->id],
            $settingsData
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

    public function updatedSubdomain(string $value): void
    {
        // Auto-format: lowercase, replace spaces with hyphens, remove special chars
        $this->subdomain = strtolower(preg_replace('/[^a-z0-9\-]/', '', str_replace(' ', '-', $value)));
    }

    public function updateSubdomain(): void
    {
        $this->validate([
            'subdomain' => 'required|string|min:3|max:50|regex:/^[a-z0-9][a-z0-9\-]*[a-z0-9]$/',
        ]);

        $user = Auth::guard('web')->user();
        $website = $user->websites->first();

        // Check if subdomain is the same
        if ($this->subdomain === $this->currentSubdomain) {
            session()->flash('info', 'Subdomain tidak berubah.');

            return;
        }

        // Check uniqueness (exclude current website)
        $exists = Website::where('subdomain', $this->subdomain)
            ->where('id', '!=', $website->id)
            ->exists();

        if ($exists) {
            $this->addError('subdomain', 'Subdomain ini sudah digunakan. Silakan pilih yang lain.');

            return;
        }

        $website->update(['subdomain' => $this->subdomain]);
        $this->currentSubdomain = $this->subdomain;

        session()->flash('success', 'Subdomain berhasil diubah menjadi ' . $this->subdomain . '.' . parse_url(config('app.url'), PHP_URL_HOST));
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
