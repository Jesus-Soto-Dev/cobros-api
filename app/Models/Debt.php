<?php

namespace App\Models;

use App\Enums\DebtStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Debt extends Model
{
    /** @use HasFactory<\Database\Factories\DebtFactory> */
    use HasFactory;

    protected $fillable = [
        'debtor_id',
        'amount',
        'due_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
            'status' => DebtStatus::class,
        ];
    }

    public function debtor(): BelongsTo
    {
        return $this->belongsTo(Debtor::class);
    }

    public function actions(): HasMany
    {
        return $this->hasMany(DebtAction::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
