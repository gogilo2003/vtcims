<?php

namespace App\Models;

class Admin extends \Ogilo\AdminMd\Models\Admin
{
    public function staff()
    {
        return $this->hasOne('App\Models\Staff');
    }
}
