<?php

namespace App\Models;

use App\Enums\DebtActionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DebtAction extends Model
{
    /** @use HasFactory<\Database\Factories\DebtActionFactory> */
    use HasFactory;

    protected $fillable = [
        'debt_id',
        'user_id',
        'type',
        'notes',
        'action_date',
    ];

    protected function casts(): array
    {
        return [
            'type' => DebtActionType::class,
            'action_date' => 'datetime',
        ];
    }

    public function debt(): BelongsTo
    {
        return $this->belongsTo(Debt::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
