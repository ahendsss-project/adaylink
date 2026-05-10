<?php

namespace App\Livewire\Admin;

use App\Models\SubscriptionPlan;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SubscriptionPlanForm extends Component
{
    public ?SubscriptionPlan $plan = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|numeric|min:0')]
    public string $price = '';

    #[Validate('required|integer|min:0')]
    public string $max_tours = '';

    #[Validate('required|integer|min:0')]
    public string $max_vehicles = '';

    #[Validate('required|integer|min:0')]
    public string $max_pages = '';

    #[Validate('required|in:Basic,Premium,All')]
    public string $allowed_template_tier = 'Basic';

    #[Validate('boolean')]
    public bool $is_active = true;

    // Feature toggles
    public bool $feature_floating_whatsapp = false;
    public bool $feature_social_share = false;
    public bool $feature_gallery_lightbox = false;
    public bool $feature_reviews = false;

    public bool $feature_multilanguage = false;
    public bool $feature_custom_domain = false;

    public bool $isEditing = false;

    public function mount(?int $planId = null): void
    {
        if ($planId) {
            $this->plan = SubscriptionPlan::findOrFail($planId);
            $this->fill([
                'name' => $this->plan->name,
                'price' => (string) $this->plan->price,
                'max_tours' => (string) $this->plan->max_tours,
                'max_vehicles' => (string) $this->plan->max_vehicles,
                'max_pages' => (string) $this->plan->max_pages,
                'allowed_template_tier' => $this->plan->allowed_template_tier,
                'is_active' => $this->plan->is_active,
            ]);

            // Load feature toggles
            $features = $this->plan->features ?? [];
            $this->feature_floating_whatsapp = (bool) ($features['floating_whatsapp'] ?? false);
            $this->feature_social_share = (bool) ($features['social_share'] ?? false);
            $this->feature_gallery_lightbox = (bool) ($features['gallery_lightbox'] ?? false);
            $this->feature_reviews = (bool) ($features['reviews'] ?? false);
            $this->feature_multilanguage = (bool) ($features['multilanguage'] ?? false);
            $this->feature_custom_domain = (bool) ($features['custom_domain'] ?? false);

            $this->isEditing = true;
        }
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'price' => (float) $this->price,
            'max_tours' => (int) $this->max_tours,
            'max_vehicles' => (int) $this->max_vehicles,
            'max_pages' => (int) $this->max_pages,
            'allowed_template_tier' => $this->allowed_template_tier,
            'is_active' => $this->is_active,
            'features' => [
                'floating_whatsapp' => $this->feature_floating_whatsapp,
                'social_share' => $this->feature_social_share,
                'gallery_lightbox' => $this->feature_gallery_lightbox,
                'reviews' => $this->feature_reviews,
                'multilanguage' => $this->feature_multilanguage,
                'custom_domain' => $this->feature_custom_domain,
            ],
        ];

        if ($this->isEditing) {
            $this->plan->update($data);
            session()->flash('success', 'Paket berhasil diperbarui!');
        } else {
            SubscriptionPlan::create($data);
            session()->flash('success', 'Paket baru berhasil ditambahkan!');
        }

        $this->redirect(route('admin.plans.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.subscription-plan-form')
            ->layout('components.layouts.admin')
            ->title(($this->isEditing ? 'Edit' : 'Tambah') . ' Paket - Admin adaylink');
    }
}
