<?php

namespace App\Livewire\Onboarding;

use App\Models\PlatformConfig;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Paywall extends Component
{
    public ?Transaction $transaction = null;

    public string $whatsappLink = '';

    public function mount(): void
    {
        $user = Auth::guard('web')->user();
        $website = $user->websites()->first();

        // If no website claimed yet, redirect back to subdomain claim
        if (! $website) {
            $this->redirect(route('onboarding.subdomain'), navigate: true);

            return;
        }

        // Get the user's selected plan
        $plan = $user->plan;

        // Determine the amount from the plan price
        $amount = $plan ? (float) $plan->price : 0;

        // Create or find the pending transaction
        $this->transaction = Transaction::firstOrCreate(
            [
                'user_id' => $user->id,
                'status' => 'Pending',
            ],
            [
                'amount' => $amount,
                'payment_method' => 'Transfer via WhatsApp',
                'plan_id' => $plan?->id,
            ]
        );

        // Update transaction amount if plan changed
        if ($this->transaction->amount != $amount) {
            $this->transaction->update([
                'amount' => $amount,
                'plan_id' => $plan?->id,
            ]);
        }

        // Build WhatsApp link with plan info
        $adminWhatsapp = PlatformConfig::first()?->admin_whatsapp ?? '6281234567890';
        $message = $this->buildWhatsAppMessage(
            $user->email,
            $website->subdomain,
            $plan?->name ?? 'Tanpa Paket',
            $amount
        );
        $this->whatsappLink = "https://wa.me/{$adminWhatsapp}?text=" . urlencode($message);
    }

    private function buildWhatsAppMessage(string $email, string $subdomain, string $planName, float $amount): string
    {
        $formattedAmount = 'Rp ' . number_format($amount, 0, ',', '.');

        return "Halo Admin adaylink,\n\n"
            . "Saya ingin mengaktifkan akun saya.\n\n"
            . "Detail Pendaftaran:\n"
            . "- Email: {$email}\n"
            . "- Subdomain: {$subdomain}.adaylink.com\n"
            . "- Paket: {$planName}\n"
            . "- Tagihan: {$formattedAmount}/bulan\n\n"
            . "Mohon informasi langkah pembayaran selanjutnya.\n\n"
            . "Terima kasih!";
    }

    public function render()
    {
        $user = Auth::guard('web')->user();
        $plan = $user->plan;

        return view('livewire.onboarding.paywall', compact('plan'))
            ->layout('components.layouts.onboarding');
    }
}
