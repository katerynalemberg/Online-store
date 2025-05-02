<?php

namespace Drupal\utils\Resolver;

use Drupal\commerce\Context;
use Drupal\commerce\PurchasableEntityInterface;
use Drupal\commerce_price\Resolver\PriceResolverInterface;
use Drupal\commerce_order\Entity\OrderItemInterface;
use Drupal\utils\UserPurchasedOrders;

/**
 * Resolves the price fetched from the product variation fields.
 */
class PriceResolver implements PriceResolverInterface {

  public function __construct(
    protected UserPurchasedOrders $userPurchasedOrders
  ) {}

  /**
   * {@inheritdoc}
   */
  public function resolve(PurchasableEntityInterface $entity, $quantity, Context $context) {
    $orders = $this->userPurchasedOrders->getAllOrders();
    if ($orders) {
      foreach ($orders as $order) {
        foreach ($order->getItems() as $order_item) {
          if ($this->isMembershipProduct($order_item)) {
            return $entity->getPrice()->multiply('0.90');
          }
        }
      }
    }
    return $entity->getPrice();
  }

  /**
   * Checks if the order item is a membership product.
   */
  protected function isMembershipProduct(OrderItemInterface $order_item): bool {
    $purchasable_entity = $order_item->getPurchasedEntity();
    if ($purchasable_entity) {
      return $purchasable_entity->getEntityTypeId() === 'commerce_product_variation'
        && $purchasable_entity->getProduct()->bundle() === 'membership';
    }
    return FALSE;
  }

}
