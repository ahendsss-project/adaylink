<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Website;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PendingApprovals extends Component
{
    public function approve(string $userId): void
    {
        $admin = Auth::guard('admin')->user();

        DB::transaction(function () use ($userId, $admin) {
            // 1. Update user subscription
            User::where('id', $userId)->update([
                'subscription_status' => 'Active',
                'subscription_expires_at' => now()->addDays(30),
            ]);

            // 2. Activate website
            Website::where('user_id', $userId)->update([
                'is_active' => true,
            ]);

            // 3. Update pending transactions
            Transaction::where('user_id', $userId)
                ->where('status', 'Pending')
                ->update([
                    'status' => 'Success',
                    'approved_by' => $admin->id,
                ]);
        });

        session()->flash('success', 'Akun driver berhasil diaktifkan!');

        $this->dispatch('$refresh');
    }

    public function render()
    {
        $pendingUsers = User::where('subscription_status', 'Pending')
            ->with(['websites', 'transactions'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.admin.pending-approvals', compact('pendingUsers'))
            ->layout('components.layouts.admin')
            ->title('Pending Approvals - Admin adaylink');
    }
}
