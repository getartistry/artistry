<?php

/*
Plugin Name: Cart2Cart: Password Migration
Plugin URI: http://www.shopping-cart-migration.com/?utm_source=wp-admin&utm_medium=magneticone&utm_campaign=cart2cart-password-migration-plugin
Description: Let your customers successfully log in to their accounts after shopping cart migration with Cart2Cart.
Version: 1.1
Author: MagneticOne
Author URI: http://www.shopping-cart-migration.com/?utm_source=wp-admin&utm_medium=visit-plugin-site&utm_campaign=cart2cart-password-migration-plugin
*/

define('C2C_USER_META_KEY', 'c2c_login_data');
define('itoa64', './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');

add_filter('check_password', 'c2c_password_migration_filter', 10, 4);

function c2c_password_migration_filter($check, $password, $hash, $user_id)
{
  if ($check) {
    return $check;
  }

  $loginData = get_user_meta($user_id, C2C_USER_META_KEY, true);

  if ($loginData != '') {

    switch($loginData['cartId']){
      case 'Magento':
        $hashArr = explode(':', $loginData['hash']);
        switch (count($hashArr)) {
          case 1:
            $check = md5($password) === $loginData['hash'];
          break;
          case 2:
          case 3:
            $check = (md5($hashArr[1] . $password) === $hashArr[0]
              || md5($hashArr[1] . $password) . ':' . $hashArr[1] === $hashArr[0]
              || hash('sha256', $hashArr[1] . $password) === $hashArr[0]
              || hash('sha256', $hashArr[1] . $password) . ':' . $hashArr[1] === $hashArr[0]
            );
          break;
        }
      break;
      case 'PrestaShop':
        $check = (md5($loginData['key'] . $password) === $loginData['hash']) || (function_exists('password_verify') && password_verify($password, $loginData['hash']));
      break;
      case 'Opencart':
        $check = sha1($loginData['salt'] . sha1($loginData['salt'] . sha1($password))) === $loginData['hash'];
      break;
      case 'Cscart':
        $check = ((md5($password) == $loginData['hash']) || (md5(md5($password) . md5($loginData['salt'])) == $loginData['hash']));
      break;
      case 'Oscommerce':
      case 'Oscommerce22ms2':
      case 'Oscmax':
      case 'Oscmax2':
      case 'Creloaded':
      case 'LoadedCommerce':
      case 'Modified':
      case 'Xtcommerce':
        $check = (md5($loginData['salt'] . $password) === $loginData['hash']
          || checkPassword($password, $loginData['hash']));
      break;
      case 'Zencart137':
        if (!($check = (md5($loginData['salt'] . $password) === $loginData['hash'] || checkPassword($password, $loginData['hash'])))) {
          $hasher = new Cart2cart_Login_Auth_Zencart();
          $check = $hasher->validatePassword($loginData['hash'], $password, $loginData['salt']);
        }
      break;
      case 'Virtuemart':
        $encrypted = ($loginData['salt']) ? md5($password.$loginData['salt']) : md5($password);
        if ($loginData['hash'] == $encrypted) {
          $check = true;
        } elseif (strpos($hash, '$P$') === 0) {
          $phpass = new Cart2cart_Login_Libs_PasswordHash(10, true);
          $check = $phpass->CheckPassword($password, $hash);
        } elseif ($hash[0] == '$') {
          $check = vm_passwordVerify($password, $hash);
        } elseif (substr($hash, 0, 8) == '{SHA256}') {
          $parts     = explode(':', $hash);
          $crypt     = $parts[0];
          $salt      = @$parts[1];

          $encrypted = ($salt) ? hash('sha256', $password . $salt) . ':' . $salt : hash('sha256', $password);
          $testcrypt = '{SHA256}' . $encrypted;

          $check = vm_timingSafeCompare($hash, $testcrypt);
        } else {
          $parts = explode(':', $hash);
          $crypt = $parts[0];
          $salt  = @$parts[1];

          $testcrypt = md5($password . $salt) . ($salt ? ':' . $salt : (strpos($hash, ':') !== false ? ':' : ''));

          $check = vm_timingSafeCompare($hash, $testcrypt);
        }
      break;
    }

    if ( $check && $user_id ) {
      // Rehash using new hash.
      wp_set_password($password, $user_id);
      delete_user_meta($user_id, C2C_USER_META_KEY);
    }
  }

  return $check;
}

