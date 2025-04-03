<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command(
    command: 'directories:clean',
    parameters: [
        '--path' => 'storage/app/public/avatars',
    ]
)->daily();
