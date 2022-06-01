<?php

namespace Drupal\unsecurize\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\unsecurize\Component\Trait\SearchValidatorTrait;
use Drupal\unsecurize\Entity\Exception\ConfigEntityWrongValuesException;
use Drupal\unsecurize\Component\EventDispatcher\Event\UserDataAccessEvent;
use Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Drupal\unsecurize\Component\Trait\UserDatabaseFieldsTrait;

/**
 * Search and return Drupal users sensible data.
 */
class SearchUsersPrivateData {

  use SearchValidatorTrait;
  use UserDatabaseFieldsTrait;

  final const SETTINGS = 'unsecurize.settings';

  /**
   * Config Factory importer.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * The event dispatcher service.
   *
   * @var \Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher
   */
  protected $eventDispatcher;

  /**
   * Serach users with all private data service.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher $eventDispatcher
   *   The entity type manager.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    EntityTypeManager $entity_type_manager,
    ContainerAwareEventDispatcher $eventDispatcher
    ) {
    $this->configFactory = $config_factory;
    $this->entityTypeManager = $entity_type_manager;
    $this->eventDispatcher = $eventDispatcher;
  }

  /**
   * Search users in the database.
   *
   * @param string $search
   *   Search string.
   *
   * @return array
   *   Users array.
   */
  public function searchUser(string $search): array {

    if (!($this->nameConfigured() || ($this->mailConfigured()))) {
      throw new ConfigEntityWrongValuesException('Name and Email are not activated.');
    }

    if (!$this->validSearchText($search)) {
      return [];
    }

    $users = $this->mergeUserArrays(
      $this->nameConfigured() ? $this->searchUsersByName($search) : [],
      $this->mailConfigured() ? $this->searchUsersByMail($search) : [],
    );

    $event = new UserDataAccessEvent($users);
    $this->eventDispatcher->dispatch(UserDataAccessEvent::ACCESS, $event);

    return $users;
  }

  /**
   * Search from users by email.
   *
   * @param string $search
   *   The search text.
   *
   * @return array
   *   List of users.
   */
  private function searchUsersByMail(string $search): array {
    $searchResultRows = [];
    foreach ($this->users() as $user) {
      $userMail = strtolower($user->get($this->userFields()->mail->id)->value ?? '');

      if (strpos($userMail, strtolower($search) ?? '') !== FALSE) {
        $searchResultRows[] = $user;
      }
    }

    return $searchResultRows;
  }

  /**
   * Search from users by name.
   *
   * @param string $search
   *   The search text.
   *
   * @return array
   *   List of users.
   */
  private function searchUsersByName(string $search): array {
    $searchResultRows = [];
    foreach ($this->users() as $user) {
      $userMail = strtolower($user->get($this->userFields()->name->id)->value ?? '');

      if (strpos($userMail, strtolower($search) ?? '') !== FALSE) {
        $searchResultRows[] = $user;
      }

    }

    return $searchResultRows;
  }

  /**
   * Returns email config status.
   *
   * @return bool
   *   The config status.
   */
  private function mailConfigured(): bool {
    $config = (bool) $this->configFactory
      ->get(self::SETTINGS)
      ->get($this->userFields()->mail->id);

    return (bool) $config;
  }

  /**
   * Returns name config status.
   *
   * @return bool
   *   The config status.
   */
  private function nameConfigured(): bool {
    $config = (bool) $this->configFactory
      ->get(self::SETTINGS)
      ->get($this->userFields()->name->id);

    return (bool) $config;
  }

  /**
   * Return list of user objects.
   *
   * @return mixed
   *   Users.
   */
  private function users() {
    $userStorage = $this->entityTypeManager->getStorage('user');
    $uids = $userStorage->getQuery()->execute();

    return $userStorage->loadMultiple($uids);
  }

  /**
   * Merge 2 user objects array.
   *
   * @param array $usersList0
   *   Users.
   * @param array $usersList1
   *   Users.
   *
   * @return array
   *   Combined unique users array.
   */
  private function mergeUserArrays(array $usersList0, array $usersList1): array {
    $users = array_merge($usersList0, $usersList1);

    $takedUids = [];
    foreach ($users as $i => $user) {
      if (in_array($user->get($this->userFields()->uid->id)->value, $takedUids)) {
        unset($users[$i]);
      }
      else {
        $takedUids[] = $user->get($this->userFields()->uid->id)->value;
      }
    }

    return $users;
  }

}
