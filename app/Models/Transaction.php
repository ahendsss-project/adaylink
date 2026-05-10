<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'plan_id',
        'amount',
        'payment_method',
        'status',
        'approved_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    /**
     * Get the user who made the transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Transaction>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subscription plan for this transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<SubscriptionPlan, Transaction>
     */
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    /**
     * Get the admin who approved the transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Admin, Transaction>
     */
    public function approver()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }
}
