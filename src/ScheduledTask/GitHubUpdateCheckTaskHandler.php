<?php declare(strict_types=1);

namespace Mgd\SoldOut\ScheduledTask;

use Mgd\SoldOut\Service\GitHubReleaseUpdater;
use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(handles: GitHubUpdateCheckTask::class)]
final class GitHubUpdateCheckTaskHandler extends ScheduledTaskHandler
{
    public function __construct(
        EntityRepository $scheduledTaskRepository,
        LoggerInterface $logger,
        private readonly SystemConfigService $config,
        private readonly GitHubReleaseUpdater $updater,
    ) {
        parent::__construct($scheduledTaskRepository, $logger);
    }

    public function run(): void
    {
        // Die Entscheidung gilt global. Verkaufskanal-Overrides dürfen keine
        // Hintergrundänderung an gemeinsam genutzten Plugin-Dateien auslösen.
        if (!$this->config->getBool('MgdSoldOut.config.automaticUpdates')) {
            return;
        }
        try {
            $this->updater->checkAndPrepare(Context::createDefaultContext());
        } catch (\Throwable $exception) {
            $this->exceptionLogger->warning('MGD Ausverkauft: GitHub-Updateprüfung fehlgeschlagen.', [
                'exception' => $exception,
            ]);
        }
    }
}
