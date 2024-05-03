<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeTransactionMode extends Model
{
    use HasFactory;
    /**
     * Get all of the fee_transactions for the FeeTransactionMode
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fee_transactions(): HasMany
    {
        return $this->hasMany(FeeTransaction::class, 'transaction_mode_id', 'id');
    }
}
