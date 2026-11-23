<?php

declare(strict_types=1);

namespace Mgd\SoldOut\Kit\Platform\Shopware;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

// tägliche Aktiv-Meldung (Install-Client), Name je Plugin eindeutig, läuft nur mit Shopware-Scheduler
final class PingTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'mgd_kit.mgd_ausverkauft_shopware.ping';
    }

    public static function getDefaultInterval(): int
    {
        return 86400;
    }
}
