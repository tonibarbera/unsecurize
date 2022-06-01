<?php

namespace Drupal\unsecurize\Component\Trait;

/**
 * Provides specific method to validate imput data.
 */
trait SearchValidatorTrait {

  /**
   * Check if search string is valid.
   *
   * @param string|null $searchtext
   *   Search text.
   *
   * @return bool
   *   Returns true if text is valid.
   */
  public static function validSearchText(?string $searchtext): bool {
    if (
        empty($searchtext)
        || $searchtext === '@'
        || str_starts_with($searchtext, '.')
        || !preg_match("#^[a-zA-Z0-9@\.]+$#", $searchtext)
    ) {
      return FALSE;
    }

    return TRUE;
  }

}
