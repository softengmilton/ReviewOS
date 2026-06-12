<?php

namespace App\Services;

use App\Models\Board;
use App\Models\Organization;
use App\Models\PostStatus;

class DefaultOrganizationSetup
{
    public function seed(Organization $organization): void
    {
        foreach ($this->statuses() as $index => $status) {
            PostStatus::withoutGlobalScopes()->firstOrCreate(
                [
                    'organization_id' => $organization->id,
                    'type' => $status['type'],
                ],
                [
                    'name' => $status['name'],
                    'color' => $status['color'],
                    'sort_order' => $index,
                    'notify_subscribers' => true,
                ]
            );
        }

        Board::withoutGlobalScopes()->firstOrCreate(
            [
                'organization_id' => $organization->id,
                'slug' => 'feature-requests',
            ],
            [
                'name' => 'Feature Requests',
                'description' => 'Collect and prioritize customer product feedback.',
                'type' => 'public',
                'is_active' => true,
                'sort_order' => 0,
            ]
        );
    }

    /**
     * @return array<int, array{name: string, type: string, color: string}>
     */
    public function statuses(): array
    {
        return [
            ['name' => 'Open', 'type' => 'open', 'color' => '#2563eb'],
            ['name' => 'Planned', 'type' => 'planned', 'color' => '#7c3aed'],
            ['name' => 'In Progress', 'type' => 'in_progress', 'color' => '#d97706'],
            ['name' => 'Done', 'type' => 'done', 'color' => '#16a34a'],
            ['name' => 'Declined', 'type' => 'declined', 'color' => '#dc2626'],
        ];
    }
}
