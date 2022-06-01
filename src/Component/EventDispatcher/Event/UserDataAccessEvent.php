<?php

namespace Drupal\unsecurize\Component\EventDispatcher\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event User Private Data has been accesed.
 */
class UserDataAccessEvent extends Event {

  const ACCESS = 'event.access';

  /**
   * List of the users has been accesed.
   *
   * @var array
   */
  protected $usersList;

  /**
   * Gest users array.
   *
   * @param array $usersList
   *   List of the users has been accesed.
   */
  public function __construct(array $usersList) {
    $this->usersList = $usersList;
  }

  /**
   * Return the users array.
   *
   * @return array
   *   List of the users has been accesed.
   */
  public function users() {
    return $this->usersList;
  }

  /**
   * Returns class description.
   */
  public function userDataAccessEventDescription() {
    return "Event that alerts about private data accesed.";
  }

}
