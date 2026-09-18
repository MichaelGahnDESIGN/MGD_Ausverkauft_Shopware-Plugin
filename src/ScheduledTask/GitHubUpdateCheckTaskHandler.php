<?php declare(strict_types=1);

namespace Mgd\SoldOut\ScheduledTask;

use Mgd\SoldOut\Service\GitHubReleaseUpdater;
use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(handles: GitHubUpdateCheckTask::class)]
final class GitHubUpdateCheckTaskHandler extends ScheduledTaskHandler
{
    public function __construct(
        EntityRepository $scheduledTaskRepository,
        LoggerInterface $logger,
        private readonly GitHubReleaseUpdater $updater,
    ) {
        parent::__construct($scheduledTaskRepository, $logger);
    }

    public function run(): void
    {
        try {
            $this->updater->checkAndPrepare(Context::createDefaultContext());
        } catch (\Throwable $exception) {
            $this->logger->warning('MGD Ausverkauft: GitHub-Updateprüfung fehlgeschlagen.', [
                'exception' => $exception,
            ]);
        }
    }
}
