<?php

namespace Drupal\unsecurize\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\unsecurize\Component\Trait\UserDatabaseFieldsTrait;

/**
 * Returns responses for Unsecurize routes.
 */
class UnsecurizeController extends ControllerBase {

  use UserDatabaseFieldsTrait;

  final const SETTINGS = 'unsecurize.settings';

  /**
   * Returns a table with all users data.
   *
   * @param string $search
   *   Search string.
   */
  public function search(string $search) {

    $build['table'] = [
      '#type' => 'table',
      '#header' => $this->tableHeaders(),
      '#rows' => $this->tableRows($search),
      '#empty' => t('No users has been found.'),
    ];
    return [
      '#type' => '#markup',
      '#markup' => render($build),
    ];

  }

  /**
   * Generate table headers from config.
   */
  private function tableHeaders() {

    foreach ($this->userFields() as $field) {
      $headersList[$field->id] = $field->name;
    }

    $header = [];
    foreach ($headersList as $config_key => $header_item) {
      if ($this->config(self::SETTINGS)->get($config_key)) {
        $header[$config_key] = $header_item;
      }
    }

    return $header;
  }

  /**
   * Generates table content rows.
   *
   * @param string $search
   *   The user seach string.
   */
  private function tableRows(string $search) {
    $users = \Drupal::service('search_users')->searchUser($search);

    $rows = [];
    foreach ($users as $user) {
      $row = [];
      foreach ($this->userFields() as $field) {
        if ($this->config(self::SETTINGS)->get($field->id)) {
          $row[] = $this->fieldFormatter(
            $user->get($field->id)->value,
            $field->id
          );
        }
      }

      $rows[] = $row;
    }

    return $rows;
  }

}
