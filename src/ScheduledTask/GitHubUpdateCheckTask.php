<?php declare(strict_types=1);

namespace Mgd\SoldOut\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

final class GitHubUpdateCheckTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'mgd_sold_out.github_update_check';
    }

    public static function getDefaultInterval(): int
    {
        return 21600;
    }
}
