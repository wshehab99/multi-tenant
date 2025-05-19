<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain as BaseDomain;
use Illuminate\Database\Eloquent\Model;

class Domain extends BaseDomain
{
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
