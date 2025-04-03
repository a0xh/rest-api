<?php declare(strict_types=1);

namespace App\Modules\Account\Events;

use App\Shared\Event;
use Ramsey\Uuid\UuidInterface;

final class MediaWasDeleted extends Event
{
    public function __construct(
        public private(set) UuidInterface $userId
    ) {}
}
