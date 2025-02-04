<?php

namespace Drupal\cache_extension\Cache\Context;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;
use Drupal\Core\Path\PathMatcherInterface;

/**
 * Defines a cache context for whether the URL is the front page of the site.
 *
 * Cache context ID: 'url.path.not_front'.
 *
 */
final class NotFrontPathCacheContext implements CacheContextInterface {

  public function __construct(protected PathMatcherInterface $path_matcher) {}

  /**
   * {@inheritdoc}
   */
  public static function getLabel() {
    return t('Is not front page');
  }

  /**
   * {@inheritdoc}
   */
  public function getContext(): string {
    return ($this->path_matcher->isFrontPage()) != 1?  '1' : '0';
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata(): CacheableMetadata {
    return new CacheableMetadata();
  }

}
