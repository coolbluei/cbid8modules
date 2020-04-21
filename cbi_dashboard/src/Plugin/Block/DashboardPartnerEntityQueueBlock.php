<?php

namespace Drupal\cbi_dashboard\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides a 'DashboardPartnerEntityQueueBlock' block.
 *
 * @Block(
 *  id = "dashboard_partner_entity_queue_block",
 *  admin_label = @Translation("Dashboard Partner Entity queue block"),
 * )
 */
class DashboardPartnerEntityQueueBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $blockManager;

  /**
   * {@inheritdoc}
   *
   * Load services into properties.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, BlockManager $block_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->blockManager = $block_manager;
  }

  /**
   * {@inheritdoc}
   *
   * Return services from the container to the __construct method.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.block')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\cbi_dashboard\Form\PartnerEntityQueueForm');

    // Create an empty config array.
    $config = [];

    // Set the block id.
    // views_block__slideshow_block_1_2
    // views_block:content_views-site_map_block
    $block_id = 'views_block:partner_hero-partner_hero_queue_block';

    // Create an instance of the block.
    $block = $this->blockManager->createInstance($block_id, $config)->build();
    $output = array(
      '#type' => 'container',
      '#attached' => array(
        'library' => array(
          'cbi_dashboard/global',

        ),
      ),
      'wrapper' => array(
        '#type' => 'container',
        'add_hero' => array (
          '#type' => 'markup',
          '#markup' => '<ul class="action-links"><li><a href="/admin/content/hero/add" class="button button-action button--primary button--small" data-drupal-link-system-path="admin/content/hero/add">Add Hero</a></li></ul>',
        ),
        'form_wrapper' => array (
          'form' => $form,
        ),
        'view_wrapper' => array (
          'block' => $block,
        ),
      ),
    );

    return $output;
  }

}
