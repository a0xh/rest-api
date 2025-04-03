<?php declare(strict_types=1);

namespace App\Modules\Account\Queries;

use Ramsey\Uuid\{Uuid, UuidInterface};
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use App\Shared\Query;

final class GetUserByIdQuery extends Query
{
    private string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function getId(): ?UuidInterface
    {
        if (!is_string(value: $this->userId)) {
            return null;
        }

        try {
            return Uuid::fromString(uuid: $this->userId);
        }

        catch (InvalidUuidStringException $e) {
            return null;
        }
    }
}
