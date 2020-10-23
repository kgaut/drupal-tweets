<?php

namespace Drupal\tweets\Entity\AccessControlHandler;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Tweet entity.
 *
 * @see \Drupal\tweets\Entity\Tweet.
 */
class TweetAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\tweets\Entity\Tweet $entity */

    switch ($operation) {
      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'administer tweet entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

}