/**
 * @param $input
 * @param $count
 * @return string
 */
function encode64($input, $count)
{
  $output = '';
  $i = 0;
  $itoa64 = str_split(itoa64);
  do {
    $value = ord($input[$i++]);
    $output .= $itoa64[$value & 0x3f];
    if ($i < $count)
      $value |= ord($input[$i]) << 8;
    $output .= $itoa64[($value >> 6) & 0x3f];
    if ($i++ >= $count)
      break;
    if ($i < $count)
      $value |= ord($input[$i]) << 16;
    $output .= $itoa64[($value >> 12) & 0x3f];
    if ($i++ >= $count)
      break;
    $output .= $itoa64[($value >> 18) & 0x3f];
  } while ($i < $count);

  return $output;
}

/**
 * @param $password
 * @param $setting
 * @return string
 */
function crypt_private($password, $setting)
{
  $settingArr = str_split($setting);
  $output = '*0';
  if (substr($setting, 0, 2) == $output)
    $output = '*1';

  $id = substr($setting, 0, 3);

  if ($id != '$P$' && $id != '$H$')
    return $output;

  $countLog2 = strpos(itoa64, $settingArr[3]);
  if ($countLog2 < 7 || $countLog2 > 30)
    return $output;

  $count = 1 << $countLog2;

  $salt = substr($setting, 4, 8);
  if (strlen($salt) != 8)
    return $output;

  if (PHP_VERSION >= '5') {
    $hash = md5($salt . $password, TRUE);
    do {
      $hash = md5($hash . $password, TRUE);
    } while (--$count);
  } else {
    $hash = pack('H*', md5($salt . $password));
    do {
      $hash = pack('H*', md5($hash . $password));
    } while (--$count);
  }

  $output = substr($setting, 0, 12);
  $output .= encode64($hash, 16);

  return $output;
}

/**
 * @param $password
 * @param $stored_hash
 * @return bool
 */
function CheckPassword($password, $stored_hash)
{
  $hash = crypt_private($password, $stored_hash);
  if ($hash[0] == '*')
    $hash = crypt($password, $stored_hash);

  return $hash == $stored_hash;
}

function vm_passwordVerify($password, $hash)
{
  if (!function_exists('crypt')) {
    return false;
  }

  $ret = crypt($password, $hash);
  if (!is_string($ret) || vm_strlen($ret) != vm_strlen($hash) || vm_strlen($ret) <= 13) {
    return false;
  }

  $status = 0;
  for ($i = 0; $i < vm_strlen($ret); $i++) {
    $status |= (ord($ret[$i]) ^ ord($hash[$i]));
  }

  return $status === 0;
}

function vm_strlen($string) {
  if (function_exists('mb_strlen')) {
    return mb_strlen($string, '8bit');
  }
  return strlen($string);
}

function vm_timingSafeCompare($known, $unknown)
{
  $known .= chr(0);
  $unknown .= chr(0);

  $knownLength = strlen($known);
  $unknownLength = strlen($unknown);

  $result = $knownLength - $unknownLength;

  for ($i = 0; $i < $unknownLength; $i++) {
    $result |= (ord($known[$i % $knownLength]) ^ ord($unknown[$i]));
  }

  return $result === 0;
}

class Cart2cart_Login_Libs_PasswordHash
{
  var $itoa64;
  var $iterationCountLog2;
  var $portableHashes;
  var $randomState;

  function __construct($iterationCountLog2, $portableHashes)
  {
    $this->itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    if ($iterationCountLog2 < 4 || $iterationCountLog2 > 31)
      $iterationCountLog2 = 8;
    $this->iterationCountLog2 = $iterationCountLog2;

    $this->$portableHashes = $portableHashes;

    $this->randomState = microtime();
    if (function_exists('getmypid'))
      $this->randomState .= getmypid();
  }

  function get_random_bytes($count)
  {
    $output = '';
    if (is_readable('/dev/urandom') &&
      ($fh = @fopen('/dev/urandom', 'rb'))) {
      $output = fread($fh, $count);
      fclose($fh);
    }

    if (strlen($output) < $count) {
      $output = '';
      for ($i = 0; $i < $count; $i += 16) {
        $this->randomState =
          md5(microtime() . $this->randomState);
        $output .=
          pack('H*', md5($this->randomState));
      }
      $output = substr($output, 0, $count);
    }

    return $output;
  }

