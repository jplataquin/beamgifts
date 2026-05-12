<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('vouchers:expire')->hourly();
