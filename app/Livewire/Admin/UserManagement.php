<?php

namespace App\Livewire\Admin;

use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    // Search & filter
    public string $search = '';
    public string $filterPlan = '';

    // Edit subscription modal
    public bool $showEditModal = false;
    public ?string $editUserId = null;
    public ?int $editPlanId = null;
    public string $editExpiresAt = '';

    // Admin note modal
    public bool $showNoteModal = false;
    public ?string $noteUserId = null;
    public string $adminNote = '';

    protected function rules(): array
    {
        return [
            'editPlanId' => ['required', 'exists:subscription_plans,id'],
            'editExpiresAt' => ['required', 'date'],
        ];
    }

    protected array $messages = [
        'editPlanId.required' => 'Pilih paket langganan.',
        'editPlanId.exists' => 'Paket tidak valid.',
        'editExpiresAt.required' => 'Tanggal expired wajib diisi.',
        'editExpiresAt.date' => 'Format tanggal tidak valid.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterPlan(): void
    {
        $this->resetPage();
    }

    /**
     * Log an action to audit_logs.
     */
    private function logAudit(string $action, string $targetUserId, ?array $details = null): void
    {
        AuditLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'target_user_id' => $targetUserId,
            'action' => $action,
            'details' => $details,
        ]);
    }

    /**
     * Toggle user verification status.
     */
    public function toggleVerify(string $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['is_verified' => !$user->is_verified]);

        $this->logAudit(
            $user->is_verified ? 'Verify User' : 'Unverify User',
            $userId,
            ['is_verified' => $user->is_verified],
        );

        $status = $user->is_verified ? 'terverifikasi' : 'batal terverifikasi';
        session()->flash('success', "User {$user->full_name} berhasil {$status}.");

        $this->dispatch('$refresh');
    }

    /**
     * Toggle user block status.
     */
    public function toggleBlock(string $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['is_blocked' => !$user->is_blocked]);

        $this->logAudit(
            $user->is_blocked ? 'Block User' : 'Unblock User',
            $userId,
            ['is_blocked' => $user->is_blocked],
        );

        $status = $user->is_blocked ? 'diblokir' : 'dibuka blokirnya';
        session()->flash('success', "User {$user->full_name} berhasil {$status}.");

        $this->dispatch('$refresh');
    }

    /**
     * Open edit subscription modal.
     */
    public function openEditModal(string $userId): void
    {
        $user = User::with('plan')->findOrFail($userId);
        $this->editUserId = $userId;
        $this->editPlanId = $user->plan_id;
        $this->editExpiresAt = $user->subscription_expires_at?->format('Y-m-d') ?? now()->addDays(30)->format('Y-m-d');
        $this->showEditModal = true;
    }

    /**
     * Save edited subscription.
     */
    public function saveSubscription(): void
    {
        $this->validate();

        $user = User::findOrFail($this->editUserId);
        $oldPlan = $user->plan?->name ?? 'None';
        $oldExpires = $user->subscription_expires_at?->format('Y-m-d') ?? 'None';

        DB::transaction(function () use ($user, $oldPlan, $oldExpires) {
            $newPlan = SubscriptionPlan::find($this->editPlanId);

            $user->update([
                'plan_id' => $this->editPlanId,
                'subscription_plan' => $newPlan?->name ?? $user->subscription_plan,
                'subscription_expires_at' => $this->editExpiresAt,
                'subscription_status' => 'Active',
            ]);

            $this->logAudit('Edit Subscription', $user->id, [
                'old_plan' => $oldPlan,
                'new_plan' => $newPlan?->name,
                'old_expires_at' => $oldExpires,
                'new_expires_at' => $this->editExpiresAt,
            ]);
        });

        $this->showEditModal = false;
        $this->reset(['editUserId', 'editPlanId', 'editExpiresAt']);

        session()->flash('success', "Subscription user {$user->full_name} berhasil diperbarui.");
    }

    /**
     * Open admin note modal.
     */
    public function openNoteModal(string $userId): void
    {
        $user = User::findOrFail($userId);
        $this->noteUserId = $userId;
        $this->adminNote = $user->admin_note ?? '';
        $this->showNoteModal = true;
    }

    /**
     * Save admin note.
     */
    public function saveNote(): void
    {
        $user = User::findOrFail($this->noteUserId);
        $user->update(['admin_note' => $this->adminNote]);

        $this->logAudit('Update Admin Note', $user->id, [
            'admin_note' => $this->adminNote,
        ]);

        $this->showNoteModal = false;
        $this->reset(['noteUserId', 'adminNote']);

        session()->flash('success', "Catatan admin untuk {$user->full_name} berhasil disimpan.");
    }

    /**
     * Close modals and reset state.
     */
    public function closeModal(): void
    {
        $this->showEditModal = false;
        $this->showNoteModal = false;
        $this->reset(['editUserId', 'editPlanId', 'editExpiresAt', 'noteUserId', 'adminNote']);
    }

    /**
     * Impersonate a user — login as driver without password.
     */
    public function impersonate(string $userId)
    {
        $admin = Auth::guard('admin')->user();
        $user = User::findOrFail($userId);

        // Store admin ID in session so we can return later
        session(['impersonating_admin' => $admin->id]);

        // Log the impersonation
        $this->logAudit('Impersonate User', $userId, [
            'user_name' => $user->full_name,
            'user_email' => $user->email,
        ]);

        // Login as the user on the web guard
        Auth::guard('web')->login($user);

        // Redirect to driver dashboard
        return $this->redirect(route('driver.dashboard'), navigate: true);
    }

    public function render()
    {
        $plans = SubscriptionPlan::orderBy('price')->get();

        $users = User::with('plan')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('full_name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterPlan, function ($query) {
                $query->where('plan_id', $this->filterPlan);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.admin.user-management', compact('users', 'plans'))
            ->layout('components.layouts.admin')
            ->title('User Management - Admin adaylink');
    }
}
