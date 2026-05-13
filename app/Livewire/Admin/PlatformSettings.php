<?php

namespace App\Livewire\Admin;

use App\Models\PlatformConfig;
use Livewire\Component;
use Livewire\WithFileUploads;

class PlatformSettings extends Component
{
    use WithFileUploads;

    public string $app_name = 'adaylink';
    public string $tagline = '';
    public string $main_logo_url = '';
    public string $favicon_url = '';
    public string $admin_whatsapp = '';
    public string $admin_email = '';
    public string $google_analytics_id = '';
    public bool $maintenance_mode = false;

    public $logo_file = null;
    public $favicon_file = null;

    public function mount(): void
    {
        $config = PlatformConfig::first();

        if ($config) {
            $this->app_name = $config->app_name ?? 'adaylink';
            $this->tagline = $config->tagline ?? '';
            $this->main_logo_url = $config->main_logo_url ?? '';
            $this->favicon_url = $config->favicon_url ?? '';
            $this->admin_whatsapp = $config->admin_whatsapp ?? '';
            $this->admin_email = $config->admin_email ?? '';
            $this->google_analytics_id = $config->google_analytics_id ?? '';
            $this->maintenance_mode = $config->maintenance_mode ?? false;
        }
    }

    private string $fileRules = 'nullable|file|mimes:jpg,jpeg,png,webp|max:1024|dimensions:min_width=16,min_height=16';

    public function updatedLogoFile(): void
    {
        $this->validateOnly('logo_file', ['logo_file' => $this->fileRules], [
            'logo_file.mimes' => 'Format gambar harus WEBP, PNG, atau JPG.',
            'logo_file.max' => 'Ukuran gambar maksimal 1 MB.',
        ]);
    }

    public function updatedFaviconFile(): void
    {
        $this->validateOnly('favicon_file', ['favicon_file' => $this->fileRules], [
            'favicon_file.mimes' => 'Format gambar harus WEBP, PNG, atau JPG.',
            'favicon_file.max' => 'Ukuran gambar maksimal 1 MB.',
        ]);
    }

    public function save(): void
    {
        $this->validate([
            'app_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'main_logo_url' => 'nullable|string|max:500',
            'favicon_url' => 'nullable|string|max:500',
            'admin_whatsapp' => 'nullable|string|max:20',
            'admin_email' => 'nullable|email|max:255',
            'google_analytics_id' => 'nullable|string|max:50',
            'maintenance_mode' => 'boolean',
            'logo_file' => $this->fileRules,
            'favicon_file' => $this->fileRules,
        ], [
            'logo_file.mimes' => 'Format logo harus WEBP, PNG, atau JPG.',
            'logo_file.max' => 'Ukuran logo maksimal 1 MB.',
            'favicon_file.mimes' => 'Format favicon harus WEBP, PNG, atau JPG.',
            'favicon_file.max' => 'Ukuran favicon maksimal 1 MB.',
        ]);

        // Handle logo file upload
        if ($this->logo_file) {
            $this->main_logo_url = upload_url(upload_store('platform', $this->logo_file));
            $this->logo_file = null;
        }

        // Handle favicon file upload
        if ($this->favicon_file) {
            $this->favicon_url = upload_url(upload_store('platform', $this->favicon_file));
            $this->favicon_file = null;
        }

        PlatformConfig::updateOrCreate(
            ['id' => 1],
            [
                'app_name' => $this->app_name,
                'tagline' => $this->tagline ?: null,
                'main_logo_url' => $this->main_logo_url ?: null,
                'favicon_url' => $this->favicon_url ?: null,
                'admin_whatsapp' => $this->admin_whatsapp ?: null,
                'admin_email' => $this->admin_email ?: null,
                'google_analytics_id' => $this->google_analytics_id ?: null,
                'maintenance_mode' => $this->maintenance_mode,
            ]
        );

        session()->flash('success', 'Pengaturan platform berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.admin.platform-settings')
            ->layout('components.layouts.admin')
            ->title('Platform Settings - adaylink');
    }
}
