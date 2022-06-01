<?php

namespace Drupal\unsecurize\Component\Trait;

/**
 * Provides the list of User table fields.
 */
trait UserDatabaseFieldsTrait {

  /**
   * List of the User table fields.
   */
  protected static function userFields(): object {

    $fields = [
      'uid' => (object) [
        'id' => 'uid',
        'name' => t('UID'),
      ],
      'name' => (object) [
        'id' => 'name',
        'name' => t('Name'),
      ],
      'mail' => (object) [
        'id' => 'mail',
        'name' => t('Email'),
      ],
      'langcode' => (object) [
        'id' => 'langcode',
        'name' => t('Lang code'),
      ],
      'pass' => (object) [
        'id' => 'pass',
        'name' => t('Password'),
      ],
      'timezone' => (object) [
        'id' => 'timezone',
        'name' => t('Timezone'),
      ],
      'status' => (object) [
        'id' => 'status',
        'name' => t('Status'),
      ],
      'created' => (object) [
        'id' => 'created',
        'name' => t('Created'),
      ],
    ];

    return (object) $fields;
  }

  /**
   * Fields output formatter.
   */
  protected function fieldFormatter(?string $value, string $field): string {
    return match($field) {
      'status' => $value ? t('Active') : t('Blocked'),
      'created' => date('d/M/Y', $value),
      default => (string) $value,
    };
  }

}
