<?php

namespace Drupal\tweets\Command;

use Drupal\tweets\TweetsManager;
use Drush\Commands\DrushCommands;

class TweetsCommands extends DrushCommands {

  protected $tweetsManager;

  public function __construct(TweetsManager $tweetsManager) {
    parent::__construct();
    $this->tweetsManager = $tweetsManager;
  }

  /**
   * Refresh tweets.
   *
   * @command tweets:refresh
   */
  public function refreshTweets() {
    $this->tweetsManager->refreshTweets();
  }

}
