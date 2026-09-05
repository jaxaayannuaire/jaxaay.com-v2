<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrganizationService
{
    public function create(User $user, string $name): Organization
    {
        return DB::transaction(function () use ($user, $name) {
            $base = Str::slug($name);
            $slug = $base;
            $i = 2;
            while (Organization::where('slug', $slug)->exists()) {
                $slug = $base.'-'.($i++);
            } $o = Organization::create(['name' => $name, 'slug' => $slug, 'owner_user_id' => $user->id]);
            $o->users()->attach($user->id, ['role' => 'owner']);

            return $o;
        });
    }
}
