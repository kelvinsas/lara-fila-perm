<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups;

class BackupsPage extends Backups
{
    // add this for work with BezhanSalleh\FilamentShield
   // use BezhanSalleh\FilamentShield\Traits\HasPageShield;

   protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasPermissionTo('view-backup');
    }

    public function mount(): void
    {
        abort_unless(auth()->user()->hasPermissionTo('view-backup'), 403);
    }
}