  function encode64($input, $count)
  {
    $output = '';
    $i = 0;
    do {
      $value = ord($input[$i++]);
      $output .= $this->itoa64[$value & 0x3f];
      if ($i < $count)
        $value |= ord($input[$i]) << 8;
      $output .= $this->itoa64[($value >> 6) & 0x3f];
      if ($i++ >= $count)
        break;
      if ($i < $count)
        $value |= ord($input[$i]) << 16;
      $output .= $this->itoa64[($value >> 12) & 0x3f];
      if ($i++ >= $count)
        break;
      $output .= $this->itoa64[($value >> 18) & 0x3f];
    } while ($i < $count);

    return $output;
  }

  function gensalt_private($input)
  {
    $output = '$P$';
    $output .= $this->itoa64[min($this->iterationCountLog2 +
      ((PHP_VERSION >= '5') ? 5 : 3), 30)];
    $output .= $this->encode64($input, 6);

    return $output;
  }

  function crypt_private($password, $setting)
  {
    $output = '*0';
    if (substr($setting, 0, 2) == $output)
      $output = '*1';

    $id = substr($setting, 0, 3);

    if ($id != '$P$' && $id != '$H$')
      return $output;

    $countLog2 = strpos($this->itoa64, $setting[3]);
    if ($countLog2 < 7 || $countLog2 > 30)
      return $output;

    $count = 1 << $countLog2;

    $salt = substr($setting, 4, 8);
    if (strlen($salt) != 8)
      return $output;

    if (PHP_VERSION >= '5') {
      $hash = md5($salt . $password, TRUE);
      do {
        $hash = md5($hash . $password, TRUE);
      } while (--$count);
    } else {
      $hash = pack('H*', md5($salt . $password));
      do {
        $hash = pack('H*', md5($hash . $password));
      } while (--$count);
    }

    $output = substr($setting, 0, 12);
    $output .= $this->encode64($hash, 16);

    return $output;
  }

  function gensalt_extended($input)
  {
    $countLog2 = min($this->iterationCountLog2 + 8, 24);

    $count = (1 << $countLog2) - 1;

    $output = '_';
    $output .= $this->itoa64[$count & 0x3f];
    $output .= $this->itoa64[($count >> 6) & 0x3f];
    $output .= $this->itoa64[($count >> 12) & 0x3f];
    $output .= $this->itoa64[($count >> 18) & 0x3f];

    $output .= $this->encode64($input, 3);

    return $output;
  }

  function gensalt_blowfish($input)
  {
    $itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    $output = '$2a$';
    $output .= chr(ord('0') + $this->iterationCountLog2 / 10);
    $output .= chr(ord('0') + $this->iterationCountLog2 % 10);
    $output .= '$';

    $i = 0;
    do {
      $c1 = ord($input[$i++]);
      $output .= $itoa64[$c1 >> 2];
      $c1 = ($c1 & 0x03) << 4;
      if ($i >= 16) {
        $output .= $itoa64[$c1];
        break;
      }

      $c2 = ord($input[$i++]);
      $c1 |= $c2 >> 4;
      $output .= $itoa64[$c1];
      $c1 = ($c2 & 0x0f) << 2;

      $c2 = ord($input[$i++]);
      $c1 |= $c2 >> 6;
      $output .= $itoa64[$c1];
      $output .= $itoa64[$c2 & 0x3f];
    } while (1);

    return $output;
  }

  function HashPassword($password)
  {
    $random = '';

    if (CRYPT_BLOWFISH == 1 && !$this->$portableHashes) {
      $random = $this->get_random_bytes(16);
      $hash =
        crypt($password, $this->gensalt_blowfish($random));
      if (strlen($hash) == 60)
        return $hash;
    }

    if (CRYPT_EXT_DES == 1 && !$this->$portableHashes) {
      if (strlen($random) < 3)
        $random = $this->get_random_bytes(3);
      $hash =
        crypt($password, $this->gensalt_extended($random));
      if (strlen($hash) == 20)
        return $hash;
    }

    if (strlen($random) < 6)
      $random = $this->get_random_bytes(6);
    $hash =
      $this->crypt_private($password,
        $this->gensalt_private($random));
    if (strlen($hash) == 34)
      return $hash;

    return '*';
  }

