<?php

namespace Drupal\unsecurize\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\unsecurize\Component\Trait\UserDatabaseFieldsTrait;

/**
 * Configure Unsecurize settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  use UserDatabaseFieldsTrait;

  final const SETTINGS = 'unsecurize.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'unsecurize_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [self::SETTINGS];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    foreach ($this->userFields() as $field) {

      $form[$field->id] = [
        '#type' => 'checkbox',
        '#title' => $field->name,
        '#default_value' => $this->config(self::SETTINGS)->get($field->id),
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (
        $form_state->getValue($this->userFields()->name->id) === 0
        && $form_state->getValue($this->userFields()->email->id) === 0
      ) {
      $form_state->setErrorByName('example', $this->t('You must check Mail and/or Name.'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    foreach ($this->userFields() as $field) {
      $this->config(self::SETTINGS)
        ->set($field->id, $form_state->getValue($field->id));
    }

    $this->config(self::SETTINGS)->save();

    parent::submitForm($form, $form_state);
  }

}
