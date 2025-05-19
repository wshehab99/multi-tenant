<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Auth\Authenticatable;

class TenantUserProvider extends EloquentUserProvider
{
    public function __construct(Hasher $hasher, $model)
    {
        parent::__construct($hasher, $model);
    }
    public function retrieveByCredentials(array $credentials)
    {
        $query = $this->createModel()->newQuery();

        // Add tenant scope
        if ($tenantId = tenant('id')) {
            $query->where('tenant_id', $tenantId);
        }

        foreach ($credentials as $key => $value) {
            if (!str_contains($key, 'password')) {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }
}
