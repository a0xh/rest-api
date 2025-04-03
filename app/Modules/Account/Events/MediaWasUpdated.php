<?php declare(strict_types=1);

namespace App\Modules\Account\Events;

use App\Shared\Event;
use Illuminate\Http\UploadedFile;
use App\Entities\User;

final class MediaWasUpdated extends Event
{
    public function __construct(
        public private(set) UploadedFile $avatar,
        public private(set) User $user
    ) {}
}
