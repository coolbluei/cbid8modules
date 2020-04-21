<?php

namespace Drupal\cbi_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ads\Entity\Ad;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class AdController.
 *
 * @package Drupal\cbi_dashboard\Controller
 */
class AdController extends ControllerBase {

  /**
   * Removeitemfront.
   *
   * @return NULL
   */
  public function removeItemFront($id) {
    if($ad = Ad::load($id)) {
      $ad->field_front_page_ad->value = 0;
      $ad->save();
    }
  
    return new RedirectResponse(\Drupal::url('cbi_dashboard.dashboard'));
  }

}
