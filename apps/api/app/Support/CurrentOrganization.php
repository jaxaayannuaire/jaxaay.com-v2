<?php

namespace App\Support;

use App\Models\Organization;
use RuntimeException;

final class CurrentOrganization
{
    private ?Organization $organization = null;

    public function set(Organization $organization): void
    {
        $this->organization = $organization;
    }

    public function get(): Organization
    {
        return $this->organization ?? throw new RuntimeException('Aucune organisation courante.');
    }
}
