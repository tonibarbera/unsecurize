<?php

namespace Drupal\unsecurize\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a really unecure user private data block.
 *
 * @Block(
 *   id = "unsecurize_usersprivatedata",
 *   admin_label = @Translation("Users private data"),
 *   category = @Translation("Unsecurize")
 * )
 */
class UsersPrivateDataBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return \Drupal::formBuilder()->getForm('Drupal\unsecurize\Form\UserSearchForm');
  }

}
