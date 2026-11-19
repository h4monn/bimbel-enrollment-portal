<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('registrations:expire-pending')
    ->everyFiveMinutes()
    ->withoutOverlapping();