  function CheckPassword($password, $stored_hash)
  {
    $hash = $this->crypt_private($password, $stored_hash);
    if ($hash[0] == '*')
      $hash = crypt($password, $stored_hash);

    return $hash == $stored_hash;
  }
}

class Cart2cart_Login_Auth_Zencart
{
  protected static $instance = null;

  public function validatePassword($hash, $password, $salt)
  {
    $type = $this->detectPasswordType($hash . ':'. $salt);
    if ($type != 'unknown') {
      $method = 'validatePassword' . ucfirst($type);
      return $this->{$method}($password, $hash . ':'. $salt);
    }

    $result = password_verify($password, $hash);
    return $result;
  }

  public function __construct($phpVersion = PHP_VERSION)
  {
    if (version_compare($phpVersion, '5.3.7', '<')) {
      define('PASSWORD_BCRYPT', 1);
      define('PASSWORD_DEFAULT', PASSWORD_BCRYPT);

      if (! function_exists('password_hash')) {
        function password_hash($plain, $algo = null)
        {
          $password = '';
          for($i = 0; $i < 40; $i ++) {
            $password .= $this->zen_rand();
          }
          $salt = hash('sha256', $password);
          $password = hash('sha256', $salt . $plain) . ':' . $salt;

          return $password;
        }
      }

      if (! function_exists('password_verify')) {
        function password_verify($plain, $encrypted)
        {
          if ($this->zen_not_null($plain) && $this->zen_not_null($encrypted)) {
            $stack = explode(':', $encrypted);
            if (sizeof($stack) != 2)
              return false;
            if ($this->validatePasswordOldMd5($plain, $encrypted) === true) {
              return true;
            } elseif ($this->validatePasswordCompatSha256($plain, $encrypted) === true) {
              return true;
            }
          }
          return false;
        }
      }
      if (! function_exists('password_needs_rehash')) {
        function password_needs_rehash($hash, $algo = null)
        {
          $tmp = explode(':', $hash);
          if (count($tmp) == 2 && strlen($tmp [1]) == 2) {
            return true;
          } else {
            return false;
          }
        }
      }
    } elseif (version_compare($phpVersion, '5.5.0', '<')) {
      function password_hash($password, $algo, array $options = array()) {
        if (!function_exists('crypt')) {
          return false;
        }
        if (!is_string($password)) {
          return false;
        }
        if (!is_int($algo)) {
          return false;
        }
        switch ($algo) {
          case PASSWORD_BCRYPT:
            // Note that this is a C constant, but not exposed to PHP, so we don't define it here.
            $cost = 10;
            if (isset($options['cost'])) {
              $cost = $options['cost'];
              if ($cost < 4 || $cost > 31) {
                trigger_error(sprintf("password_hash(): Invalid bcrypt cost parameter specified: %d", $cost), E_USER_WARNING);
                return null;
              }
            }
            // The length of salt to generate
            $raw_salt_len = 16;
            // The length required in the final serialization
            $required_salt_len = 22;
            $hash_format = sprintf("$2y$%02d$", $cost);
          break;
          default:
            trigger_error(sprintf("password_hash(): Unknown password hashing algorithm: %s", $algo), E_USER_WARNING);
            return null;
        }
        if (isset($options['salt'])) {
          switch (gettype($options['salt'])) {
            case 'NULL':
            case 'boolean':
            case 'integer':
            case 'double':
            case 'string':
              $salt = (string) $options['salt'];
            break;
            case 'object':
              if (method_exists($options['salt'], '__tostring')) {
                $salt = (string) $options['salt'];
                break;
              }
            case 'array':
            case 'resource':
            default:
              trigger_error('password_hash(): Non-string salt parameter supplied', E_USER_WARNING);
              return null;
          }
          if (strlen($salt) < $required_salt_len) {
            trigger_error(sprintf("password_hash(): Provided salt is too short: %d expecting %d", strlen($salt), $required_salt_len), E_USER_WARNING);
            return null;
          } elseif (0 == preg_match('#^[a-zA-Z0-9./]+$#D', $salt)) {
            $salt = str_replace('+', '.', base64_encode($salt));
          }
        } else {
          $buffer = '';
          $buffer_valid = false;
          if (function_exists('mcrypt_create_iv') && !defined('PHALANGER')) {
            $buffer = mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);
            if ($buffer) {
              $buffer_valid = true;
            }
          }
          if (!$buffer_valid && function_exists('openssl_random_pseudo_bytes')) {
            $buffer = openssl_random_pseudo_bytes($raw_salt_len);
            if ($buffer) {
              $buffer_valid = true;
            }
          }
          if (!$buffer_valid && is_readable('/dev/urandom')) {
            $f = fopen('/dev/urandom', 'r');
            $read = strlen($buffer);
            while ($read < $raw_salt_len) {
              $buffer .= fread($f, $raw_salt_len - $read);
              $read = strlen($buffer);
            }
            fclose($f);
            if ($read >= $raw_salt_len) {
              $buffer_valid = true;
            }
          }
          if (!$buffer_valid || strlen($buffer) < $raw_salt_len) {
            $bl = strlen($buffer);
            for ($i = 0; $i < $raw_salt_len; $i++) {
              if ($i < $bl) {
                $buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
              } else {
                $buffer .= chr(mt_rand(0, 255));
              }
            }
          }
          $salt = str_replace('+', '.', base64_encode($buffer));
        }
        $salt = substr($salt, 0, $required_salt_len);

        $hash = $hash_format . $salt;

        $ret = crypt($password, $hash);

        if (!is_string($ret) || strlen($ret) <= 13) {
          return false;
        }

        return $ret;
      }

      function password_get_info($hash) {
        $return = array(
          'algo' => 0,
          'algoName' => 'unknown',
          'options' => array(),
        );
        if (substr($hash, 0, 4) == '$2y$' && strlen($hash) == 60) {
          $return['algo'] = PASSWORD_BCRYPT;
          $return['algoName'] = 'bcrypt';
          list($cost) = sscanf($hash, "$2y$%d$");
          $return['options']['cost'] = $cost;
        }
        return $return;
      }

      function password_needs_rehash($hash, $algo, array $options = array()) {
        $info = password_get_info($hash);
        if ($info['algo'] != $algo) {
          return true;
        }
        switch ($algo) {
          case PASSWORD_BCRYPT:
            $cost = isset($options['cost']) ? $options['cost'] : 10;
            if ($cost != $info['options']['cost']) {
              return true;
            }
          break;
        }
        return false;
      }

      function password_verify($password, $hash) {
        if (!function_exists('crypt')) {
          trigger_error("Crypt must be loaded for password_verify to function", E_USER_WARNING);
          return false;
        }
        $ret = crypt($password, $hash);
        if (!is_string($ret) || strlen($ret) != strlen($hash) || strlen($ret) <= 13) {
          return false;
        }

        $status = 0;
        for ($i = 0; $i < strlen($ret); $i++) {
          $status |= (ord($ret[$i]) ^ ord($hash[$i]));
        }

        return $status === 0;
      }
    }
  }

  function detectPasswordType($encryptedPassword)
  {
    $type = 'unknown';
    $tmp = explode(':', $encryptedPassword);
    if (count($tmp) == 2) {
      if (strlen($tmp [1]) > 2) {
        $type = 'compatSha256';
      } elseif (strlen($tmp [1]) == 2) {
        $type = 'oldMd5';
      }
    }
    return $type;
  }

  public function validatePasswordOldMd5($plain, $encrypted)
  {
    if ($this->zen_not_null($plain) && $this->zen_not_null($encrypted)) {
      $stack = explode(':', $encrypted);
      if (sizeof($stack) != 2)
        return false;
      if (md5($stack [1] . $plain) == $stack [0]) {
        return true;
      }
    }
    return false;
  }

  public function validatePasswordCompatSha256($plain, $encrypted)
  {
    if ($this->zen_not_null($plain) && $this->zen_not_null($encrypted)) {
      $stack = explode(':', $encrypted);
      if (sizeof($stack) != 2)
        return false;
      if (hash('sha256', $stack [1] . $plain) == $stack [0]) {
        return true;
      }
    }
    return false;
  }

  public function hashPassword($plain)
  {
    $hash = password_hash($plain, PASSWORD_DEFAULT);

    return $hash;
  }

  protected function zen_not_null($value)
  {
    if (is_array($value)) {
      if (sizeof($value) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
        return true;
      } else {
        return false;
      }
    }
  }

  public function zen_rand($min = null, $max = null) {
    static $seeded;

    if (!isset($seeded)) {
      mt_srand((double)microtime()*1000000);
      $seeded = true;
    }

    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }
}