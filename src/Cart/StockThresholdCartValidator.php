<?php declare(strict_types=1);

namespace Mgd\SoldOut\Cart;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartValidatorInterface;
use Shopware\Core\Checkout\Cart\Error\ErrorCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Content\Product\Cart\ProductStockReachedError;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\Entity\SalesChannelRepository;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;

final class StockThresholdCartValidator implements CartValidatorInterface
{
    public function __construct(
        private readonly SalesChannelRepository $productRepository,
        private readonly SystemConfigService $systemConfig,
    ) {
    }

    public function validate(Cart $cart, ErrorCollection $errors, SalesChannelContext $context): void
    {
        $salesChannelId = $context->getSalesChannelId();
        if (!$this->systemConfig->getBool('MgdSoldOut.config.thresholdEnabled', $salesChannelId)) {
            return;
        }

        $threshold = max(0, $this->systemConfig->getInt('MgdSoldOut.config.stockThreshold', $salesChannelId));
        $onlyCloseout = $this->systemConfig->getBool('MgdSoldOut.config.onlyCloseoutProducts', $salesChannelId);

        $items = array_filter(
            $cart->getLineItems()->getFlat(),
            static fn (LineItem $item): bool => $item->getType() === LineItem::PRODUCT_LINE_ITEM_TYPE && $item->getReferencedId() !== null
        );

        if ($items === []) {
            return;
        }

        $ids = array_values(array_unique(array_map(static fn (LineItem $item): string => (string) $item->getReferencedId(), $items)));
        $products = $this->productRepository->search(new Criteria($ids), $context)->getEntities();

        $requested = [];
        $labels = [];
        foreach ($items as $item) {
            $id = (string) $item->getReferencedId();
            $requested[$id] = ($requested[$id] ?? 0) + $item->getQuantity();
            $labels[$id] = (string) $item->getLabel();
        }

        foreach ($requested as $productId => $quantity) {
            $product = $products->get($productId);
            if ($product === null || ($onlyCloseout && !$product->getIsCloseout())) {
                continue;
            }

            $sellable = max(0, $product->getAvailableStock() - $threshold);
            if ($quantity <= $sellable) {
                continue;
            }

            $errors->add(new ProductStockReachedError(
                $productId,
                $labels[$productId] ?? $productId,
                $sellable,
                false
            ));
        }
    }
}
