<?php

namespace Drupal\unsecurize\Commands;

use Drush\Commands\DrushCommands;
use Symfony\Component\Console\Helper\Table;
use Drupal\unsecurize\Component\Trait\UserDatabaseFieldsTrait;

/**
 * New command for search users at drush.
 */
class ListUsersCommand extends DrushCommands {

  use UserDatabaseFieldsTrait;

  /**
   * Search user by name or email.
   *
   * @param string $search
   *   Is the string used to search by name or email.
   *
   * @command user:search
   * @aliases usearch
   * @usage user:search
   */
  public function searchUser($search) {
    $users = \Drupal::service('search_users');

    $table = new Table($this->output());

    $headers = [];
    foreach ($this->userFields() as $field) {
      $headers[] = $field->name;
    }
    $table->setHeaders($headers);

    $rows = [];
    foreach ($users->searchUser($search) as $user) {
      $row = [];
      foreach ($this->userFields() as $field) {
        $row[] = $this->fieldFormatter(
          $user->get($field->id)->value,
          $field->id
        );
      }

      $rows[] = $row;
    }

    $table->setRows($rows);
    $table->render();
  }

}
