<?php

namespace Drupal\tweets;

use DG\Twitter\Twitter;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Messenger\Messenger;
use Drupal\tweets\Entity\Tweet;

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
    $consumerKey = $config->get('consumerKey');
    $consumerSecret = $config->get('consumerSecret');
    $accessToken = $config->get('accessToken');
    $accessTokenSecret = $config->get('accessTokenSecret');
    if (isset($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret)) {
      try {
        $this->twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
      }
      catch (\Exception $e) {
        $this->logger->error(t('Twitter auth error : @message', ['@message' => $e->getMessage()]));
      }
      if (!$this->twitter->authenticate()) {
        $this->logger->error(t('Twitter authentification error'));
      }
    }
    else {
      $this->logger->notice(t('Twitter credentials are not setted'));
    }
  }

  /**
   * Method description.
   */
  public function refreshTweets() {
    try {
      $statuses = $this->twitter->load(Twitter::ME);
      $updated = 0;
      $created = 0;
      foreach ($statuses as $status) {
        $date = new \DateTime($status->created_at, new \DateTimeZone('GMT'));
        if ($tweet = Tweet::loadByTwitterId($status->id)) {
          $tweet->set('favorite_count', $status->favorite_count);
          $tweet->set('retweet_count', $status->retweet_count);
          $tweet->set('author', $status->user->screen_name);
          $tweet->set('author_name', $status->user->name);
          $tweet->set('created', $date->format('U'));
          $updated++;
        }
        else {
          $tweet = Tweet::create([
            'id_twitter' => $status->id,
            'tweet' => $status->text,
            'favorite_count' => $status->favorite_count,
            'retweet_count' => $status->retweet_count,
            'author' => $status->user->screen_name,
            'author_name' => $status->user->author_name,
            'created' => $date->format('U'),
          ]);
          $created++;
        }
        $tweet->save();
      }
      $this->logger->info(t('@created Tweets created and @updated updated', ['@created' => $created, '@updated' => $updated]));
    }
    catch (\Exception $e) {
      $this->logger->error(t('Twitter refreshTweets error : @message', ['@message' => $e->getMessage()]));
    }
  }

}
