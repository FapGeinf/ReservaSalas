<?php

namespace App\Console\Commands;

use Illuminate\Console\Scheduling\Schedule;

class Kernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('ldap:importar-usuarios')->everyThirtyMinutes();
    }
}
