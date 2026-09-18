<?php declare(strict_types=1);

namespace Mgd\SoldOut;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class MgdSoldOut extends Plugin
{
    private const HIDE_CLOSEOUT_CONFIG = 'core.listing.hideCloseoutProductsWhenOutOfStock';

    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);
        $this->keepCloseoutProductsVisible();
    }

    public function activate(ActivateContext $activateContext): void
    {
        parent::activate($activateContext);
        $this->keepCloseoutProductsVisible();
    }

    private function keepCloseoutProductsVisible(): void
    {
        if (!$this->container->has(SystemConfigService::class)) {
            return;
        }

        $this->container->get(SystemConfigService::class)->set(self::HIDE_CLOSEOUT_CONFIG, false);
    }
}
