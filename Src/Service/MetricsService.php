<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;
use Throwable;
use PDO;

final class MetricsService 
{
    public function __construct(private PDO $pdo)
    {

    }

    public function getDashboardData(?string $userId = null): array
    {
        return [
            'summary' => $this->getSummary($userId),
            'events_per_type' => $this->getEventsPerType($userId),
            'events_per_day' => $this->getEventsPerDay($userId),
            'recent_events' => $this->getResentEvents($userId)
        ];
    }

    private function getSummary(?string $userId): array 
    {
        $sql = `
        SELECT 
                COUNT(*) AS total_events,
                COALESCE(SUM(points), 0) AS total_points,
                COALESCE(AVG(progress), 0) AS average_progress
            FROM user_activity_events
        `;

        $params = [];

        if($userId !== null) {
            $sql .= "WHERE user_id = :user_id";
            $params['user_id'] = $user_id;
        } 

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(params: $params);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'total_events' => (int) ($row['total_events'] ?? 0),
            'total_points' => (int) ($row['total_points'] ?? 0),
            'average_progress' => round((float) ($row['average_progress'])
        ];
    }
    
}