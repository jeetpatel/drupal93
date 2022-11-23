<?php
namespace Drupal\custom_migrate\Services;

use Drupal\Core\Password\PhpassHashedPassword;
use Drupal\Core\Password\PasswordInterface;

/**
 * Class CustomMigratePasswordService
 *
 * @package Drupal\custom_migrate\Services\CustomMigratePasswordService
 */
class CustomMigratePasswordService extends PhpassHashedPassword implements PasswordInterface {

  const _SHA512_SALT = 'CRM';
  const _SHA512_HASH_KEY = 'asdFghj123';

  /**
   * Custom password hash Check.
   *
   * @param string $password
   *   Plain Password string.
   * @param string $hash
   *   Database Hash string.
   *
   * @return bool
   *   Return TRUE|FALSE.
   */
  public function check($password, $hash) {
    \Drupal::logger('password_service')->info("Password: $password, Hash: $hash");
    if (substr($hash, 0, 7) == '$ROT13$') {
      \Drupal::logger('password_service')->info('ROT13 Algorithm');
      // Remove the prefix so that we can compare the hash without it.
      $stored_hash = substr($hash, 7);
      // Compute the hash with the same algorithm as the legacy system did.
      return str_rot13($password) == $stored_hash;
    }
    elseif (substr($hash, 0, 8) == '$SHA512$') {
      \Drupal::logger('password_service')->info('SHA512 Algorithm');
      // Remove the prefix so that we can compare the hash without it.
      $stored_hash = substr($hash, 8);
      // Compute the hash with the same algorithm as the legacy system did.
      return $this->calculateSha512($password) == $stored_hash;
    }
    return parent::check($password, $hash);
  }

  /**
   * Calculate SHA512 hash.
   *
   * @param string $password
   *   Plain password.
   *
   * @return string
   *   Computed password.
   */
  private function calculateSha512($password) {
    return hash('sha512', self::_SHA512_SALT . $password . self::_SHA512_HASH_KEY . self::_SHA512_SALT);
  }
  
}
