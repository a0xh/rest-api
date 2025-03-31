<?php declare(strict_types=1);

namespace App\Shared;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
}
