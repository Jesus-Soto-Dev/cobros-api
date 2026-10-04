<?php

namespace App\Models;

use Database\Factories\DebtorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Debtor extends Model
{
    /** @use HasFactory<DebtorFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'document',
        'email',
        'phone',
    ];

    public function debts(): HasMany
    {
        return $this->hasMany(Debt::class);
    }
}
