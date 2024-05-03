<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeTransaction extends Model
{
    public function student()
    {
        return $this->belongsTo('App\Models\Student');
    }

    public function fee()
    {
        return $this->belongsTo('App\Models\Fee');
    }

    public function vote_head()
    {
        return $this->belongsTo('App\Models\FeeVoteHead');
    }

    /**
     * Get the transaction_type that owns the FeeTransaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction_type(): BelongsTo
    {
        return $this->belongsTo(FeeTransactionType::class, 'transaction_type_id', 'id');
    }

    /**
     * Get the transaction_mode that owns the FeeTransaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction_mode(): BelongsTo
    {
        return $this->belongsTo(FeeTransactionMode::class, 'transaction_mode_id', 'id');
    }
}
