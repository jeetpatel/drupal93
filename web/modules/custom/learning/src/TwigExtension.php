<?php

namespace Drupal\learning;

use Drupal\block\Entity\Block;

/**
 * Class DefaultService.
 *
 * @package Drupal\alter
 */
class TwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('display_block', [$this, 'displayBlock'], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('is_login', [$this, 'isLogin']),
      new \Twig_SimpleFunction('PrintSearchForm', [$this, 'getSearchForm']),
      new \Twig_SimpleFunction('getYears', [$this, 'getYears']),
    ];
  }

  /**
   * Display block in twig.
   *
   * @param string $block_id
   *   Block id.
   *
   * @return string
   *   Return block as string.
   */
  public function displayBlock($block_id) {
    $block = Block::load($block_id);
    if (!empty($block)) {
      return \Drupal::entityTypeManager()->getViewBuilder('block')->view($block);
    }
  }

  /**
   * Check user is login or not.
   *
   * @return bool
   *   Return TRUE or FALSE.
   */
  public function isLogin() {
    return \Drupal::currentUser()->isAuthenticated();
  }

  /**
   * Get search form block.
   *
   * @return object
   *   Return search form block object.
   */
  public function getSearchForm() {
    $block = Block::load('tech_search');
    if (!empty($block)) {
      return \Drupal::entityTypeManager()->getViewBuilder('block')->view($block);
    }
  }

  /**
   * Get years array starting form 2017.
   *
   * @return array
   *   Years array.
   */
  public function getYears() {
    $years = [];
    $start_year = 2017;
    $years[] = $start_year;
    $current_year = (new \DateTime)->format("Y");
    $count = 0;
    while ($start_year < $current_year) {
      $start_year++;
      $years[] = $start_year;
    }
    return $years;
  }

}
