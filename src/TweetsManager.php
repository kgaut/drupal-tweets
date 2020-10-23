<?php

namespace Drupal\tweets;

use DG\Twitter\Twitter;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Messenger\Messenger;

/**
 * TweetsManager service.
 */
class TweetsManager {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  /**
   * @var \DG\Twitter\Twitter
   */
  protected $twitter;

  /**
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * Constructs a TweetsManager object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   * @param \Drupal\Core\Messenger\Messenger $messenger
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerChannelFactory
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, ConfigFactoryInterface $configFactory, Messenger $messenger, LoggerChannelFactoryInterface $loggerChannelFactory) {
    $this->entityTypeManager = $entity_type_manager;
    $this->messenger = $messenger;
    $this->logger = $loggerChannelFactory->get('Tweets');
    $config = $configFactory->get('tweets.settings');
    try {
      $this->twitter = new Twitter($config->get('consumerKey'), $config->get('consumerSecret'), $config->get('accessToken'), $config->get('accessTokenSecret'));
    }
    catch (\Exception $e) {
      $this->logger->error(t('Twitter auth error : @message', ['@message' => $e->getMessage()]));
    }
    if (!$this->twitter->authenticate()) {
      $this->logger->error(t('Twitter authentification error'));
    }
  }

  /**
   * Method description.
   */
  public function refreshTweets() {
    try {
      $statuses = $this->twitter->load(Twitter::ME);
    }
    catch (\Exception $e) {
      $this->logger->error(t('Twitter refreshTweets error : @message', ['@message' => $e->getMessage()]));
    }
  }

}
