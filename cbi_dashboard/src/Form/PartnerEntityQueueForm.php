<?php

namespace Drupal\cbi_dashboard\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\hero\Entity\Hero;

/**
 * Class PartnerEntityQueueForm.
 *
 * @package Drupal\cbi_dashboard\Form
 */
class PartnerEntityQueueForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'partner_entity_queue_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['add_hero'] = array(
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Add Hero'),
      '#target_type' => 'hero',
      // '#selection_handler' => 'views',
      // '#selection_settings' => array(
      //   'view' => array(
      //     'view_name' => 'hero_references',
      //     'display_name' => 'front_page_hero',
      //   ),
      // ),
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
    $id = $form_state->getValue('add_hero');
    if($hero = Hero::load($id)) {
      $hero->field_partner_hero->value = 1;
      $hero->save();
    }
    return;
  }

}
