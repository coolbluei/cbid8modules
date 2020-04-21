<?php

namespace Drupal\cbi_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\hero\Entity\Hero;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class HeroController.
 *
 * @package Drupal\cbi_dashboard\Controller
 */
class HeroController extends ControllerBase {

  /**
   * Removeitemfront.
   *
   * @return NULL
   */
  public function removeItemFront($id) {
    if($hero = Hero::load($id)) {
      $hero->field_front_page_hero->value = 0;
      $hero->save();
    }

    return new RedirectResponse(\Drupal::url('cbi_dashboard.dashboard'));
  }

  /**
   * Removeitempartner.
   *
   * @return NULL
   */
  public function removeItemPartner($id) {
    if($hero = Hero::load($id)) {
      $hero->field_partner_hero->value = 0;
      $hero->save();
    }

    return new RedirectResponse(\Drupal::url('cbi_dashboard.dashboard'));
  }

}
