<?php

namespace Drupal\cbi_dashboard\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides a 'DashboardAdQueueBlock' block.
 *
 * @Block(
 *  id = "dashboard_ad_entity_queue_block",
 *  admin_label = @Translation("Dashboard Ad Entity queue block"),
 * )
 */
class DashboardAdQueueBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
    $form = \Drupal::formBuilder()->getForm('Drupal\cbi_dashboard\Form\AdQueueForm');

    // Create an empty config array.
    $config = [];

    // Set the block id.
    // views_block__slideshow_block_1_2
    // views_block:content_views-site_map_block
    $block_id = 'views_block:ads-dashboard_queue';

    // Create an instance of the block.
    $block = $this->blockManager->createInstance($block_id, $config)->build();
    $output = array(
      '#type' => 'container',
      '#attached' => array(
        'library' => array(
          'cbi_dashboard/global',

        ),
      ),
      '#cache' => array(
        'tags' => array(
          'ad_list',
        ),
      ),
      'wrapper' => array(
        '#type' => 'container',
        'add_ad' => array (
          '#type' => 'markup',
          '#markup' => '<ul class="action-links"><li><a href="/admin/structure/ad/add" class="button button-action button--primary button--small" data-drupal-link-system-path="admin/structure/ad/add">Add Ad</a></li></ul>',
        ),
      ),
    );

    $view = $block['#view'];
    $results = $view->total_rows;

    if($results == 0) {
      $output['wrapper']['form_wrapper'] = array (
        'form' => $form,
      );
    } else {
      $output['wrapper']['view_wrapper'] = array (
        'block' => $block,
      );
    }

    return $output;
  }

}
