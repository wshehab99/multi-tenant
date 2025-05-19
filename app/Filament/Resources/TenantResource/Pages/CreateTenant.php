<?php

namespace App\Filament\Resources\TenantResource\Pages;

use App\Filament\Resources\TenantResource;
use App\Models\Tenant;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $tenant = Tenant::create([
            'name' => $data['name'],
        ]);
        $tenant->domains()->create(['domain' => $data['domain']]);

        tenancy()->initialize($tenant);

        // Run migrations
        $this->runMigrations();
        // Create admin user
        $this->createAdminUser($data);

        tenancy()->end();

        return $tenant;
    }

    protected function runMigrations(): void
    {
        $migrator = app('migrator');
        $migrator->setOutput(new \Symfony\Component\Console\Output\NullOutput);
        $migrator->run(database_path('migrations/tenant'));
    }

    protected function createAdminUser(array $data): void
    {
        config()->set('database.default', 'tenant');

        \App\Models\User::create([
            'name' => $data['admin_name'],
            'email' => $data['admin_email'],
            'password' => Hash::make($data['admin_password']),
        ]);

        config()->set('database.default', 'mysql');
    }
}
