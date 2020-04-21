<?php

namespace Drupal\cbi_dashboard\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ads\Entity\Ad;

/**
 * Class AdQueueForm.
 *
 * @package Drupal\cbi_dashboard\Form
 */
class AdQueueForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ad_queue_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['add_ad'] = array(
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Add Ad'),
      '#target_type' => 'ad',
    );
    $form['add'] = array(
      '#type' => 'submit',
      '#title' => $this->t('Add'),
      '#value' => $this->t('Add'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $id = $form_state->getValue('add_ad');
    if($ad = Ad::load($id)) {
      $ad->field_front_page_ad->value = 1;
      $ad->save();
    }
    return;
  }

}
