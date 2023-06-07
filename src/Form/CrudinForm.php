<?php

namespace Drupal\crudin\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Messenger;
use Drupal\Core\Link;


class CrudinForm extends FormBase {

  protected int $recordId;

  public function getFormId() {
    return "crudin_form";
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $conn = Database::getConnection();
    $record = [];
    if ($id) {
      $query = $conn->select('crudin', 'm')
        ->condition('id', $id)
        ->fields('m');
      $record = $query->execute()->fetchAssoc();

      $this->recordId = $id;
    }

    $form['name'] = [
      '#default_value' => (isset($record['name']) && $this->recordId) ? $record['name'] : '',
      '#required' => TRUE,
      '#title' => t('Name'),
      '#type' => 'textfield',
    ];

    $form['age'] = [
      '#type' => 'textfield',
      '#title' => t('Age'),
      '#required' => TRUE,
      '#default_value' => (isset($record['age']) && $this->recordId) ? $record['age'] : '',
    ];


    $form['action'] = ['#type' => 'action',];

    $form['action']['submit'] = ['#type' => 'submit', '#value' => t('Save'),];

    $form['action']['reset'] = [
      '#type' => 'button',
      '#value' => t('Reset'),
      '#attributes' => ['onclick' => 'this.form.reset(); return false;'],
    ];

    $link = Url::fromUserInput('/crudin');

    $form['action']['cancel'] = [
      '#markup' => Link::fromTextAndUrl(t('Back to page'), $link, ['attributes' => ['class' => 'button']])
        ->toString(),
    ];
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

    $name = $form_state->getValue('name');

    if (preg_match('/[^A-Za-z]/', $name)) {
      $form_state->setErrorByName('name', $this->t('Name must be in Characters Only'));
    }


    $age = $form_state->getValue('age');

    if (!preg_match('/[^A-Za-z]/', $age)) {
      $form_state->setErrorByName('age', $this->t('Age must be in Numbers Only'));
    }

    parent::validateForm($form, $form_state);

  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $field = $form_state->getValues();

    $name = $field['name'];
    $age = $field['age'];

    if ($this->recordId) {
      $field = ['name' => $name, 'age' => $age,];

      $query = \Drupal::database();
      $query->update('crudin')
        ->fields($field)
        ->condition('id', $this->recordId)
        ->execute();
      $this->messenger()->addMessage('Sucessfully Update Record');
    }
    else {
      $field = ['name' => $name, 'age' => $age,];
      $query = \Drupal::database();
      $query->insert('crudin')->fields($field)->execute();
      $this->messenger()->addMessage('Sucessfully Saved Record');

      $form_state->setRedirect('crudin.crudin_controller_listing');
    }

  }

}




















