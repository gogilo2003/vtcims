<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Remark extends Model
{
    /**
     * Get all of the grades for the Remark
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
