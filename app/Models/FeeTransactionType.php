<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeTransactionType extends Model
{
    use HasFactory;
    /**
     * Get all of the fee_transactions for the FeeTransactionType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fee_transactions(): HasMany
    {
        return $this->hasMany(FeeTransaction::class, 'transaction_type_id', 'id');
    }
}
