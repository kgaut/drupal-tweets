<?php

namespace Drupal\tweets\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Tweet entity.
 *
 * @ingroup tweets
 *
 * @ContentEntityType(
 *   id = "tweet",
 *   label = @Translation("Tweet"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\ViewBuilder\EntityViewBuilder",
 *     "list_builder" = "Drupal\tweets\Entity\ListBuilder\TweetListBuilder",
 *     "views_data" = "Drupal\tweets\Entity\ViewsData\TweetViewsData", *
 *     "form" = {
 *       "delete" = "Drupal\tweets\Entity\Form\TweetDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\tweets\Entity\HtmlRouteProvider\TweetHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\tweets\Entity\AccessControlHandler\TweetAccessControlHandler",
 *   },
 *   base_table = "tweets",
 *   translatable = FALSE,
 *   admin_permission = "administer tweet entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "tweet",
 *   },
 *   links = {
 *     "delete-form" = "/admin/content/tweets/{tweet}/delete",
 *     "collection" = "/admin/content/tweets",
 *   }
 * )
 */
class Tweet extends ContentEntityBase {

  public static function loadByTwitterId($twitterId) {
    $tweets = \Drupal::entityTypeManager()->getStorage('tweet')->loadByProperties(['id_twitter' => $twitterId]);
    return $tweets ? array_pop($tweets) : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('tweet')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('tweet', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  public function getRetweetsNumber() {
    return (int) $this->get('retweet_count')->value;
  }

  public function getLikesNumber() {
    return (int) $this->get('favorite_count')->value;
  }

  public function getAuthor() {
    return $this->get('author')->value;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['id_twitter'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Twitter ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE)
      ->setSetting('size', 'big');

    $fields['tweet'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Tweet'))
      ->setDescription(t('The tweet.'))
      ->setSetting('max_length', 400)
      ->setSetting('text_processing', 0)
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['author'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Author'))
      ->setSetting('max_length', 50)
      ->setSetting('text_processing', 0)
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['author_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Author name'))
      ->setSetting('max_length', 100)
      ->setSetting('text_processing', 0)
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['favorite_count'] = BaseFieldDefinition::create('integer')
      ->setLabel('Favorited')
      ->setSetting('unsigned', TRUE)
      ->setSetting('min', 0)
      ->setDefaultValue(0)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['retweet_count'] = BaseFieldDefinition::create('integer')
      ->setLabel('Retweeted')
      ->setSetting('unsigned', TRUE)
      ->setSetting('min', 0)
      ->setDefaultValue(0)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the tweet was posted.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the tweet was updated.'));

    return $fields;
  }

}
