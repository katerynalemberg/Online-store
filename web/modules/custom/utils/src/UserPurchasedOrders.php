<?php

declare(strict_types=1);

namespace Drupal\utils;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Retrieves all completed orders purchased by the current user.
 */
final class UserPurchasedOrders {

  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
    protected AccountInterface $currentUser,
  ) {}

  /**
   * List all purchased orders for current user.
   */
  public function getAllOrders(): array {
    $user_id = $this->currentUser->id();
    $user = $this->entityTypeManager->getStorage('user')->load($user_id);

    if ($user) {
      return $this->entityTypeManager
        ->getStorage('commerce_order')
        ->loadByProperties(['uid' => $user->id(), 'state' => 'completed']);
    }
    return [];
  }
}
