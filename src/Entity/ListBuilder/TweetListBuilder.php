<?php

namespace Drupal\tweets\Entity\ListBuilder;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Tweet entities.
 *
 * @ingroup tweets
 */
class TweetListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Tweet ID');
    $header['author'] = $this->t('Author');
    $header['name'] = $this->t('Tweet');
    $header['retweet'] = $this->t('RT');
    $header['likes'] = $this->t('Likes');
    $header['created'] = $this->t('Created');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\tweets\Entity\Tweet $entity */
    $row['id'] = $entity->id();
    $row['author'] = $entity->getAuthor();
    $row['name'] = $entity->label();
    $row['retweet'] = $entity->getRetweetsNumber();
    $row['likes'] = $entity->getLikesNumber();
    $row['created'] = \Drupal::service('date.formatter')->format($entity->getCreatedTime(), 'short') ;
    return $row + parent::buildRow($entity);
  }

}
