<?php

namespace App\Observers;

use App\Models\Organization;
use App\Services\DefaultOrganizationSetup;

class OrganizationObserver
{
    public function created(Organization $organization): void
    {
        app(DefaultOrganizationSetup::class)->seed($organization);
    }
}
