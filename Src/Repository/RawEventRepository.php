<?php

declare(strict_types=1);

namespace App\Domain;

use App\Repository\RawEventRepositoryInterface;
use PDO;
use App\Support\uuid;

final class RawEventRepository implements RawEventRepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function insert(array $payload, bool $isValid, ?string $error = null): int
    {

        $uuid = Uuid::v4();
        $pdostatement = $this->pdo->prepare(query: "
            INSERT INTO events_raw (uuid, received_at, payload, is_valid, error)
            VALUES (:uuid, :received_at, :payload, :is_valid, :error)
        ");

        $pdostatement->execute(params: [
            'uuid' => $uuid,
            'recieved_at' =>date(format: DATE_ATOM),
            'payload' => json_encode(value: $payload),
            'is_valid' => $isValid ? 1 : 0,
            'error' => $error
        ]);

        return (int) $this->pdo->lastInsertId();

    }
}