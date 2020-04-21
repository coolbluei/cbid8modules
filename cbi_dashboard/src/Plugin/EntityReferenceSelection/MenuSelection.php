<?php

namespace Drupal\cbi_dashboard\Plugin\EntityReferenceSelection;

use Drupal\Core\Entity\EntityReferenceSelection\SelectionPluginBase;
use Drupal\Core\Entity\EntityReferenceSelection\SelectionTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\views\Views;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'selection' entity_reference.
 *
 * @EntityReferenceSelection(
 *   id = "menu",
 *   label = @Translation("Menu Selection"),
 *   group = "menu",
 *   weight = 0
 * )
 */
class MenuSelection extends SelectionPluginBase implements ContainerFactoryPluginInterface {

  use SelectionTrait;

  /**
   * The loaded View object.
   *
   * @var \Drupal\views\ViewExecutable
   */
  protected $view;

  protected $entityTypeManager;

  /**
   * Constructs a new selection object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'menu' => [],
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $menus = $this->entityTypeManager->getStorage('menu')->loadMultiple();

    foreach($menus as $menu) {
      $options[$menu->id()] = $menu->label();
    }

    $settings = $this->getConfiguration()['menu'];
    $default = !empty($settings['exclude']) ? $settings['exclude'] : NULL;

    if($options) {
      $form['menu']['exclude'] = [
        '#type' => 'checkboxes',
        '#title' => $this->t('Menus to exclude'),
        '#required' => TRUE,
        '#options' => $options,
        '#default_value' => $default,
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getReferenceableEntities($match = NULL, $match_operator = 'CONTAINS', $limit = 0) {

    $menus = $this->entityTypeManager->getStorage('menu')->loadMultiple();

    $excluded = $this->getConfiguration()['menu']['exclude'];

    $return = [];

    foreach($menus as $id => $menu) {
      if(empty($excluded[$id])) {
        $return['menu'][$id] = $menu->label();
      }
    }

    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function countReferenceableEntities($match = NULL, $match_operator = 'CONTAINS') {
    $refs = $this->getReferenceableEntities($match, $match_operator);
    return count($refs);
  }

  /**
   * {@inheritdoc}
   */
  public function validateReferenceableEntities(array $ids) {
    $menus = $this->entityTypeManager->getStorage('menu')->loadMultiple();

    $excluded = $this->getConfiguration()['menu']['exclude'];

    $refs = [];

    foreach($menus as $id => $menu) {
      if(empty($excluded[$id])) {
        $refs[$id] = $menu->label();
      }
    }

    $result = [];
    foreach($ids as $id) {
      if(array_key_exists($id, $refs)) {
        $result[] = $id;
      }
    }

    return $result;
  }

}
