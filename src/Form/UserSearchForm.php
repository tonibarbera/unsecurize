<?php

namespace Drupal\unsecurize\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\unsecurize\Component\Trait\SearchValidatorTrait;

/**
 * Provides a Unsecurize form.
 */
class UserSearchForm extends FormBase {

  use SearchValidatorTrait;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'unsecurize_user_search';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['searchtext'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User name or email'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $searchtext = $form_state->getValue('searchtext');
    if (!$this->validSearchText($searchtext)) {
      $form_state->setErrorByName('message', $this->t('Invalid search string.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $url = Url::fromRoute('unsecurize.search', [
      'search' => $form_state->getValue('searchtext'),
    ]);

    $form_state->setRedirectUrl($url);
  }

}
