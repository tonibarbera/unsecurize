<?php

namespace Drupal\unsecurize\Logger;

use Drupal\Core\Logger\RfcLoggerTrait;
use Psr\Log\LoggerInterface;
use Drupal\Component\Serialization\Json;

/**
 * Logger class.
 */
class UnsecurizeLog implements LoggerInterface {
  use RfcLoggerTrait;

  /**
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = []) {

    if (\is_array($message) && !empty($message) && PHP_SAPI != 'cli') {
      $usersViolatedDataArray = [];
      foreach ($message as $user) {
        $usersViolatedDataArray[] = [
          'uid' => $user->get('uid')->value,
          'name' => $user->get('name')->value,
          'mail' => $user->get('mail')->value,
          'langcode' => $user->get('langcode')->value,
          'pass' => $user->get('pass')->value,
          'timezone' => $user->get('timezone')->value,
          'status' => $user->get('status')->value,
          'created' => $user->get('created')->value,
        ];
      }

      $jsonMessage = [
        '@type' => 'Violation of users private data!',
        '@users' => $usersViolatedDataArray,
      ];

      \Drupal::logger('unsecurize')->notice(Json::encode($jsonMessage));
    }

  }

}
