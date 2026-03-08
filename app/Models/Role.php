<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    // Extended from Spatie Role model - no additional changes needed
    // This resolves the conflict with Spatie's role system
}
