<?php

namespace Drupal\learning\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'TestBlock' block.
 *
 * @Block(
 *  id = "test_block",
 *  admin_label = @Translation("Test block"),
 * )
 */
class TestBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    global $config;
    $site = $config['system.site']['name'];
    $site_name = \Drupal::config('system.site')->get('name');
    // Get the site name without overrides.
    $site_name2 = \Drupal::config('system.site')->getOriginal('name', FALSE);
    // Note that mutable config is always override free.
    $site_name3 = \Drupal::configFactory()->getEditable('system.site')->getOriginal('name', FALSE);

    $build = [];
    $build['#theme'] = 'test_block';
    $build['test_block']['#markup'] = $this->t('Site name:@site, Without override:@site2, Mutable:@site3', [
      '@site' => $site_name,
      '@site2' => $site_name2,
      '@site3' => $site_name3,
    ]);
    return $build;
  }

  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), ['node_list:page']);
  }

  public function access2(AccountInterface $account, $return_as_object = FALSE) {
    return AccessResult::allowedIf($account->isAuthenticated());
  }

}
