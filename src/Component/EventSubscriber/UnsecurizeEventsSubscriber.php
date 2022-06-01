<?php

namespace Drupal\unsecurize\Component\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;
use Drupal\unsecurize\Component\EventDispatcher\Event\UserDataAccessEvent;

/**
 * Class EventsSubscriber for Unsecurize module.
 *
 * @package Drupal\unsecurize\EventSubscriber
 */
class UnsecurizeEventsSubscriber implements EventSubscriberInterface {

  /**
   * Logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Setting the logger.
   *
   * @param \Psr\Log\LoggerInterface $logger
   *   The module logger.
   */
  public function __construct(
    LoggerInterface $logger
    ) {
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    return [
      UserDataAccessEvent::ACCESS => 'userDataAccess',
    ];
  }

  /**
   * React to a private user data access.
   *
   * @param \Drupal\unsecurize\Component\EventDispatcher\Event\UserDataAccessEvent $event
   *   User access event.
   */
  public function userDataAccess(UserDataAccessEvent $event) {
    $this->logger->notice($event->users());
  }

}
