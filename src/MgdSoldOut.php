<?php declare(strict_types=1);

namespace Mgd\SoldOut;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class MgdSoldOut extends Plugin
{
    private const HIDE_CLOSEOUT_CONFIG = 'core.listing.hideCloseoutProductsWhenOutOfStock';
    private const BACKUP_CONFIG = 'MgdSoldOut.internal.previousHideCloseoutSettings';

    public function activate(ActivateContext $activateContext): void
    {
        parent::activate($activateContext);
        $this->keepCloseoutProductsVisible($activateContext->getContext());
    }

    public function deactivate(DeactivateContext $deactivateContext): void
    {
        $this->restoreCloseoutVisibilitySettings();
        parent::deactivate($deactivateContext);
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        $this->restoreCloseoutVisibilitySettings();
        parent::uninstall($uninstallContext);
    }

    private function keepCloseoutProductsVisible(Context $context): void
    {
        if (!$this->container->has(SystemConfigService::class) || !$this->container->has('sales_channel.repository')) {
            return;
        }

        /** @var SystemConfigService $config */
        $config = $this->container->get(SystemConfigService::class);
        /** @var EntityRepository $salesChannelRepository */
        $salesChannelRepository = $this->container->get('sales_channel.repository');

        $existingBackup = $config->get(self::BACKUP_CONFIG);
        if (!\is_array($existingBackup)) {
            $backup = ['global' => $config->get(self::HIDE_CLOSEOUT_CONFIG), 'salesChannels' => []];

            $ids = $salesChannelRepository->searchIds(new Criteria(), $context)->getIds();
            foreach ($ids as $salesChannelId) {
                $backup['salesChannels'][$salesChannelId] = $config->get(self::HIDE_CLOSEOUT_CONFIG, $salesChannelId);
            }

            $config->set(self::BACKUP_CONFIG, $backup);
        }

        $config->set(self::HIDE_CLOSEOUT_CONFIG, false);

        $ids = $salesChannelRepository->searchIds(new Criteria(), $context)->getIds();
        foreach ($ids as $salesChannelId) {
            $config->set(self::HIDE_CLOSEOUT_CONFIG, false, $salesChannelId);
        }
    }

    private function restoreCloseoutVisibilitySettings(): void
    {
        if (!$this->container->has(SystemConfigService::class)) {
            return;
        }

        /** @var SystemConfigService $config */
        $config = $this->container->get(SystemConfigService::class);
        $backup = $config->get(self::BACKUP_CONFIG);
        if (!\is_array($backup)) {
            return;
        }

        $this->restoreValue($config, $backup['global'] ?? null, null);

        foreach (($backup['salesChannels'] ?? []) as $salesChannelId => $value) {
            if (\is_string($salesChannelId) && $salesChannelId !== '') {
                $this->restoreValue($config, $value, $salesChannelId);
            }
        }

        $config->delete(self::BACKUP_CONFIG);
    }

    private function restoreValue(SystemConfigService $config, mixed $value, ?string $salesChannelId): void
    {
        if ($value === null) {
            $config->delete(self::HIDE_CLOSEOUT_CONFIG, $salesChannelId);
            return;
        }

        $config->set(self::HIDE_CLOSEOUT_CONFIG, $value, $salesChannelId);
    }
}
