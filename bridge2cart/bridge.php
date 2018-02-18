<?php
/*------------------------------------------------------------------------------+
| MagneticOne                                                                   |
| Copyright (c) 2018 MagneticOne.com <contact@magneticone.com>                  |
| All rights reserved                                                           |
+-------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "license.txt" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE AT |
| THE FOLLOWING URL: https://www.shopping-cart-migration.com/license-agreement   |
|                                                                               |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS  ON WHICH YOU MAY USE  |
| THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  MAGNETICONE  |
| (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING AVAILABLE |
| AVAILABLE  TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").    |
| PLEASE   REVIEW   THE  TERMS   AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT  |
| CAREFULLY   BEFORE   INSTALLING   OR   USING  THE  SOFTWARE.  BY INSTALLING,  |
| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,   YOU  AND  YOUR  COMPANY  |
| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND  AGREEING  TO  THE TERMS OF THIS  |
| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND  BY THIS  |
| AGREEMENT, DO  NOT  INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND  |
| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.   THIS  |
| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO   USE  |
| THE  SOFTWARE   AND   NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE. |
| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.        |
|                                                                               |
| The Developer of the Code is MagneticOne,                                     |
| Copyright (C) 2006 - 2018 All Rights Reserved.                                |
+-------------------------------------------------------------------------------+
|                                                                               |
|                            ATTENTION!                                         |
+-------------------------------------------------------------------------------+
| By our Terms of Use you agreed not to change, modify, add, or remove portions |
| of Bridge Script source code as it is owned by MagneticOne company.           |
| You agreed not to use, reproduce, modify, adapt, publish, translate           |
| the Bridge Script source code into any form, medium, or technology            |
| now known or later developed throughout the universe.                         |
|                                                                               |
| Full text of our TOS located at                                               |
|                     https://www.shopping-cart-migration.com/terms-of-service   |
+------------------------------------------------------------------------------*/


class M1_Setting
{
  public $cartType         = '';
  public $allowedUpdate    = true;

  public $setCustomAccess = false;
  public $host            = '';
  public $port            = '';
  public $socket          = '';
  public $username        = '';
  public $password        = '';
  public $dbName          = '';
  public $tablePrefix     = '';
}

class M1_Bridge_Action_Savefile
{
  public $imageType = null;

  protected $_notAllowedFileTypes = array(
    'php',
    'html',
    'htm',
    'aspx',
    'xml',
    'phar',
    'js',
  );

  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    $source = $_POST['src'];
    $destination = $_POST['dst'];
    $width = (int)$_POST['width'];
    $height = (int)$_POST['height'];
    $local = $_POST['local_source'];

    echo $this->_saveFile($source, $destination, $width, $height, $local);
  }

  /**
   * @param string $source      source path
   * @param string $destination target path
   * @param int    $width       width
   * @param int    $height      height
   * @param string $local       is local
   *
   * @return string|true
   */
  protected function _saveFile($source, $destination, $width, $height, $local = '')
  {
    $exts = $this->_prepareExtensions();

    $pathInfoSource = @pathinfo(str_replace('../', '', $source));
    $pathInfoTarget = @pathinfo(str_replace('../', '', $destination));
    if (isset($pathInfoSource['extension'])) {
      str_replace($exts, '', preg_replace('/^.*filePath\=/', '', $source), $sourceCount);
    }
    if (isset($pathInfoTarget['extension'])) {
      str_replace($exts, '', $destination, $destinationCount);
    }

    if ((isset($sourceCount) && $sourceCount) || (isset($destinationCount) && $destinationCount)) {
      return '[ERROR] Bad file extension!';
    }

    if (trim($local) != '') {
      //try to download additional image on prestashop from base local image
      $newLocal = preg_replace('/(\.[A-z]{3,4})$/', '.jpg', $local);
      $newDestination = preg_replace('/(\.[A-z]{3,4})$/', '.jpg', $destination);
      if ($this->_copyLocal($newLocal, $newDestination, $width, $height) == 'OK') {
        return 'OK';
      } elseif ($this->_copyLocal($local, $destination, $width, $height) == 'OK') {
        return 'OK';
      }
    }

    if ($this->_isSameHost($source)) {
      $result = $this->_saveFileLocal($source, $destination);
    } else {
      $result = $this->_saveFileCurl($source, $destination);
    }

    if ($result != 'OK') {
      $errMsg = '[TRYING CURL]' . $result . PHP_EOL;

      set_error_handler(array($this, 'handleError'));
      try {
        $result = $this->_saveFileGetContents($source, $destination);
      } catch (Exception $e) {
        restore_error_handler();
        return $errMsg . '[BRIDGE ERROR]' . $e->getMessage();
      }

      restore_error_handler();
    }

    if ($result != 'OK') {
      return $result;
    }

    $destination = M1_STORE_BASE_DIR . $destination;

    if ($width != 0 && $height != 0) {
      if (($result = $this->_scaled($destination, $width, $height)) != 'OK') {
        return $result;
      }
    }

    if ($this->cartType == 'PrestaShop'
      && (pathinfo($destination, PATHINFO_EXTENSION)) !== 'jpg'
      && $local != 'file'
    ) {
      if (($imageGd = $this->_loadImage($destination)) === false) {
        return '[BRIDGE ERROR] Failed load the image!';
      }
      $result = $this->_convert($imageGd, $destination, IMAGETYPE_JPEG, 'jpg');

      if ($result != 'OK') {
        return $result;
      }
      unlink($destination);
    }

    return $result;
  }

  /**
   * @param string $source      source path
   * @param string $destination target path
   * @param int    $width       width
   * @param int    $height      height
   *
   * @return string
   */
  protected function _copyLocal($source, $destination, $width, $height)
  {
    $result = 'OK';
    $source = M1_STORE_BASE_DIR . $source;
    $destination = M1_STORE_BASE_DIR . $destination;

    if (!@copy($source, $destination)) {
      return '[BRIDGE ERROR] Failed to copy the image!';
    }

    if ($width != 0 && $height != 0) {
      $result = $this->_scaled($destination, $width, $height);
    }

    return $result;
  }

  /**
   * @param string $filename file name
   *
   * @return bool|resource
   */
  protected function _loadImage($filename)
  {
    $imageInfo = @getimagesize($filename);

    if ($imageInfo === false) {
      return false;
    }

    $this->imageType = $imageInfo[2];

    switch ($this->imageType) {
      case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($filename);
        break;
      case IMAGETYPE_GIF:
        $image = imagecreatefromgif($filename);
        break;
      case IMAGETYPE_PNG:
        $image = imagecreatefrompng($filename);
        break;
      default:
        return false;
    }

    if ($image === false) {
      return false;
    }

    return $image;
  }

  /**
   * @param resource $image       image file
   * @param string   $filename    file name
   * @param int      $imageType   type
   * @param int      $compression compression
   * @param null     $permissions permissions
   *
   * @return string
   */
  protected function _saveImage($image, $filename, $imageType = IMAGETYPE_JPEG, $compression = 85, $permissions = null)
  {
    $result = true;
    if ($imageType == IMAGETYPE_JPEG) {
      $result = imagejpeg($image, $filename, $compression);
    } elseif ($imageType == IMAGETYPE_GIF) {
      $result = imagegif($image, $filename);
    } elseif ($imageType == IMAGETYPE_PNG) {
      $result = imagepng($image, $filename);
    }

    if (!$result) {
      return '[BRIDGE ERROR] Can\'t not save image ' . $filename . '!';
    }

    if ($permissions != null) {
      @chmod($filename, $permissions);
    }

    imagedestroy($image);

    return 'OK';
  }

  /**
   * @param string $source      path to file
   * @param string $destination path to file
   *
   * @return string
   */
  protected function _saveFileLocal($source, $destination)
  {
    $srcInfo = parse_url($source);
    $src = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . $srcInfo['path'];

    if ($this->_create_dir(dirname($destination)) !== false) {
      $dst = M1_STORE_BASE_DIR . $destination;

      if (!@copy($src, $dst)) {
        return $this->_saveFileCurl($source, $destination);
      }
    } else {
      return '[BRIDGE ERROR] Directory creation failed!';
    }

    return 'OK';
  }

  /**
   * @param string $source      path to file
   * @param string $destination path to file
   *
   * @return string
   */
  protected function _saveFileCurl($source, $destination)
  {
    $source = $this->_escapeSource($source);
    if ($this->_create_dir(dirname($destination)) !== false) {
      $destination = M1_STORE_BASE_DIR . $destination;

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $source);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'accept-encoding: deflate',
        'accept-language: en-US,en;q=0.8,uk;q=0.6',
        'user-agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'
      ));
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);
      curl_setopt($ch, CURLOPT_NOBODY, true);

      if (!ini_get('open_basedir') && ini_get('safe_mode') != 'On') {
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
      }

      curl_exec($ch);
      $httpResponseCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);

      if ($httpResponseCode != 200) {
        curl_close($ch);
        return '[BRIDGE_ERROR_HTTP][' . $httpResponseCode . ']';
      }

      $dst = @fopen($destination, 'wb');
      if ($dst === false) {
        return '[BRIDGE ERROR] Can\'t create ' . $destination . '!';
      }

      curl_setopt($ch, CURLOPT_NOBODY, false);
      curl_setopt($ch, CURLOPT_FILE, $dst);
      curl_setopt($ch, CURLOPT_HTTPGET, true);
      curl_exec($ch);

      if (($errorNo = curl_errno($ch)) != CURLE_OK) {
        return '[BRIDGE ERROR] ' . $errorNo . ': ' . curl_error($ch);
      }

      curl_close($ch);
      @chmod($destination, 0777);

      return 'OK';
    } else {
      return '[BRIDGE ERROR] Directory creation failed!';
    }
  }

  /**
   * @param string $source      path to file
   * @param string $destination path to file
   *
   * @return string
   */
  protected function _saveFileGetContents($source, $destination)
  {
    $destination = M1_STORE_BASE_DIR . $destination;

    $ssl = array('verify_peer' => false);
    if (version_compare(phpversion(), '5.6.0', '>=')) {
      $ssl['verify_peer_name'] = false;
    }

    $context = stream_context_create(
      array(
        'http' => array(
          'timeout' => 60,
          'header' => "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8\r\n"
            . "accept-encoding: deflate\r\n"
            . "accept-language: en-US,en;q=0.8,uk;q=0.6\r\n"
            . "user-agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201\r\n"
        ),
        'ssl' => $ssl
      )
    );

    if (!strstr($source, 'http://') && !strstr($source, 'https://')) {
      $source = 'http://' . $source;
    }

    $file = @file_get_contents($source, false, $context);

    if (!$file) {
      return '[BRIDGE ERROR] Failed to get contents';
    }

    if (!@file_put_contents($destination, $file)) {
      return '[BRIDGE ERROR] Can\'t create ' . $destination . '!';
    }

    return 'OK';
  }

  /**
   * @param string $source file path
   *
   * @return mixed
   */
  protected function _escapeSource($source)
  {
    return str_replace(' ', '%20', $source);
  }

  /**
   * @param string $dir path
   *
   * @return bool
   */
  protected function _create_dir($dir)
  {
    $dirParts = explode('/', $dir);
    $path = M1_STORE_BASE_DIR;
    foreach ($dirParts as $item) {
      if ($item == '') {
        continue;
      }

      $path .= $item . DIRECTORY_SEPARATOR;
      if (!is_dir($path)) {
        $res = @mkdir($path);
        if (!$res) {
          return false;
        }
      }

      @chmod($path, 0777);
    }

    return true;
  }

  /**
   * @param string $source path
   *
   * @return bool
   */
  protected function _isSameHost($source)
  {
    $srcInfo = parse_url($source);

    if (isset($srcInfo['path']) && preg_match('/\.php$/', $srcInfo['path'])) {
      return false;
    }

    $hostInfo = parse_url('http://' . $_SERVER['HTTP_HOST']);
    if (@$srcInfo['host'] == $hostInfo['host']) {
      return true;
    }

    return false;
  }

  /**
   * @param string $destination       path
   * @param int    $destinationWidth  Width
   * @param int    $destinationHeight Height
   *
   * @return string
   */
  protected function _scaled($destination, $destinationWidth, $destinationHeight)
  {
    $method = 0;

    $sourceImage = $this->_loadImage($destination);

    if ($sourceImage === false) {
      return '[BRIDGE ERROR] Image not supported or failed to upload the image';
    }

    $sourceWidth = imagesx($sourceImage);
    $sourceHeight = imagesy($sourceImage);

    $widthDiff = $destinationWidth / $sourceWidth;
    $heightDiff = $destinationHeight / $sourceHeight;

    if ($widthDiff > 1 && $heightDiff > 1) {
      $nextWidth = $sourceWidth;
      $nextHeight = $sourceHeight;
    } else {
      if (intval($method) == 2 || (intval($method) == 0 AND $widthDiff > $heightDiff)) {
        $nextHeight = $destinationHeight;
        $nextWidth = intval(($sourceWidth * $nextHeight) / $sourceHeight);
        $destinationWidth = ((intval($method) == 0) ? $destinationWidth : $nextWidth);
      } else {
        $nextWidth = $destinationWidth;
        $nextHeight = intval($sourceHeight * $destinationWidth / $sourceWidth);
        $destinationHeight = (intval($method) == 0 ? $destinationHeight : $nextHeight);
      }
    }

    $borderWidth = intval(($destinationWidth - $nextWidth) / 2);
    $borderHeight = intval(($destinationHeight - $nextHeight) / 2);

    $destinationImage = imagecreatetruecolor($destinationWidth, $destinationHeight);

    $white = imagecolorallocate($destinationImage, 255, 255, 255);
    imagefill($destinationImage, 0, 0, $white);

    imagecopyresampled($destinationImage, $sourceImage, $borderWidth, $borderHeight, 0, 0,
      $nextWidth, $nextHeight, $sourceWidth, $sourceHeight);
    imagecolortransparent($destinationImage, $white);

    return $this->_saveImage($destinationImage, $destination, $this->imageType, 100);
  }

  /**
   * @param resource $image     GD image object
   * @param string   $filename  store source path file ex. M1_STORE_BASE_DIR . '/img/c/2.gif';
   * @param int      $type      IMAGETYPE_JPEG, IMAGETYPE_GIF or IMAGETYPE_PNG
   * @param string   $extension file extension, this use for jpg or jpeg extension in prestashop
   *
   * @return true if success or false if no
   */
  protected function _convert($image, $filename, $type = IMAGETYPE_JPEG, $extension = '')
  {
    $end = pathinfo($filename, PATHINFO_EXTENSION);

    if ($extension == '') {
      $extension = image_type_to_extension($type, false);
    }

    if ($end == $extension) {
      return 'OK';
    }

    $width = imagesx($image);
    $height = imagesy($image);

    $newImage = imagecreatetruecolor($width, $height);

    /* Allow to keep nice look even if resize */
    $white = imagecolorallocate($newImage, 255, 255, 255);
    imagefill($newImage, 0, 0, $white);
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $width, $height);
    imagecolortransparent($newImage, $white);

    $pathSave = rtrim($filename, $end);

    $pathSave .= $extension;

    return $this->_saveImage($newImage, $pathSave, $type);
  }

  public function handleError($severity, $message, $file, $line)
  {
    throw new ErrorException($message, $severity, $severity, $file, $line);
  }

  protected function _prepareExtensions()
  {
    $extensions = array();

    foreach ($this->_notAllowedFileTypes as $value) {
      $extensions[] = '.' . $value;
    }

    return $extensions;
  }
}

class M1_Config_Adapter
{
  public $host        = 'localhost';
  public $port        = '3306';
  public $socket      = '';
  public $userName    = 'root';
  public $password    = '';
  public $dbName      = '';
  public $tablePrefix = '';

  public $cartType                = 'PrestaShop';
  public $imagesDir               = '';
  public $categoriesImagesDir     = '';
  public $productsImagesDir       = '';
  public $manufacturersImagesDir  = '';
  public $categoriesImagesDirs    = '';
  public $productsImagesDirs      = '';
  public $manufacturersImagesDirs = '';

  public $languages      = array();
  public $languageIso2   = '';
  public $connectionType = false;
  public $cartVars       = array('dbCharSet' => 'utf8');

  /**
   * @return mixed
   */
  public function create()
  {
    $settings = new M1_Setting();

    if ($settings->cartType == '') {
      $cartType = M1_Config_Adapter::detectCartType();
    } else {
      $cartType = $settings->cartType;
    }
    $className = 'M1_Config_Adapter_' . $cartType;
    $obj = new $className();

    if ($cartType == 'PrestaShop') {
      return $obj;
    }

    $obj->cartType = $cartType;
    return $obj;
  }

  /**
   * @return string
   */
  public function detectCartType()
  {
    //Shopware
    if (@file_exists(M1_STORE_BASE_DIR .  'config.php') && @file_exists(M1_STORE_BASE_DIR .  'engine/Shopware/')) {
      return 'Shopware';
    }

    //PrestaShop
    if (@file_exists(M1_STORE_BASE_DIR . 'config/config.inc.php')) {
      return 'PrestaShop';
    }

    //Magento
    if (((@file_exists(M1_STORE_BASE_DIR . 'app/etc/local.xml')) || (@file_exists(M1_STORE_BASE_DIR . 'app/etc/env.php')))
        || (!(@file_exists(M1_STORE_BASE_DIR . 'app/etc/env.php')) && @file_exists(M1_STORE_BASE_DIR . '/../app/etc/env.php'))) {
      return 'Magento';
    }

    //Zencart137
    if (@file_exists(M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'configure.php')
      && @file_exists(M1_STORE_BASE_DIR . 'ipn_main_handler.php')
    ) {
      return 'Zencart137';
    }

    //Gambio
    if (@file_exists(M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'configure.php')
      && @file_exists(M1_STORE_BASE_DIR . 'gm' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'GMCat.php')
    ) {
      return 'Gambio';
    }

    //osCommerce
    if (@file_exists(M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'configure.php')
      && !@file_exists(M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'toc_constants.php')/* is if not tomatocart */
    ) {

      if (@file_exists(M1_STORE_BASE_DIR . 'loadedpayments.php')) {
        return 'LoadedCommerce';
      }

      return 'Oscommerce22ms2';
    }

    //JooCart
    if (@file_exists(M1_STORE_BASE_DIR . '/components/com_opencart/opencart.php')) {
      return 'JooCart';
    }

    //ACEShop
    if (@file_exists(M1_STORE_BASE_DIR . '/components/com_aceshop/aceshop.php')) {
      return 'AceShop';
    }

    //MijoShop
    if (@file_exists(M1_STORE_BASE_DIR . '/components/com_mijoshop/mijoshop.php')) {
      return 'MijoShop';
    }

    //HikaShop
    if (@file_exists(M1_STORE_BASE_DIR . '/components/com_hikashop/hikashop.php')) {
      return 'HikaShop';
    }

    //Virtuemart
    if ((@file_exists(M1_STORE_BASE_DIR . 'configuration.php'))
      && (!@file_exists(M1_STORE_BASE_DIR . '/components/com_mijoshop/mijoshop.php'))
      && (!@file_exists(M1_STORE_BASE_DIR . '/components/com_hikashop/hikashop.php'))
    ) {
      return 'Virtuemart';
    }

    //Pinnacle
    if (@file_exists(M1_STORE_BASE_DIR . 'content/engine/engine_config.php')) {
      return 'Pinnacle';
    }

    //Cubecart
    if (@file_exists(M1_STORE_BASE_DIR . 'includes/global.inc.php')) {
      return 'Cubecart';
    }

    //Xcart
    if (((@file_exists(M1_STORE_BASE_DIR . 'include/classes/class.DataStorage.php') || @file_exists(M1_STORE_BASE_DIR . 'change_password.php')) && @file_exists(M1_STORE_BASE_DIR . 'config.php')) || @file_exists(M1_STORE_BASE_DIR . '/etc/config.php')) {
      return 'Xcart';
    }

    //Cscart
    if ((@file_exists(M1_STORE_BASE_DIR . 'core/fn_catalog.php') && @file_exists(M1_STORE_BASE_DIR . 'core/include_addons.php'))
      || @file_exists(M1_STORE_BASE_DIR . 'config.local.php') || @file_exists(M1_STORE_BASE_DIR . 'partner.php') //version 2.0
    ) {
      return 'Cscart';
    }

    //Merchium
    if (@getenv('SAAS_UID')) {
      return 'Merchium';
    }

    //Arastta
    if (@file_exists(M1_STORE_BASE_DIR . 'arastta')) {
      return 'Arastta';
    }

    //Opencart
    if ((@file_exists(M1_STORE_BASE_DIR . 'system/startup.php')
      || (@file_exists(M1_STORE_BASE_DIR . 'common.php'))
      || (@file_exists(M1_STORE_BASE_DIR . 'library/locator.php')))
      && @file_exists(M1_STORE_BASE_DIR . 'config.php')
    ) {
      return 'Opencart';
    }

    //LemonStand
    if (@file_exists(M1_STORE_BASE_DIR . 'boot.php')) {
      return 'LemonStand';
    }

    //Interspire
    if (@file_exists(M1_STORE_BASE_DIR . 'config/config.php')) {
      return 'Interspire';
    }

    //Squirrelcart242
    if (@file_exists(M1_STORE_BASE_DIR . 'squirrelcart/config.php')) {
      return 'Squirrelcart242';
    }

    //Shopscript
    if (@file_exists(M1_STORE_BASE_DIR . 'kernel/wbs.xml')
      || @file_exists(M1_STORE_BASE_DIR . 'cfg/connect.inc.php')
    ) {
      return 'Shopscript';
    }

    //Summercart3
    if (@file_exists(M1_STORE_BASE_DIR . 'sclic.lic')
      && @file_exists(M1_STORE_BASE_DIR . 'include/miphpf/Config.php')
    ) {
      return 'Summercart3';
    }

    //Xtcommerce
    if (@file_exists(M1_STORE_BASE_DIR . 'conf/config.php')) {
      return 'Xtcommerce';
    }

    //Ubercart
    if (@file_exists(M1_STORE_BASE_DIR . 'sites/default/settings.php')) {
      if (@file_exists(M1_STORE_BASE_DIR . 'sites/all/modules/ubercart/uc_store/includes/coder_review_uc3x.inc')
        || @file_exists(M1_STORE_BASE_DIR . 'modules/ubercart/uc_store/includes/coder_review_uc3x.inc')
      ) {
        return 'Ubercart';
      } elseif (@file_exists(M1_STORE_BASE_DIR . 'profiles/commerce_kickstart/commerce_kickstart.info')) {
        return 'Kickstart';
      } elseif (@file_exists(M1_STORE_BASE_DIR . 'sites/all/modules/commerce/includes/commerce.controller.inc')) {
        return 'DrupalCommerce';
      }

      return 'Ubercart';
    }

    //Word Press
    if (@file_exists(M1_STORE_BASE_DIR . 'wp-config.php')) {

      $wooCommerceDir = glob(M1_STORE_BASE_DIR . 'wp-content/plugins/woocommerce*', GLOB_ONLYDIR);
      if (is_array($wooCommerceDir) && count($wooCommerceDir) > 0) {
        return 'Woocommerce';
      }

      $jigoshopDir = glob(M1_STORE_BASE_DIR . 'wp-content/plugins/jigoshop*', GLOB_ONLYDIR);
      if (is_array($jigoshopDir) && count($jigoshopDir) > 0) {
        return 'Jigoshop';
      }

      $miwoShopDir = glob(M1_STORE_BASE_DIR . 'wp-content/plugins/miwoshop*', GLOB_ONLYDIR);
      if (is_array($miwoShopDir) && count($miwoShopDir) > 0) {
        return 'MiwoShop';
      }

      return 'WPecommerce';
    }

    //OXID e-shop
    if (@file_exists(M1_STORE_BASE_DIR . 'oxid.php')
      || @file_exists(M1_STORE_BASE_DIR . '/core/oxid.php')
    ) {
      return 'Oxid';
    }

    //HHGMultistore
    if (@file_exists(M1_STORE_BASE_DIR . 'core/config/configure.php')) {
      return 'Hhgmultistore';
    }

    //SunShop
    if (@file_exists(M1_STORE_BASE_DIR . 'include' . DIRECTORY_SEPARATOR . 'config.php')
      || @file_exists(M1_STORE_BASE_DIR . 'include' . DIRECTORY_SEPARATOR . 'db_mysql.php')
    ) {
      return 'Sunshop4';
    }

    //Tomatocart
    if (@file_exists(M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'configure.php')
      && @file_exists(M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'toc_constants.php')
    ) {
      return 'Tomatocart';
    }

    //Loaded7
    if (@file_exists(M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'config.php')) {
      return 'Loaded7';
    }

    //LiteCart|Xcart5
    if (@file_exists(M1_STORE_BASE_DIR . '/includes/config.inc.php')) {
      if (@file_exists(M1_STORE_BASE_DIR . '/XLite.php')) {
        return 'Xcart'; //from 5.0.0
      }
      return 'LiteCart';
    }

    die('BRIDGE_ERROR_CONFIGURATION_NOT_FOUND');
  }

  /**
   * @param string $cartType cart type
   *
   * @return string
   */
  public function getAdapterPath($cartType)
  {
    return M1_STORE_BASE_DIR .
                   M1_BRIDGE_DIRECTORY_NAME . DIRECTORY_SEPARATOR .
                   'app' . DIRECTORY_SEPARATOR .
                   'class' . DIRECTORY_SEPARATOR .
                   'config_adapter' . DIRECTORY_SEPARATOR . $cartType . '.php';
  }

  /**
   * @param string $source source
   *
   * @return void
   */
  public function setHostPort($source)
  {
    $source = trim($source);

    if ($source == '') {
      $this->host = 'localhost';
      return;
    }

    $conf = explode(':', $source);
    if (isset($conf[0]) && isset($conf[1]) && isset($conf[2])) {
      $this->host = $conf[0];

      if (is_numeric($conf[1])) {
        $this->port = $conf[1];
      } else{
        $this->socket = $conf[1];
      }
      if (is_numeric($conf[2])) {
        $this->port = $conf[2];
      } else{
        $this->socket = $conf[2];
      }
    } elseif (isset($conf[0]) && isset($conf[1])) {
      $this->host = $conf[0];

      if (is_numeric($conf[1])) {
        $this->port = $conf[1];
      } else{
        $this->socket = $conf[1];
      }
    } elseif ($source[0] == '/') {
      $this->host = 'localhost';
      $this->port = $source;
    } else {
      $this->host = $source;
    }
  }

  /**
   * @return M1_Mysql|M1_Pdo
   */
  public function connect()
  {
    $settings = new M1_Setting();

    if ($settings->setCustomAccess) {
      $this->host        = $settings->host;
      $this->port        = $settings->port;
      $this->socket      = $settings->socket;
      $this->userName    = $settings->username;
      $this->password    = $settings->password;
      $this->dbName      = $settings->dbName;
      $this->tablePrefix = $settings->tablePrefix;
    }

    if (function_exists('mysql_connect')) {
      $link = new M1_Mysql($this);
    } elseif (class_exists('PDO')) {
      $link = new M1_Pdo($this);
    } else {
      $link = new M1_Mysqli($this);
    }

    return $link;
  }

  /**
   * @param string $field     field
   * @param string $tableName tableName
   * @param string $where     where
   *
   * @return string
   */
  public function getCartVersionFromDb($field, $tableName, $where)
  {
    $version = '';

    $link = $this->connect();
    if (!$link) {
      return '[ERROR] MySQL Query Error: Can not connect to DB';
    }

    $tableExist = $link->localQuery("SHOW TABLES like '" . $this->tablePrefix . $tableName . "'");

    if (isset($tableExist[0]) && !empty($tableExist[0])) {

      $sql = 'SELECT ' . $field . ' AS version FROM ' . $this->tablePrefix . $tableName . ' WHERE ' . $where;

      $result = $link->localQuery($sql);

      if (is_array($result) && isset($result[0]['version'])) {
        $version = $result[0]['version'];
      }
    }

    return $version;
  }

  /**
   * @return string
   */
  public function getCharsetFromDb()
  {
    $link = $this->connect();
    if (!$link) {
      return '[ERROR] MySQL Query Error: Can not connect to DB';
    }

    return $link->getCharset();
  }
}

class M1_Mysql
{

  public $config = null; // config adapter
  public $result = array();
  public $dataBaseHandle = null;

  /**
   * mysql constructor
   *
   * @param M1_Config_Adapter $config configuration
   *
   * @return M1_Mysql
   */
  public function __construct($config)
  {
    $this->config = $config;
  }

  /**
   * @return bool|null|resource
   */
  public function getDataBaseHandle()
  {
    if ($this->dataBaseHandle) {
      return $this->dataBaseHandle;
    }

    $this->dataBaseHandle = $this->connect();

    if (!$this->dataBaseHandle) {
      exit('[ERROR] MySQL Query Error: Can not connect to DB');
    }

    return $this->dataBaseHandle;
  }

  /**
   * @return bool|null|resource
   */
  public function connect()
  {
    $triesCount = 5;
    $link = null;
    $host = $this->config->host
      . ($this->config->port ? ':' . $this->config->port : '')
      . ($this->config->socket ? ':' . $this->config->socket : '');

    $password = (stripslashes($this->config->password));

    while (!$link) {
      if (!$triesCount--) {
        break;
      }

      $link = @mysql_connect($host, $this->config->userName, $password);
      if (!$link) {
        sleep(5);
      }

      if ($triesCount == 3) {
        // fix invalid port and socket
        $host = $this->config->host;
      } elseif ($triesCount == 1) {
        $password = sprintf($password);
      }
    }

    if (!$link) {
      return false;
    }

    if ($link) {
      mysql_select_db($this->config->dbName, $link);
    }

    return $link;
  }

  /**
   * @param string $sql sql query
   *
   * @return array
   */
  public function localQuery($sql)
  {
    $result = array();
    $dataBaseHandle = $this->getDataBaseHandle();

    $sth = mysql_query($sql, $dataBaseHandle);

    if (is_bool($sth)) {
      return $sth;
    }

    while (($row = mysql_fetch_assoc($sth)) != false) {
      $result[] = $row;
    }

    return $result;
  }

  /**
   * @param string $sql       sql query
   * @param int    $fetchType fetch Type
   *
   * @return array
   */
  public function query($sql, $fetchType)
  {
    $result = array(
      'result' => null,
      'message' => '',
    );
    $dataBaseHandle = $this->getDataBaseHandle();

    if (!$dataBaseHandle) {
      $result['message'] = '[ERROR] MySQL Query Error: Can not connect to DB';
      return $result;
    }

    $fetchMode = MYSQL_ASSOC;
    switch ($fetchType) {
      case 3:
        $fetchMode = MYSQL_BOTH;
        break;
      case 2:
        $fetchMode = MYSQL_NUM;
        break;
      case 1:
        $fetchMode = MYSQL_ASSOC;
        break;
      default:
        break;
    }

    $res = mysql_query($sql, $dataBaseHandle);

    $triesCount = 10;
    while (mysql_errno($dataBaseHandle) == 2013) {
      if (!$triesCount--) {
        break;
      }
      // reconnect
      $dataBaseHandle = $this->getDataBaseHandle();
      if ($dataBaseHandle) {
        // execute query once again
        $res = mysql_query($sql, $dataBaseHandle);
      }
    }

    if (($errno = mysql_errno($dataBaseHandle)) != 0) {
      $result['mysql_error_num'] = $errno;
      $result['message'] = '[ERROR] Mysql Query Error: ' . $errno . ', ' . mysql_error($dataBaseHandle);
      return $result;
    }

    if (!is_resource($res)) {
      $result['result'] = $res;
      return $result;
    }

    $fetchedFields = array();
    while (($field = mysql_fetch_field($res)) !== false) {
      $fetchedFields[] = $field;
    }

    $rows = array();
    while (($row = mysql_fetch_array($res, $fetchMode)) !== false) {
      $rows[] = gzdeflate(serialize($row));
    }

    $result['result'] = $rows;
    $result['fetchedFields'] = $fetchedFields;

    mysql_free_result($res);
    return $result;
  }

  /**
   * get dbCharset from database
   *
   * @param string $default default charset
   *
   * @return string
   */
  public function getCharset($default = 'utf8')
  {
    if ($this->getDataBaseHandle()) {
      $res = mysql_query("
          SELECT CHARACTER_SET_NAME as cs, count(CHARACTER_SET_NAME) as count
          FROM INFORMATION_SCHEMA.COLUMNS
          WHERE CHARACTER_SET_NAME <> ''
          GROUP BY CHARACTER_SET_NAME
          ORDER BY count DESC
          LIMIT 1
        ",
        $this->getDataBaseHandle()
      );

      if (!is_resource($res)) {
        return $default;
      }

      if ($row = mysql_fetch_assoc($res)) {
        return $row['cs'];
      }

      return $default;
    }

    return $default;
  }

  /**
   * @return string
   */
  public function getServerInfo()
  {
    if ($this->getDataBaseHandle()) {
      return mysql_get_server_info($this->getDataBaseHandle());
    }

    return '0.0.0';
  }

  /**
   * @return int
   */
  public function getLastInsertId()
  {
    return mysql_insert_id($this->dataBaseHandle);
  }

  /**
   * @return int
   */
  public function getAffectedRows()
  {
    return mysql_affected_rows($this->dataBaseHandle);
  }

  /**
   * @return void
   */
  public function __destruct()
  {
    if ($this->dataBaseHandle) {
      mysql_close($this->dataBaseHandle);
    }

    $this->dataBaseHandle = null;
  }
}

class M1_Bridge
{
  public $link = null; // connection link
  public $res = null; // query result
  public $tablePrefix = ''; // table prefix
  public $config = null; // config adapter

  const QUERY_ERROR_GONE_AWAY = 2006;
  const QUERY_LOCK_WAIT_TIMEOUT_EXCEEDED = 1205;
  const QUERY_ERROR_DEADLOCK = 1213;

  /**
   * M1_Bridge constructor.
   *
   * @param M1_Config_Adapter|array $config configuration
   */
  public function __construct($config)
  {
    $this->config = $config;
  }

  /**
   * @return string
   */
  public function getTablesPrefix()
  {
    return $this->tablePrefix;
  }

  /**
   * @return M1_Mysql|M1_Pdo|null
   */
  public function getLink()
  {
    return $this->link;
  }

  /**
   * @param string $sql       query
   * @param int    $fetchMode fetch Mode
   *
   * @return array
   */
  public function query($sql, $fetchMode)
  {
    $count = 2;
    $additionalText = '';

    while ($count--) {
      $res = $this->link->query($sql, $fetchMode);
      if (isset($res['mysql_error_num']) && in_array($res['mysql_error_num'], array(
          self::QUERY_ERROR_GONE_AWAY,
          self::QUERY_ERROR_DEADLOCK,
          self::QUERY_LOCK_WAIT_TIMEOUT_EXCEEDED,
        ))
      ) {
        sleep(4);
        $additionalText = ', tried to run query again;';
        continue;
      } else {
        break;
      }
    }

    $res['message'] .= $additionalText;

    return $res;
  }

  /**
   * @return mixed|string
   */
  public function getAction()
  {
    if (isset($_GET['action'])) {
      return str_replace('.', '', $_GET['action']);
    } else {
      return '';
    }
  }

  /**
   * @return array
   */
  public function getParams()
  {
    $responce = array();
    $responce['funcname'] = isset($_GET['funcname']) ? $_GET['funcname'] : '';

    $iteration = 1;
    while (isset($_GET['param' . $iteration])) {
      $value = $_GET['param' . $iteration];
      if(preg_match('/^\\[.*\\]$/', $value)) {
        $value = preg_replace('/[\\[\\]]/', '', $value);
        $value = explode(',', $value);
        $responce['param' . $iteration] = $value;
      } else {
        $responce['param' . $iteration] = $_GET['param' . $iteration];
      }
      $iteration++;
    }

    return $responce;
  }

  /**
   * @return void
   */
  public function run()
  {
    $actionIgnoreSelfTest = array('update' => true, 'move' => true, 'getserverip' => true, 'query' => true, 'querymultiple' => true, 'loadfromfile' => true, 'getfile' => true);
    $checkDbConnectionActions = array('carttype' => true, 'clearcache' => true, 'mysqlver' => true, 'phpinfo' => true, 'query' => true, 'querymultiple' => true, 'loadfromfile' => true);

    $action = $this->getAction();

    if (isset($checkDbConnectionActions[$action])) {
      $this->link = $this->config->connect();
      if (!$this->link || !$this->getLink()->connect()) {
        die('ERROR_BRIDGE_CANT_CONNECT_DB');
      }
    }

    if (!isset($_GET['ver']) && !isset($_GET['token'])) {
      die($this->_getBridgeInstalledMessage());
    }

    if (!isset($_GET['ver']) || (isset($_GET['ver']) && $_GET['ver'] != M1_BRIDGE_VERSION)) {
      die('ERROR_BRIDGE_VERSION_NOT_SUPPORTED');
    }

    if (!isset($_GET['token']) || $_GET['token'] != M1_TOKEN) {
      die('ERROR_INVALID_TOKEN');
    }

    if (!isset($actionIgnoreSelfTest[$action])) {
      $this->_selfTest();
    }

    if ($action == 'checkbridge') {
      echo 'BRIDGE_OK';
      return;
    }

    $className = 'M1_Bridge_Action_' . ucfirst($action);

    if (!class_exists($className)) {
      echo 'ACTION_DOES_NOT_EXIST' . PHP_EOL;
      die();
    }

    $actionObj = new $className();
    @$actionObj->cartType = @$this->config->cartType;
    $actionObj->perform($this);
    $this->destroy();
  }

  /**
   * @param string $dir directory name
   *
   * @return bool
   */
  public function isWritable($dir)
  {
    return is_dir($dir) && is_writable($dir);
  }

  /**
   * destructor
   *
   * @return void
   */
  public function destroy()
  {
    $this->link and $this->link->__destruct();
  }

  /**
   * @return void
   */
  public function _checkPossibilityUpdate()
  {
    if (!is_writable(M1_STORE_BASE_DIR . '/' . M1_BRIDGE_DIRECTORY_NAME . '/')) {
      die('ERROR_TRIED_TO_PERMISSION_CART2CART' . M1_STORE_BASE_DIR . '/' . M1_BRIDGE_DIRECTORY_NAME . '/');
    }

    if (!is_writable(M1_STORE_BASE_DIR . '/' . M1_BRIDGE_DIRECTORY_NAME . '/bridge.php')) {
      die('ERROR_TRIED_TO_PERMISSION_BRIDGE_FILE' . M1_STORE_BASE_DIR . '/' . M1_BRIDGE_DIRECTORY_NAME . '/bridge.php');
    }
  }

  /**
   * @return void
   */
  public function _selfTest()
  {
    if ((!isset($_GET['storetype']) || $_GET['storetype'] == 'target') && $this->getAction() == 'checkbridge') {

      if (trim($this->config->imagesDir) != '') {
        if (!@file_exists(M1_STORE_BASE_DIR . $this->config->imagesDir) && is_writable(M1_STORE_BASE_DIR)) {
          if (!@mkdir(M1_STORE_BASE_DIR . $this->config->imagesDir, 0777, true)) {
            die('ERROR_TRIED_TO_CREATE_IMAGE_DIR' . M1_STORE_BASE_DIR . $this->config->imagesDir);
          }
        }

        if (!$this->isWritable(M1_STORE_BASE_DIR . $this->config->imagesDir)) {
          die('ERROR_NO_IMAGES_DIR ' . M1_STORE_BASE_DIR . $this->config->imagesDir);
        }
      }

      if (trim($this->config->categoriesImagesDir) != '') {
        if (!@file_exists(M1_STORE_BASE_DIR . $this->config->categoriesImagesDir) && is_writable(M1_STORE_BASE_DIR)) {
          if (!@mkdir(M1_STORE_BASE_DIR . $this->config->categoriesImagesDir, 0777, true)) {
            die('ERROR_TRIED_TO_CREATE_IMAGE_DIR' . M1_STORE_BASE_DIR . $this->config->categoriesImagesDir);
          }
        }

        if (!$this->isWritable(M1_STORE_BASE_DIR . $this->config->categoriesImagesDir)) {
          die('ERROR_NO_IMAGES_DIR ' . M1_STORE_BASE_DIR . $this->config->categoriesImagesDir);
        }
      }

      if (trim($this->config->productsImagesDir) != '') {
        if (!@file_exists(M1_STORE_BASE_DIR . $this->config->productsImagesDir) && is_writable(M1_STORE_BASE_DIR)) {
          if (!@mkdir(M1_STORE_BASE_DIR . $this->config->productsImagesDir, 0777, true)) {
            die('ERROR_TRIED_TO_CREATE_IMAGE_DIR' . M1_STORE_BASE_DIR . $this->config->productsImagesDir);
          }
        }

        if (!$this->isWritable(M1_STORE_BASE_DIR . $this->config->productsImagesDir)) {
          die('ERROR_NO_IMAGES_DIR ' . M1_STORE_BASE_DIR . $this->config->productsImagesDir);
        }
      }

      if (trim($this->config->manufacturersImagesDir) != '') {
        if (!@file_exists(M1_STORE_BASE_DIR . $this->config->manufacturersImagesDir) && is_writable(M1_STORE_BASE_DIR)) {
          if (!@mkdir(M1_STORE_BASE_DIR . $this->config->manufacturersImagesDir, 0777, true)) {
            die('ERROR_TRIED_TO_CREATE_IMAGE_DIR' . M1_STORE_BASE_DIR . $this->config->manufacturersImagesDir);
          }
        }

        if (!$this->isWritable(M1_STORE_BASE_DIR . $this->config->manufacturersImagesDir)) {
          die('ERROR_NO_IMAGES_DIR ' . M1_STORE_BASE_DIR . $this->config->manufacturersImagesDir);
        }
      }
    }
  }

  private function _getBridgeInstalledMessage()
  {
    return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb">
            <head>
            <title>Bridge successfully Installed!</title>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,500,600,700,800,600">
            <style>.standart{font-family:"Open Sans";font-weight:400;text-align:center;margin:0}#stroke{stroke-dasharray:300;stroke-dashoffset:-300;-webkit-animation:.23s drawStroke ease-in forwards;-webkit-animation-delay: .25s;animation:.23s drawStroke ease-in forwards; animation-delay: .25s}#shadow{-webkit-animation:.23s drawShadow ease-in forwards;-webkit-animation-delay: .25s;animation:.23s drawShadow ease-in forwards;animation-delay: .25s;opacity:0}@keyframes drawStroke{0%{stroke-dasharray:300;stroke-dashoffset:-300}100%{stroke-dasharray:300;stroke-dashoffset:-600}}@keyframes drawShadow{50%{opacity:0}100%{opacity:1}}</style>
            </head>
            <body style="margin:0;height:100vh">
            <div style="position:absolute;top:45%;left:50%;transform:translate(-50%,-50%)">
            <div style="text-align:center;min-width:171px;min-height:171px">
            <svg width="171px" height="171px">
            <circle style="fill:#31b70b" cx="83.359985" cy="83.256378" r="83.222153"/>
            <g id="shadow">
            <path style="fill:#2a9c09" d="m 142.56453,56.004085 -72.211567,72.212355 35.064597,35.0638 a 83.222153,83.222153 0 0 0 61.10817,-80.222601 83.222153,83.222153 0 0 0 -0.1138,-3.206155 l -23.8474,-23.847399 z" />
            </g>
            <g>
            <g id="stroke" style="fill:none;stroke-color:white;stroke-width:100" transform="matrix(0.40462183,0,0,0.40462183,-56.950502,-78.243682)">
            <path style="stroke:white;stroke-width:63;stroke-linecap:square" d="m 249.5376,400.32987 65.60836,66.01249 134.59464,-133.3635"/>
            </g>
            </g>
            </svg>
            </div>
            <h1 class="standart" style="margin:10px">Bridge successfully Installed!</h1>
            <h3 class="standart">Now you can continue with your migration setup.</h3>
            <h3 class="standart">This window could be closed.</h3>
            </div>
            </body>
            </html>';
  }
}


class M1_Config_Adapter_Kickstart extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Kickstart constructor.
   */
  public function __construct()
  {
    @include_once M1_STORE_BASE_DIR . "sites/default/settings.php";

    $url = $databases['default']['default'];

    $url['username'] = urldecode($url['username']);
    $url['password'] = isset($url['password']) ? urldecode($url['password']) : '';
    $url['host'] = urldecode($url['host']);
    $url['database'] = urldecode($url['database']);
    if (isset($url['port'])) {
      $url['host'] = $url['host'] .':'. $url['port'];
    }

    $this->setHostPort($url['host']);
    $this->dbName   = ltrim($url['database'], '/');
    $this->userName = $url['username'];
    $this->password = $url['password'];

    $this->imagesDir = '/sites/default/files/';
    if (!@file_exists(M1_STORE_BASE_DIR . $this->imagesDir)) {
      $this->imagesDir = '/files';
    }

    $fileInfo = M1_STORE_BASE_DIR . '/sites/all/modules/commerce/commerce.info';
    if (@file_exists($fileInfo)) {
      $str = file_get_contents($fileInfo);
      if (preg_match('/version\s+=\s+".+-(.+)"/', $str, $match) != 0) {
        $this->cartVars['dbVersion'] = $match[1];
        unset($match);
      }
    }

    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;
  }
}

class M1_Config_Adapter_WPecommerce extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_WPecommerce constructor.
   */
  public function __construct()
  {
    //@include_once M1_STORE_BASE_DIR . "wp-config.php";
    $config = file_get_contents(M1_STORE_BASE_DIR . 'wp-config.php');
    preg_match("/define\(\'DB_NAME\', \'(.+)\'\);/", $config, $match);
    $this->dbName   = $match[1];
    preg_match("/define\(\'DB_USER\', \'(.+)\'\);/", $config, $match);
    $this->userName = $match[1];
    preg_match("/define\(\'DB_PASSWORD\', \'(.*)\'\);/", $config, $match);
    $this->password = $match[1];
    preg_match("/define\(\'DB_HOST\', \'(.+)\'\);/", $config, $match);
    $this->setHostPort( $match[1] );
    preg_match("/(table_prefix)(.*)(')(.*)(')(.*)/", $config, $match);
    $this->tablePrefix = $match[4];

    preg_match('/define\s*\(\s*\'DB_CHARSET\',\s*\'(.+)\'\s*\)\s*;/', $config, $match);
    if (isset($match[1])) {
      $this->cartVars['dbCharSet'] = $match[1];
    }

    $version = $this->getCartVersionFromDb('option_value', 'options', "option_name = 'wpsc_version'");
    if ($version != '') {
      $this->cartVars['dbVersion'] = $version;
    } else {
      if (@file_exists(M1_STORE_BASE_DIR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR
        . 'wp-shopping-cart' . DIRECTORY_SEPARATOR . 'wp-shopping-cart.php')) {
        $conf = file_get_contents(M1_STORE_BASE_DIR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR
          . 'wp-shopping-cart' . DIRECTORY_SEPARATOR . 'wp-shopping-cart.php');
        preg_match("/define\('WPSC_VERSION.*/", $conf, $match);
        if (isset($match[0]) && !empty($match[0])) {
          preg_match("/\d.*/", $match[0], $project);
          if (isset($project[0]) && !empty($project[0])) {
            $version = $project[0];
            $version = str_replace(array(' ','-','_',"'",');',')',';'), '', $version);
            if ($version != '') {
              $this->cartVars['dbVersion'] = strtolower($version);
            }
          }
        }
      }
    }

    if (@file_exists(M1_STORE_BASE_DIR . 'wp-content/plugins/shopp/Shopp.php')) {
      $shoppFile = file_get_contents(M1_STORE_BASE_DIR . 'wp-content/plugins/shopp/Shopp.php');
      if (!preg_match("/define\( *'SHOPP_VERSION' *, *'([0-9\.]{3})/", $shoppFile, $cartVersion)) {
        preg_match("/.Version:\s*([0-9\.]{3})/", $shoppFile, $cartVersion);
      }

      $this->cartVars['cartVersion'] = @$cartVersion[1];
      $this->imagesDir = 'wp-content/uploads/wpsc/';
      $this->categoriesImagesDir    = $this->imagesDir . 'category_images/';
      $this->productsImagesDir      = $this->imagesDir . 'product_images/';
      $this->manufacturersImagesDir = $this->imagesDir;
    } elseif (@file_exists(M1_STORE_BASE_DIR . 'wp-content/plugins/wp-e-commerce/editor.php')) {
      $this->imagesDir = 'wp-content/uploads/wpsc/';
      $this->categoriesImagesDir    = $this->imagesDir . 'category_images/';
      $this->productsImagesDir      = $this->imagesDir . 'product_images/';
      $this->manufacturersImagesDir = $this->imagesDir;
    } elseif (@file_exists(M1_STORE_BASE_DIR . 'wp-content/plugins/wp-e-commerce/wp-shopping-cart.php')) {
      $this->imagesDir = 'wp-content/uploads/';
      $this->categoriesImagesDir    = $this->imagesDir . 'wpsc/category_images/';
      $this->productsImagesDir      = $this->imagesDir;
      $this->manufacturersImagesDir = $this->imagesDir;
    } elseif (@file_exists(M1_STORE_BASE_DIR . 'wp-content/plugins/wp-cart-for-digital-products/eStore_classes.php')) {
      $this->imagesDir = 'wp-content/uploads/';
      $this->categoriesImagesDir    = $this->imagesDir;
      $this->productsImagesDir      = $this->imagesDir;
      $this->manufacturersImagesDir = $this->imagesDir;
    } else {
      $this->imagesDir = 'images/';
      $this->categoriesImagesDir    = $this->imagesDir;
      $this->productsImagesDir      = $this->imagesDir;
      $this->manufacturersImagesDir = $this->imagesDir;
    }
  }
}



class M1_Config_Adapter_Magento extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Magento constructor.
   */
  public function __construct()
  {
    // MAGENTO 2.X
    $baseDir = M1_STORE_BASE_DIR;
    $check = true;
    $imagesDir = 'pub/media/';

    if (!file_exists($baseDir . 'app/etc/env.php') && file_exists($baseDir . '/../app/etc/env.php')) {
      $baseDir = M1_STORE_BASE_DIR . '/../';
      $check = false;
      $imagesDir = 'media/';
    }

    if (!$check || file_exists($baseDir . 'app/etc/env.php')) {
      /**
       * @var array
       */
      $config = @include($baseDir . 'app/etc/env.php');

      $this->cartVars['AdminUrl'] = (string)$config['backend']['frontName'];

      $db = array();
      foreach ($config['db']['connection'] as $connection) {
        if ($connection['active'] == 1) {
          $db = $connection;
          break;
        }
      }

      $this->setHostPort((string)$db['host']);
      $this->userName = (string)$db['username'];
      $this->dbName   = (string)$db['dbname'];
      $this->password = (string)$db['password'];

      if (@file_exists($baseDir . 'composer.json')) {
        $ver = file_get_contents($baseDir . 'composer.json');
        if (preg_match("/\"version\"\:[ ]*?\"([0-9\.]*)\"\,/", $ver, $match) == 1) {
          $mageVersion = $match[1];
          $this->cartVars['dbVersion'] = $mageVersion;
          unset($match);
        }
      }

      if (isset($db['initStatements']) && $db['initStatements'] != '') {
        $this->cartVars['dbCharSet'] = $db['initStatements'];
      }

      $this->imagesDir              = $imagesDir;
      $this->categoriesImagesDir    = $this->imagesDir . 'catalog/category/';
      $this->productsImagesDir      = $this->imagesDir . 'catalog/product/';
      $this->manufacturersImagesDir = $this->imagesDir;

      return;
    }

    // MAGENTO 1.X
    /**
     * @var SimpleXMLElement
     */
    $config = simplexml_load_file(M1_STORE_BASE_DIR . 'app/etc/local.xml');
    $statuses = simplexml_load_file(M1_STORE_BASE_DIR . 'app/code/core/Mage/Sales/etc/config.xml');

    $version =  $statuses->modules->Mage_Sales->version;

    $result = array();

    if (version_compare($version, '1.4.0.25') < 0) {
      $statuses = $statuses->global->sales->order->statuses;
      foreach ($statuses->children() as $status) {
        $result[$status->getName()] = (string)$status->label;
      }
    }

    if (@file_exists(M1_STORE_BASE_DIR . 'app/Mage.php')) {
      $ver = file_get_contents(M1_STORE_BASE_DIR . 'app/Mage.php');
      if (preg_match("/getVersionInfo[^}]+\'major\' *=> *\'(\d+)\'[^}]+\'minor\' *=> *\'(\d+)\'[^}]+\'revision\' *=> *\'(\d+)\'[^}]+\'patch\' *=> *\'(\d+)\'[^}]+}/s", $ver, $match) == 1) {
        $mageVersion = $match[1] . '.' . $match[2] . '.' . $match[3] . '.' . $match[4];
        $this->cartVars['dbVersion'] = $mageVersion;
        unset($match);
      }
    }

    $this->cartVars['orderStatus'] = $result;
    $this->cartVars['AdminUrl']    = (string)$config->admin->routers->adminhtml->args->frontName;

    $this->setHostPort((string)$config->global->resources->default_setup->connection->host);
    $this->userName = (string)$config->global->resources->default_setup->connection->username;
    $this->dbName   = (string)$config->global->resources->default_setup->connection->dbname;
    $this->password = (string)$config->global->resources->default_setup->connection->password;

    if (!$this->cartVars['dbCharSet']
      && ($charSet = (string)$config->global->resources->default_setup->connection->initStatements) != ''
    ) {
      $this->cartVars['dbCharSet'] = str_replace('SET NAMES ', '', $charSet);
    }

    $this->imagesDir              = 'media/';
    $this->categoriesImagesDir    = $this->imagesDir . 'catalog/category/';
    $this->productsImagesDir      = $this->imagesDir . 'catalog/product/';
    $this->manufacturersImagesDir = $this->imagesDir;
    @unlink(M1_STORE_BASE_DIR . 'app/etc/use_cache.ser');
  }
}

class M1_Config_Adapter_JooCart extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_JooCart constructor.
   */
  public function __construct()
  {
    @require_once M1_STORE_BASE_DIR . '/configuration.php';

    if (class_exists('JConfig')) {

      $jConfig = new JConfig();

      $this->setHostPort($jConfig->host);
      $this->dbName   = $jConfig->db;
      $this->userName = $jConfig->user;
      $this->password = $jConfig->password;

    } else {

      $this->setHostPort($mosConfig_host);
      $this->dbName   = $mosConfig_db;
      $this->userName = $mosConfig_user;
      $this->password = $mosConfig_password;
    }

    if (@file_exists(M1_STORE_BASE_DIR . '/components/com_opencart/index.php')) {
      $content = file_get_contents(M1_STORE_BASE_DIR . '/components/com_opencart/index.php');

      if (preg_match("/define\('\VERSION\'\, \'(.+)\'\)/", $content, $match)) {
        $this->cartVars['dbVersion'] = $match[1];
      }
    }

    $this->imagesDir              = 'components/com_opencart/image/';
    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;
  }
}


class M1_Config_Adapter_MijoShop extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_MijoShop constructor.
   */
  public function __construct()
  {
    @require_once M1_STORE_BASE_DIR . '/configuration.php';

    if (class_exists('JConfig')) {

      $jConfig = new JConfig();

      $this->setHostPort($jConfig->host);
      $this->dbName   = $jConfig->db;
      $this->userName = $jConfig->user;
      $this->password = $jConfig->password;

    } else {

      $this->setHostPort($mosConfig_host);
      $this->dbName   = $mosConfig_db;
      $this->userName = $mosConfig_user;
      $this->password = $mosConfig_password;
    }

    if (@file_exists(M1_STORE_BASE_DIR . 'administrator/components/com_mijoshop/mijoshop.xml')) {
      $config = @simplexml_load_file(M1_STORE_BASE_DIR . 'administrator/components/com_mijoshop/mijoshop.xml');
      if (isset($config->version)) {
        $this->cartVars['dbVersion'] = (string)$config->version;
      }
    }

    $this->imagesDir = 'components/com_mijoshop/opencart/image/';
    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;
  }
}


class M1_Config_Adapter_Cscart extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Cscart constructor.
   */
  public function __construct()
  {
    defined('IN_CSCART') || define('IN_CSCART', 1);
    defined('CSCART_DIR') || define('CSCART_DIR', M1_STORE_BASE_DIR);
    defined('AREA') || define('AREA', 1);
    defined('BOOTSTRAP') || define('BOOTSTRAP', 1);
    defined('DIR_ROOT') || define('DIR_ROOT', M1_STORE_BASE_DIR);
    define('DIR_CSCART', M1_STORE_BASE_DIR);
    define('DS', DIRECTORY_SEPARATOR);

    require_once M1_STORE_BASE_DIR . 'config.php';

    //For CS CART 1.3.x
    if (isset($db_host, $db_name, $db_user, $db_password)) {
      $this->setHostPort($db_host);
      $this->dbName = $db_name;
      $this->userName = $db_user;
      $this->password = $db_password;
    } else {
      $this->setHostPort($config['db_host']);
      $this->dbName = $config['db_name'];
      $this->userName = $config['db_user'];
      $this->password = $config['db_password'];
    }

    if (isset($images_storage_dir)) {
      $imagesDir = $images_storage_dir;
    } elseif (defined('DIR_IMAGES')) {
      $imagesDir = DIR_IMAGES;
    } else {
      $imagesDir = $config['storage']['images']['dir'] . '/' . $config['storage']['images']['prefix'];
    }

    $this->imagesDir = str_replace(M1_STORE_BASE_DIR, '', $imagesDir);

    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;

    if (defined('MAX_FILES_IN_DIR')) {
      $this->cartVars['cs_max_files_in_dir'] = MAX_FILES_IN_DIR;
    }

    if (defined('PRODUCT_VERSION')) {
      $this->cartVars['dbVersion'] = PRODUCT_VERSION;
    }
  }
}

class M1_Config_Adapter_Jigoshop extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Jigoshop constructor.
   */
  public function __construct()
  {
    $config = file_get_contents(M1_STORE_BASE_DIR . 'wp-config.php');

    preg_match('/define\s*\(\s*\'DB_NAME\',\s*\'(.+)\'\s*\)\s*;/', $config, $match);
    $this->dbName = $match[1];
    preg_match('/define\s*\(\s*\'DB_USER\',\s*\'(.+)\'\s*\)\s*;/', $config, $match);
    $this->userName = $match[1];
    preg_match('/define\s*\(\s*\'DB_PASSWORD\',\s*\'(.*)\'\s*\)\s*;/', $config, $match);
    $this->password = $match[1];
    preg_match('/define\s*\(\s*\'DB_HOST\',\s*\'(.+)\'\s*\)\s*;/', $config, $match);
    $this->setHostPort($match[1]);
    preg_match('/\$table_prefix\s*=\s*\'(.*)\'\s*;/', $config, $match);
    $this->tablePrefix = $match[1];

    $this->imagesDir = 'wp-content/uploads/';
    $this->categoriesImagesDir = $this->imagesDir;
    $this->productsImagesDir = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;

    $this->cartVars['categoriesImagesDirRelative'] = 'images/categories/';
    $this->cartVars['productsImagesDirRelative'] = 'images/products/';
  }
}

class M1_Config_Adapter_Gambio extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Gambio constructor.
   */
  public function __construct()
  {
    include(M1_STORE_BASE_DIR . '/includes/configure.php');

    $this->setHostPort(DB_SERVER);
    $this->dbName   = DB_DATABASE;
    $this->userName = DB_SERVER_USERNAME;
    $this->password = DB_SERVER_PASSWORD;

    $this->imagesDir = DIR_WS_IMAGES;
    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = DIR_WS_POPUP_IMAGES;
    $this->manufacturersImagesDir = $this->imagesDir;
  }
}

class M1_Config_Adapter_LemonStand extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_LemonStand constructor.
   */
  public function __construct()
  {
    include (M1_STORE_BASE_DIR . 'phproad/system/phpr.php');
    include (M1_STORE_BASE_DIR . 'phproad/modules/phpr/classes/phpr_securityframework.php');

    define('PATH_APP', '');

    if (phpversion() > 5) {
      eval('Phpr::$config = new MockConfig();
      Phpr::$config->set("SECURE_CONFIG_PATH", M1_STORE_BASE_DIR . "config/config.dat");
      $framework = Phpr_SecurityFramework::create();');
    }

    $configContent = $framework->get_config_content();

    $this->setHostPort($configContent['mysql_params']['host']);
    $this->dbName   = $configContent['mysql_params']['database'];
    $this->userName = $configContent['mysql_params']['user'];
    $this->password = $configContent['mysql_params']['password'];

    $this->categoriesImagesDir    = '/uploaded/thumbnails/';
    $this->productsImagesDir      = '/uploaded/';
    $this->manufacturersImagesDir = '/uploaded/thumbnails/';

    $version = $this->getCartVersionFromDb('version_str', 'core_versions', "moduleId = 'shop'");
    $this->cartVars['dbVersion'] = $version;
  }
}

class MockConfig
{
  protected $_data = array();

  /**
   * @param mixed $key   key
   * @param mixed $value value
   *
   * @return void
   */
  public function set($key, $value)
  {
    $this->_data[$key] = $value;
  }

  /**
   * @param mixed  $key     key
   * @param string $default default value
   *
   * @return string
   */
  public function get($key, $default = 'default')
  {
    return isset($this->_data[$key]) ? $this->_data[$key] : $default;
  }
}

class M1_Config_Adapter_PrestaShop extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_PrestaShop constructor.
   */
  public function __construct()
  {
    $prestaShopOnePointSeven = false;

    //for version 1.7.0.0 beta 1
    if (file_exists(M1_STORE_BASE_DIR . '/app/config/parameters.yml')) {
      $prestaShopOnePointSeven = true;

      if (file_exists(M1_STORE_BASE_DIR . 'app/cache/prod/appParameters.php')) {
        $config = @require_once M1_STORE_BASE_DIR . 'app/cache/prod/appParameters.php';
      } else {
        $config = @require_once M1_STORE_BASE_DIR . 'app/config/parameters.php';
      }

      define('_DB_SERVER_', $config['parameters']['database_host']);
      define('_DB_NAME_', $config['parameters']['database_name']);
      define('_DB_USER_', $config['parameters']['database_user']);
      define('_DB_PASSWD_', $config['parameters']['database_password']);
      define('_DB_PREFIX_', $config['parameters']['database_prefix']);

      define('_MYSQL_ENGINE_', $config['parameters']['database_engine']);
      define('_PS_CACHING_SYSTEM_', $config['parameters']['ps_caching']);
      define('_PS_CACHE_ENABLED_', $config['parameters']['ps_cache_enable']);
      define('_COOKIE_KEY_', $config['parameters']['cookie_key']);
      define('_COOKIE_IV_', $config['parameters']['cookie_iv']);
      define('_PS_CREATION_DATE_', $config['parameters']['ps_creation_date']);
    }

    $confFileOne = file_get_contents(M1_STORE_BASE_DIR . '/config/settings.inc.php');
    $confFileTwo = file_get_contents(M1_STORE_BASE_DIR . '/config/config.inc.php');

    $filesLines = array_merge(explode("\n", $confFileOne), explode("\n", $confFileTwo));

    $execute = '$currentDir = \'\';';
    define('_PS_ROOT_DIR_', M1_STORE_BASE_DIR);
    eval($execute);

    foreach ($filesLines as $line) {
      if (preg_match("/^(\s*)define\(/i", $line)) {
        if ((strpos($line, '_DB_') !== false) && !defined('_DB_') && !$prestaShopOnePointSeven) {
          eval($line);
        } elseif ((strpos($line, '_PS_IMG_DIR_') !== false) && !defined('_PS_IMG_DIR_')) {
          eval($line);
        } elseif ((strpos($line, '_PS_VERSION_') !== false) && !defined('_PS_VERSION_')) {
          eval($line);
        } elseif ((strpos($line, '_COOKIE_KEY_') !== false) && !defined('_COOKIE_KEY_')) {
          eval($line);
        }
      }
    }

    $this->setHostPort(_DB_SERVER_);
    $this->dbName = _DB_NAME_;
    $this->userName = _DB_USER_;
    $this->password = _DB_PASSWD_;
    $this->tablePrefix = _DB_PREFIX_;

    if (defined('_PS_IMG_DIR_') && defined('_PS_ROOT_DIR_')) {
      preg_match("/(\/\w+\/)$/i", _PS_IMG_DIR_, $m);
      $this->imagesDir = $m[1];
    } else {
      $this->imagesDir = 'img/';
    }

    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;

    if (defined('_PS_VERSION_')) {
      $this->cartVars['dbVersion'] = _PS_VERSION_;
    } else {
      $this->cartVars['dbVersion'] = $this->getCartVersionFromDb('value', 'configuration', 'name="PS_VERSION_DB"');
    }

    if (defined('_COOKIE_KEY_')) {
      $this->cartVars['_COOKIE_KEY_'] = _COOKIE_KEY_;
    }

    if (@$_GET['action'] == 'getconfig' && file_exists(M1_STORE_BASE_DIR . '/classes/Dispatcher.php')) {
      $dispatcherClass = file_get_contents(M1_STORE_BASE_DIR . '/classes/Dispatcher.php');

      preg_match_all("/(')([a-z_]+?_rule)('.+?)('rule'.+?')(.+?)(')/s", $dispatcherClass, $matches);

      $defaultConfig = array();

      foreach ($matches[2] as $index => $rulename) {
        $defaultConfig[$rulename] = $matches[5][$index];
      }

      $this->cartVars['defaultRoutes'] = $defaultConfig;
    }
  }
}

class M1_Config_Adapter_Woocommerce extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Woocommerce constructor.
   */
  public function __construct()
  {
    $config = file_get_contents(M1_STORE_BASE_DIR . 'wp-config.php');

    preg_match('/^\s*define\s*\(\s*\'DB_NAME\',\s*\'(.+)\'\s*\)\s*;/m', $config, $match);
    $this->dbName = $match[1];
    preg_match('/^\s*define\s*\(\s*\'DB_USER\',\s*\'(.+)\'\s*\)\s*;/m', $config, $match);
    $this->userName = $match[1];
    preg_match('/^\s*define\s*\(\s*\'DB_PASSWORD\',\s*\'(.*)\'\s*\)\s*;/m', $config, $match);
    $this->password = stripslashes($match[1]);
    preg_match('/^\s*define\s*\(\s*\'DB_HOST\',\s*\'(.+)\'\s*\)\s*;/m', $config, $match);
    $this->setHostPort($match[1]);
    preg_match('/^\s*\$table_prefix\s*=\s*\'(.*)\'\s*;/m', $config, $match);
    $this->tablePrefix = $match[1];
    preg_match('/^\s*define\s*\(\s*\'WPLANG\',\s*\'(.+)\'\s*\)\s*;/m', $config, $match);
    $this->languageIso2 = isset($match[1]) ? $match[1] : 'EN';

    preg_match('/^\s*define\s*\(\s*\'DB_CHARSET\',\s*\'(.+)\'\s*\)\s*;/m', $config, $match);
    if (isset($match[1])) {
      $this->cartVars['dbCharSet'] = $match[1];
    }

    $version = $this->getCartVersionFromDb('option_value', 'options', "option_name = 'woocommerce_version'");
    if ($version != '') {
      $this->cartVars['dbVersion'] = $version;
    }

    $this->imagesDir = 'wp-content/uploads/';
    $this->categoriesImagesDir = $this->imagesDir;
    $this->productsImagesDir = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;

    $this->cartVars['categoriesImagesDirRelative'] = 'images/categories/';
    $this->cartVars['productsImagesDirRelative'] = 'images/products/';
  }
}



class M1_Config_Adapter_DrupalCommerce extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_DrupalCommerce constructor.
   */
  public function __construct()
  {
    @include_once M1_STORE_BASE_DIR . "sites/default/settings.php";

    $url = $databases['default']['default'];

    $url['username'] = urldecode($url['username']);
    $url['password'] = isset($url['password']) ? urldecode($url['password']) : '';
    $url['host'] = urldecode($url['host']);
    $url['database'] = urldecode($url['database']);
    if (isset($url['port'])) {
      $url['host'] = $url['host'] .':'. $url['port'];
    }

    $this->setHostPort($url['host']);
    $this->dbName   = ltrim($url['database'], '/');
    $this->userName = $url['username'];
    $this->password = $url['password'];

    $this->imagesDir = '/sites/default/files/';
    if (!@file_exists(M1_STORE_BASE_DIR . $this->imagesDir)) {
      $this->imagesDir = '/files';
    }

    $fileInfo = M1_STORE_BASE_DIR . '/sites/all/modules/commerce/commerce.info';
    if (@file_exists($fileInfo)) {
      $str = file_get_contents($fileInfo);
      if (preg_match('/version\s+=\s+".+-(.+)"/', $str, $match) != 0) {
        $this->cartVars['dbVersion'] = $match[1];
        unset($match);
      }
    }

    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;
  }
}

class M1_Config_Adapter_MiwoShop extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_MiwoShop constructor.
   */
  public function __construct()
  {
    $config = file_get_contents(M1_STORE_BASE_DIR . 'wp-config.php');

    preg_match('/define\s*\(\s*\'DB_NAME\',\s*\'(.+)\'\s*\)\s*;/', $config, $match);
    $this->dbName = $match[1];
    preg_match('/define\s*\(\s*\'DB_USER\',\s*\'(.+)\'\s*\)\s*;/', $config, $match);
    $this->userName = $match[1];
    preg_match('/define\s*\(\s*\'DB_PASSWORD\',\s*\'(.*)\'\s*\)\s*;/', $config, $match);
    $this->password = $match[1];
    preg_match('/define\s*\(\s*\'DB_HOST\',\s*\'(.+)\'\s*\)\s*;/', $config, $match);
    $this->setHostPort($match[1]);
    preg_match('/\$table_prefix\s*=\s*\'(.*)\'\s*;/', $config, $match);
    $this->tablePrefix = $match[1];

    preg_match('/define\s*\(\s*\'DB_CHARSET\',\s*\'(.+)\'\s*\)\s*;/', $config, $match);
    if (isset($match[1])) {
      $this->cartVars['dbCharSet'] = $match[1];
    }

    $moduleFile = file_get_contents(M1_STORE_BASE_DIR . 'wp-content/plugins/miwoshop/miwoshop.php');
    if (preg_match('/Version:\s?([\d\.]+)/', $moduleFile, $match)) {
      $this->cartVars['dbVersion'] = $match[1];
    }

    $this->imagesDir = 'wp-content/plugins/miwoshop/site/opencart/image/';
    $this->categoriesImagesDir = $this->imagesDir;
    $this->productsImagesDir = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;

    $this->cartVars['categoriesImagesDirRelative'] = 'images/categories/';
    $this->cartVars['productsImagesDirRelative'] = 'images/products/';
  }
}



class M1_Config_Adapter_Zencart137 extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Zencart137 constructor.
   */
  public function __construct()
  {
    $currentDir = getcwd();

    chdir(M1_STORE_BASE_DIR);

    @require_once M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'configure.php';

    chdir($currentDir);

    $this->imagesDir = defined('DIR_WS_IMAGES') ? DIR_WS_IMAGES : '/images';

    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;

    if (defined('DIR_WS_PRODUCT_IMAGES')) {
      $this->productsImagesDir = DIR_WS_PRODUCT_IMAGES;
    }

    if (defined('DIR_WS_ORIGINAL_IMAGES')) {
      $this->productsImagesDir = DIR_WS_ORIGINAL_IMAGES;
    }

    $this->manufacturersImagesDir = $this->imagesDir;

    $this->setHostPort(DB_SERVER);
    $this->userName  = DB_SERVER_USERNAME;
    $this->password  = DB_SERVER_PASSWORD;
    $this->dbName    = DB_DATABASE;

    if (defined('DB_CHARSET')) {
      $this->cartVars['dbCharSet'] = DB_CHARSET;
    }

    if (@file_exists(M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'version.php')) {
       @require_once M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'version.php';
      $major = PROJECT_VERSION_MAJOR;
      $minor = PROJECT_VERSION_MINOR;
      if (defined('EXPECTED_DATABASE_VERSION_MAJOR') && EXPECTED_DATABASE_VERSION_MAJOR != '') {
        $major = EXPECTED_DATABASE_VERSION_MAJOR;
      }

      if (defined('EXPECTED_DATABASE_VERSION_MINOR') && EXPECTED_DATABASE_VERSION_MINOR != '') {
        $minor = EXPECTED_DATABASE_VERSION_MINOR;
      }

      if ($major != '' && $minor != '') {
        $this->cartVars['dbVersion'] = $major . '.' . $minor;
      }
    }
  }
}



class M1_Config_Adapter_LiteCart extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_LiteCart constructor.
   */
  public function __construct()
  {
    $config = file_get_contents(M1_STORE_BASE_DIR . '/includes/config.inc.php');
    preg_match("/define\(\'DB_DATABASE\', \'(.+)\'\);/", $config, $match);
    $this->dbName   = $match[1];
    preg_match("/define\(\'DB_USERNAME\', \'(.+)\'\);/", $config, $match);
    $this->userName = $match[1];
    preg_match("/define\(\'DB_PASSWORD\', \'(.*)\'\);/", $config, $match);
    $this->password = $match[1];
    preg_match("/define\(\'DB_SERVER\', \'(.+)\'\);/", $config, $match);
    $this->setHostPort( $match[1] );

    preg_match("/define\(\'WS_DIR_IMAGES\',\s+WS_DIR_HTTP_HOME \. \'(.+)\'\);/", $config, $match);
    $this->imagesDir = $match[1];

    $this->categoriesImagesDir    = $this->imagesDir . 'categories/';
    $this->productsImagesDir      = $this->imagesDir . 'products/';
    $this->manufacturersImagesDir = $this->imagesDir . 'manufacturers/';
  }
}

class M1_Config_Adapter_Xtcommerce extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Xtcommerce constructor.
   */
  public function __construct()
  {
    define('_VALID_CALL', 'TRUE');
    define('_SRV_WEBROOT', 'TRUE');
    require_once M1_STORE_BASE_DIR . 'conf' . DIRECTORY_SEPARATOR . 'config.php';
    require_once M1_STORE_BASE_DIR . 'conf' . DIRECTORY_SEPARATOR . 'paths.php';

    $this->setHostPort(_SYSTEM_DATABASE_HOST);
    $this->dbName = _SYSTEM_DATABASE_DATABASE;
    $this->userName = _SYSTEM_DATABASE_USER;
    $this->password = _SYSTEM_DATABASE_PWD;
    $this->imagesDir = _SRV_WEB_IMAGES;
    $this->tablePrefix = DB_PREFIX . '_';

    $version = $this->getCartVersionFromDb('config_value', 'config', "config_key = '_SYSTEM_VERSION'");
    if ($version != '') {
      $this->cartVars['dbVersion'] = $version;
    }

    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;
  }
}


class M1_Config_Adapter_Opencart extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Opencart constructor.
   */
  public function __construct()
  {
    include_once(M1_STORE_BASE_DIR . '/config.php');

    if (defined('DB_HOST')) {
      $this->setHostPort(DB_HOST);
    } else {
      $this->setHostPort(DB_HOSTNAME);
    }

    if (defined('DB_USER')) {
      $this->userName = DB_USER;
    } else {
      $this->userName = DB_USERNAME;
    }

    $this->password = DB_PASSWORD;

    if (defined('DB_NAME')) {
      $this->dbName = DB_NAME;
    } else {
      $this->dbName = DB_DATABASE;
    }

    $indexFileContent = '';
    $startupFileContent = '';

    if (@file_exists(M1_STORE_BASE_DIR . '/index.php')) {
      $indexFileContent = file_get_contents(M1_STORE_BASE_DIR . '/index.php');
    }

    if (@file_exists(M1_STORE_BASE_DIR . '/system/startup.php')) {
      $startupFileContent = file_get_contents(M1_STORE_BASE_DIR . '/system/startup.php');
    }

    if (preg_match("/define\('\VERSION\'\, \'(.+)\'\)/", $indexFileContent, $match) == 0) {
      preg_match("/define\('\VERSION\'\, \'(.+)\'\)/", $startupFileContent, $match);
    }

    if (count($match) > 0) {
      $this->cartVars['dbVersion'] = $match[1];
      unset($match);
    }

    $this->imagesDir              = 'image/';
    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;

    $this->cartVars['downloadDir']  = DIR_DOWNLOAD;
    $this->cartVars['uploadDir'] = DIR_UPLOAD;
    $this->cartVars['dirSeparator'] = DIRECTORY_SEPARATOR;
  }
}

class M1_Config_Adapter_LoadedCommerce extends M1_Config_Adapter_Oscommerce22ms2
{
}

class M1_Config_Adapter_AceShop extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_AceShop constructor.
   */
  public function __construct()
  {
    @require_once M1_STORE_BASE_DIR . '/configuration.php';

    if (class_exists('JConfig')) {

      $jConfig = new JConfig();

      $this->setHostPort($jConfig->host);
      $this->dbName   = $jConfig->db;
      $this->userName = $jConfig->user;
      $this->password = $jConfig->password;

    } else {

      $this->setHostPort($mosConfig_host);
      $this->dbName   = $mosConfig_db;
      $this->userName = $mosConfig_user;
      $this->password = $mosConfig_password;

    }

    if (@file_exists(M1_STORE_BASE_DIR . 'administrator/components/com_aceshop/aceshop.xml')) {
      $config = @simplexml_load_file(M1_STORE_BASE_DIR . 'administrator/components/com_aceshop/aceshop.xml');
      if (isset($config->version)) {
        $this->cartVars['dbVersion'] = (string)$config->version;
      }
    }

    $this->imagesDir = 'components/com_aceshop/opencart/image/';
    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;
  }
}


class M1_Config_Adapter_Shopscript extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Shopscript constructor.
   */
  public function __construct()
  {
    if (@file_exists(M1_STORE_BASE_DIR . 'kernel/wbs.xml')) {

      $config = simplexml_load_file(M1_STORE_BASE_DIR . 'kernel/wbs.xml');

      $dbKey = (string)$config->FRONTEND['dbkey'];

      $config = simplexml_load_file(M1_STORE_BASE_DIR . 'dblist' . '/' . strtoupper($dbKey) . '.xml');

      $host = (string)$config->DBSETTINGS['SQLSERVER'];

      $this->setHostPort($host);
      $this->dbName = (string)$config->DBSETTINGS['DB_NAME'];
      $this->userName = (string)$config->DBSETTINGS['DB_USER'];
      $this->password = (string)$config->DBSETTINGS['DB_PASSWORD'];

      $this->imagesDir = 'published/publicdata/' . strtoupper($dbKey) . '/attachments/SC/products_pictures';
      $this->categoriesImagesDir = $this->imagesDir;
      $this->productsImagesDir = $this->imagesDir;
      $this->manufacturersImagesDir = $this->imagesDir;

      if (isset($config->VERSIONS['SYSTEM'])) {
        $this->cartVars['dbVersion'] = (string)$config->VERSIONS['SYSTEM'];
      }

      if ($charSet = $this->getCharsetFromDb()) {
        $this->cartVars['dbCharSet'] = $charSet;
      }
    } elseif (@file_exists(M1_STORE_BASE_DIR . 'cfg/connect.inc.php')) {

      $config = file_get_contents(M1_STORE_BASE_DIR . 'cfg/connect.inc.php');

      preg_match("/define\(\'DB_NAME\', \'(.+)\'\);/", $config, $match);
      $this->dbName = $match[1];
      preg_match("/define\(\'DB_USER\', \'(.+)\'\);/", $config, $match);
      $this->userName = $match[1];
      preg_match("/define\(\'DB_PASS\', \'(.*)\'\);/", $config, $match);
      $this->password = $match[1];
      preg_match("/define\(\'DB_HOST\', \'(.+)\'\);/", $config, $match);
      $this->setHostPort( $match[1] );

      $this->imagesDir = 'products_pictures/';
      $this->categoriesImagesDir    = $this->imagesDir;
      $this->productsImagesDir      = $this->imagesDir;
      $this->manufacturersImagesDir = $this->imagesDir;

      $generalInc = file_get_contents(M1_STORE_BASE_DIR . 'cfg/general.inc.php');

      preg_match("/define\(\'CONF_CURRENCY_ISO3\', \'(.+)\'\);/", $generalInc, $match);
      if (count($match) != 0) {
        $this->cartVars['iso3Currency'] = $match[1];
      }

      preg_match("/define\(\'CONF_CURRENCY_ID_LEFT\', \'(.+)\'\);/", $generalInc, $match);
      if (count($match) != 0) {
        $this->cartVars['currencySymbolLeft'] = $match[1];
      }

      preg_match("/define\(\'CONF_CURRENCY_ID_RIGHT\', \'(.+)\'\);/", $generalInc, $match);
      if (count($match) != 0) {
        $this->cartVars['currencySymbolRight'] = $match[1];
      }
    }
  }
}

class M1_Config_Adapter_Hhgmultistore extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Hhgmultistore constructor.
   */
  public function __construct()
  {
    defined('SITE_PATH') || define('SITE_PATH', '');
    defined('WEB_PATH') || define('WEB_PATH', '');
    defined('DS') || define('DS', DIRECTORY_SEPARATOR);
    require_once M1_STORE_BASE_DIR . 'core/config/configure.php';
    require_once M1_STORE_BASE_DIR . 'core/config/paths.php';

    $baseDir = '/store_files/1/';
    $this->imagesDir = $baseDir . DIR_WS_IMAGES;

    $this->categoriesImagesDir = $baseDir . DIR_WS_CATEGORIE_IMAGES;
    $this->productsImagesDirs['info'] = $baseDir . DIR_WS_PRODUCT_INFO_IMAGES;
    $this->productsImagesDirs['org'] = $baseDir . DIR_WS_PRODUCT_ORG_IMAGES;
    $this->productsImagesDirs['thumb'] = $baseDir . DIR_WS_PRODUCT_THUMBNAIL_IMAGES;
    $this->productsImagesDirs['popup'] = $baseDir . DIR_WS_PRODUCT_POPUP_IMAGES;

    $this->manufacturersImagesDirs['img'] = $baseDir . DIR_WS_MANUFACTURERS_IMAGES;
    $this->manufacturersImagesDirs['org'] = $baseDir . DIR_WS_MANUFACTURERS_ORG_IMAGES;

    $this->host     = DB_SERVER;
    $this->userName = DB_SERVER_USERNAME;
    $this->password = DB_SERVER_PASSWORD;
    $this->dbName   = DB_DATABASE;

    if (@file_exists(M1_STORE_BASE_DIR . '/core/config/conf.hhg_startup.php')) {
      $ver = file_get_contents(M1_STORE_BASE_DIR . '/core/config/conf.hhg_startup.php');
      if (preg_match('/PROJECT_VERSION.+\((.+)\)\'\)/', $ver, $match) != 0) {
        $this->cartVars['dbVersion'] = $match[1];
        unset($match);
      }
    }
  }
}


class M1_Config_Adapter_Merchium extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Merchium constructor.
   */
  public function __construct()
  {
    global $config;

    $this->setHostPort($config['db_host']);
    $this->dbName = $config['db_name'];
    $this->userName = $config['db_user'];
    $this->password = $config['db_password'];

    $imagesDir = $config['storage']['images']['dir'] . $config['storage']['images']['prefix'];

    $this->imagesDir = str_replace(M1_STORE_BASE_DIR, '', $imagesDir);

    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;

    if (defined('MAX_FILES_IN_DIR')) {
      $this->cartVars['cs_max_files_in_dir'] = MAX_FILES_IN_DIR;
    }

    if (defined('PRODUCT_VERSION')) {
      $this->cartVars['dbVersion'] = PRODUCT_VERSION;
    }
  }
}

class M1_Config_Adapter_Tomatocart extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Tomatocart constructor.
   */
  public function __construct()
  {
    $config = file_get_contents(M1_STORE_BASE_DIR . 'includes/configure.php');
    preg_match("/define\(\'DB_DATABASE\', \'(.+)\'\);/", $config, $match);
    $this->dbName   = $match[1];
    preg_match("/define\(\'DB_SERVER_USERNAME\', \'(.+)\'\);/", $config, $match);
    $this->userName = $match[1];
    preg_match("/define\(\'DB_SERVER_PASSWORD\', \'(.*)\'\);/", $config, $match);
    $this->password = $match[1];
    preg_match("/define\(\'DB_SERVER\', \'(.+)\'\);/", $config, $match);
    $this->setHostPort( $match[1] );

    preg_match("/define\(\'DIR_WS_IMAGES\', \'(.+)\'\);/", $config, $match);
    $this->imagesDir = $match[1];

    $this->categoriesImagesDir    = $this->imagesDir . 'categories/';
    $this->productsImagesDir      = $this->imagesDir . 'products/';
    $this->manufacturersImagesDir = $this->imagesDir . 'manufacturers/';
    if (@file_exists(M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'application_top.php')) {
      $conf = file_get_contents(M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'application_top.php');
      preg_match("/define\('PROJECT_VERSION.*/", $conf, $match);

      if (isset($match[0]) && !empty($match[0])) {
        preg_match("/\d.*/", $match[0], $project);
        if (isset($project[0]) && !empty($project[0])) {
          $version = $project[0];
          $version = str_replace(array(' ','-','_',"'",');'), '', $version);
          if ($version != '') {
            $this->cartVars['dbVersion'] = strtolower($version);
          }
        }
      } else {
        //if another version
        if (@file_exists(M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'version.php')) {
          @require_once M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'version.php';
          if (defined('PROJECT_VERSION') && PROJECT_VERSION != '') {
            $version = PROJECT_VERSION;
            preg_match("/\d.*/", $version, $vers);
            if (isset($vers[0]) && !empty($vers[0])) {
              $version = $vers[0];
              $version = str_replace(array(' ','-','_'), '', $version);
              if ($version != '') {
                $this->cartVars['dbVersion'] = strtolower($version);
              }
            }
          }
        }
      }
    }
  }
}



class M1_Config_Adapter_Cubecart extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Cubecart constructor.
   */
  public function __construct()
  {
    include_once(M1_STORE_BASE_DIR . 'includes/global.inc.php');

    $this->setHostPort($glob['dbhost']);
    $this->dbName = $glob['dbdatabase'];
    $this->userName = $glob['dbusername'];
    $this->password = $glob['dbpassword'];

    if (isset($glob['charset']) && $glob['charset'] != '') {
      $this->cartVars['dbCharSet'] = $glob['charset'];
    }

    $this->imagesDir = 'images';
    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;
    $dirHandle = opendir(M1_STORE_BASE_DIR . 'language/');

    //settings for cube 5
    $languages = array();
    while ($dirEntry = readdir($dirHandle)) {
      $info = pathinfo($dirEntry);
      $xmlflag = false;

      if (isset($info['extension'])) {
        $xmlflag = strtoupper($info['extension']) != 'XML' ? true : false;
      }

      if (is_dir(M1_STORE_BASE_DIR . 'language/' . $dirEntry)
          || $dirEntry == '.'
          || $dirEntry == '..'
          || strpos($dirEntry, '_') !== false
          || $xmlflag
      ) {
        continue;
      }

      $configXml = simplexml_load_file(M1_STORE_BASE_DIR . 'language/' . $dirEntry);
      if ($configXml->info->title) {
        $lang['name'] = (string)$configXml->info->title;
        $lang['code'] = substr((string)$configXml->info->code, 0, 2);
        $lang['locale'] = substr((string)$configXml->info->code, 0, 2);
        $lang['currency'] = (string)$configXml->info->default_currency;
        $lang['fileName'] = str_replace('.xml', '', $dirEntry);
        $languages[] = $lang;
      }
    }

    if (!empty($languages)) {
      $this->cartVars['languages'] = $languages;
    }

    if (@file_exists(M1_STORE_BASE_DIR  . 'ini.inc.php')) {
      $conf = file_get_contents (M1_STORE_BASE_DIR . 'ini.inc.php');
      preg_match("/ini\['ver'\].*/", $conf, $match);
      if (isset($match[0]) && !empty($match[0])) {
        preg_match("/\d.*/", $match[0], $project);
        if (isset($project[0]) && !empty($project[0])) {
          $version = $project[0];
          $version = str_replace(array(' ','-','_',"'",');',';',')'), '', $version);
          if ($version != '') {
            $this->cartVars['dbVersion'] = strtolower($version);
          }
        }
      } else {
        preg_match("/define\('CC_VERSION.*/", $conf, $match);
        if (isset($match[0]) && !empty($match[0])) {
          preg_match("/\d.*/", $match[0], $project);
          if (isset($project[0]) && !empty($project[0])) {
            $version = $project[0];
            $version = str_replace(array(' ','-','_',"'",');',';',')'), '', $version);
            if ($version != '') {
              $this->cartVars['dbVersion'] = strtolower($version);
            }
          }
        }
      }
    } elseif (@file_exists(M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'ini.inc.php')) {
      $conf = file_get_contents (M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'ini.inc.php');
      preg_match("/ini\['ver'\].*/", $conf, $match);
      if (isset($match[0]) && !empty($match[0])) {
        preg_match("/\d.*/", $match[0], $project);
        if (isset($project[0]) && !empty($project[0])) {
          $version = $project[0];
          $version = str_replace(array(' ','-','_',"'",');',';',')'), '', $version);
          if ($version != '') {
            $this->cartVars['dbVersion'] = strtolower($version);
          }
        }
      } else {
        preg_match("/define\('CC_VERSION.*/", $conf, $match);
        if (isset($match[0]) && !empty($match[0])) {
          preg_match("/\d.*/", $match[0], $project);
          if (isset($project[0]) && !empty($project[0])) {
            $version = $project[0];
            $version = str_replace(array(' ','-','_',"'",');',';',')'), '', $version);
            if ($version != '') {
              $this->cartVars['dbVersion'] = strtolower($version);
            }
          }
        }
      }
    }
  }
}

class miSettings
{
  public $arr;

  /**
   * @return miSettings|null
   */
  public function singleton()
  {
    static $instance = null;
    if ($instance == null) {
      $instance = new miSettings();
    }

    return $instance;
  }

  /**
   * @param array $arr array
   *
   * @return void
   */
  public function setArray($arr)
  {
    $this->arr[] = $arr;
  }

  /**
   * @return mixed
   */
  public function getArray()
  {
    return $this->arr;
  }
}

class M1_Config_Adapter_Summercart3 extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Summercart3 constructor.
   */
  public function __construct()
  {
    @include_once M1_STORE_BASE_DIR . 'include/miphpf/Config.php';

    $instance = miSettings::singleton();

    $data = $instance->getArray();

    $this->setHostPort($data[0]['MI_DEFAULT_DB_HOST']);
    $this->dbName   = $data[0]['MI_DEFAULT_DB_NAME'];
    $this->userName = $data[0]['MI_DEFAULT_DB_USER'];
    $this->password = $data[0]['MI_DEFAULT_DB_PASS'];
    $this->imagesDir = '/userfiles/';

    $this->categoriesImagesDir    = $this->imagesDir . 'categoryimages';
    $this->productsImagesDir      = $this->imagesDir . 'productimages';
    $this->manufacturersImagesDir = $this->imagesDir . 'manufacturer';

    if (@file_exists(M1_STORE_BASE_DIR . '/include/VERSION')) {
      $indexFileContent = file_get_contents(M1_STORE_BASE_DIR . '/include/VERSION');
      $this->cartVars['dbVersion'] = trim($indexFileContent);
    }
  }
}



class M1_Config_Adapter_HikaShop extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_HikaShop constructor.
   */
  public function __construct()
  {
    @require_once M1_STORE_BASE_DIR . '/configuration.php';

    if (class_exists('JConfig')) {

      $jConfig = new JConfig();

      $this->setHostPort($jConfig->host);
      $this->dbName   = $jConfig->db;
      $this->userName = $jConfig->user;
      $this->password = $jConfig->password;

    } else {

      $this->setHostPort($mosConfig_host);
      $this->dbName   = $mosConfig_db;
      $this->userName = $mosConfig_user;
      $this->password = $mosConfig_password;
    }

    if (@file_exists(M1_STORE_BASE_DIR . 'administrator/components/com_hikashop/hikashop_j3.xml')) {
      $config = @simplexml_load_file(M1_STORE_BASE_DIR . 'administrator/components/com_hikashop/hikashop_j3.xml');
      if (isset($config->version)) {
        $this->cartVars['dbVersion'] = (string)$config->version;
      }
    }

    $this->imagesDir = 'images/com_hikashop/upload/';
    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;
  }
}


class M1_Config_Adapter_Ubercart extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Ubercart constructor.
   */
  public function __construct()
  {
    @include_once M1_STORE_BASE_DIR . 'sites/default/settings.php';

    if (@file_exists(M1_STORE_BASE_DIR . 'sites/all/modules/ubercart/uc_store/includes/coder_review_uc3x.inc')
      || @file_exists(M1_STORE_BASE_DIR . 'modules/ubercart/uc_store/includes/coder_review_uc3x.inc')
    ) {

      /** @var array $databases */
      $url = $databases['default']['default'];

      $url['username'] = urldecode($url['username']);
      $url['password'] = isset($url['password']) ? urldecode($url['password']) : '';
      $url['host'] = urldecode($url['host']);
      $url['database'] = urldecode($url['database']);
      if (isset($url['port'])) {
        $url['host'] = $url['host'] . ':' . $url['port'];
      }

      $this->setHostPort($url['host']);
      $this->dbName   = ltrim($url['database'], '/');
      $this->userName = $url['username'];
      $this->password = $url['password'];

      $fileInfo = M1_STORE_BASE_DIR . '/sites/all/modules/ubercart/uc_cart/uc_cart.info';
      if (@file_exists($fileInfo)) {
        $str = file_get_contents($fileInfo);
        if (preg_match('/version\s+=\s+".+-(.+)"/', $str, $match) != 0) {
          $this->cartVars['dbVersion'] = $match[1];
          unset($match);
        }
      }

    } elseif (@file_exists(M1_STORE_BASE_DIR . '/modules/ubercart/uc_cart/uc_cart.info')) {

      $url = parse_url($db_url);

      $url['user'] = urldecode($url['user']);
      // Test if database url has a password.
      $url['pass'] = isset($url['pass']) ? urldecode($url['pass']) : '';
      $url['host'] = urldecode($url['host']);
      $url['path'] = urldecode($url['path']);
      // Allow for non-standard MySQL port.
      if (isset($url['port'])) {
        $url['host'] = $url['host'] . ':' . $url['port'];
      }

      $this->setHostPort($url['host']);
      $this->dbName = ltrim($url['path'], '/');
      $this->userName = $url['user'];
      $this->password = $url['pass'];

      $this->cartVars['dbVersion'] = 1;

    }

    $this->imagesDir = '/sites/default/files/';
    if (!@file_exists(M1_STORE_BASE_DIR . $this->imagesDir)) {
      $this->imagesDir = '/files';
    }

    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;
  }
}



class M1_Config_Adapter_Xcart extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Xcart constructor.
   */
  public function __construct()
  {
    if ((@file_exists(M1_STORE_BASE_DIR . '/includes/config.inc.php'))) {
      $config = file_get_contents(M1_STORE_BASE_DIR . '/includes/config.inc.php');
      preg_match("/define\(\'DB_DATABASE\', \'(.+)\'\);/", $config, $match);
      $this->dbName   = $match[1];
      preg_match("/define\(\'DB_USERNAME\', \'(.+)\'\);/", $config, $match);
      $this->userName = $match[1];
      preg_match("/define\(\'DB_PASSWORD\', \'(.*)\'\);/", $config, $match);
      $this->password = $match[1];
      preg_match("/define\(\'DB_SERVER\', \'(.+)\'\);/", $config, $match);
      $this->setHostPort( $match[1] );

      preg_match("/define\(\'WS_DIR_IMAGES\',\s+WS_DIR_HTTP_HOME \. \'(.+)\'\);/", $config, $match);
      $this->imagesDir = $match[1];

      $this->categoriesImagesDir    = $this->imagesDir . 'categories/';
      $this->productsImagesDir      = $this->imagesDir . 'products/';
      $this->manufacturersImagesDir = $this->imagesDir . 'manufacturers/';
    } elseif ((@file_exists(M1_STORE_BASE_DIR . '/etc/config.php'))) {

      $config = parse_ini_file(M1_STORE_BASE_DIR . '/etc/config.php');

      $this->host = $config['hostspec'];
      $this->setHostPort($config['hostspec']);
      $this->userName = $config['username'];
      $this->password = $config['password'];
      $this->dbName = $config['database'];
      $this->tablePrefix = $config['table_prefix'];

      if (($version = $this->getCartVersionFromDb('value', 'config', "name = 'version'")) != '') {
        $this->cartVars['dbVersion'] = $version;
      }

      $this->imagesDir = '/images';
      $this->categoriesImagesDir = $this->imagesDir . '/category';
      $this->productsImagesDir = $this->imagesDir . '/product';
      $this->manufacturersImagesDir = $this->imagesDir;

    } else {

      define('XCART_START', 1);

      $config = file_get_contents(M1_STORE_BASE_DIR . 'config.php');

      preg_match('/\$sql_host.+\'(.+)\';/', $config, $match);
      $this->setHostPort($match[1]);
      preg_match('/\$sql_user.+\'(.+)\';/', $config, $match);
      $this->userName = $match[1];
      preg_match('/\$sql_db.+\'(.+)\';/', $config, $match);
      $this->dbName = $match[1];
      preg_match('/\$sql_password.+\'(.*)\';/', $config, $match);
      $this->password = $match[1];

      preg_match('/\$sql_charset.+\'(.*)\';/', $config, $match);
      if (isset($match[1]) && $match[1] != '') {
        $this->cartVars['dbCharSet'] = $match[1];
      }

      $this->imagesDir = 'images/';
      $this->categoriesImagesDir = $this->imagesDir;
      $this->productsImagesDir = $this->imagesDir;
      $this->manufacturersImagesDir = $this->imagesDir;

      if (@file_exists(M1_STORE_BASE_DIR . 'VERSION')) {
        $version = file_get_contents(M1_STORE_BASE_DIR . 'VERSION');
        $this->cartVars['dbVersion'] = preg_replace('/(Version| |\\n)/', '', $version);
      }
    }

    if (@file_exists(M1_STORE_BASE_DIR . 'classes/XLite.php')) {
      $xlite = file_get_contents(M1_STORE_BASE_DIR . 'classes/XLite.php');
      preg_match('/XC\_VERSION[ ]*=[ ]*(\'|\")([0-9.]+)(\'|\")/', $xlite, $matches);
      if (isset($matches[2])) {
        $this->cartVars['dbVersion'] = $matches[2];
      }
    }
  }
}

class M1_Config_Adapter_Arastta extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Arastta constructor.
   */
  public function __construct()
  {
    include_once(M1_STORE_BASE_DIR . '/config.php');

    if (defined('DB_HOST')) {
      $this->setHostPort(DB_HOST);
    } else {
      $this->setHostPort(DB_HOSTNAME);
    }

    if (defined('DB_USER')) {
      $this->userName = DB_USER;
    } else {
      $this->userName = DB_USERNAME;
    }

    $this->password = DB_PASSWORD;

    if (defined('DB_NAME')) {
      $this->dbName = DB_NAME;
    } else {
      $this->dbName = DB_DATABASE;
    }

    $indexFileContent = '';
    $startupFileContent = '';

    if (@file_exists(M1_STORE_BASE_DIR . '/index.php')) {
      $indexFileContent = file_get_contents(M1_STORE_BASE_DIR . '/index.php');
    }

    if (@file_exists(M1_STORE_BASE_DIR . '/system/startup.php')) {
      $startupFileContent = file_get_contents(M1_STORE_BASE_DIR . '/system/startup.php');
    }

    if (preg_match("/define\('\VERSION\'\, \'(.+)\'\)/", $indexFileContent, $match) == 0) {
      preg_match("/define\('\VERSION\'\, \'(.+)\'\)/", $startupFileContent, $match);
    }

    if (count($match) > 0) {
      $this->cartVars['dbVersion'] = $match[1];
      unset($match);
    }

    $this->imagesDir              = 'image/';
    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;

    if (is_dir(M1_STORE_BASE_DIR . '/download')) {
      $this->cartVars['downloadDir']  = M1_STORE_BASE_DIR . '/download';
    }
  }
}

class M1_Config_Adapter_Zoey extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Zoey constructor.
   */
  public function __construct()
  {
    // Zoey 1.X
    /**
     * @var SimpleXMLElement
     */
    $config = simplexml_load_file(M1_STORE_BASE_DIR . 'app/etc/local.xml');
    $statuses = simplexml_load_file(M1_STORE_BASE_DIR . 'app/code/core/Mage/Sales/etc/config.xml');

    $version =  $statuses->modules->Mage_Sales->version;

    $result = array();

    if (version_compare($version, '1.4.0.25') < 0) {
      $statuses = $statuses->global->sales->order->statuses;
      foreach ($statuses->children() as $status) {
        $result[$status->getName()] = (string)$status->label;
      }
    }

    if (@file_exists(M1_STORE_BASE_DIR . 'app/Mage.php')) {
      $ver = file_get_contents(M1_STORE_BASE_DIR . 'app/Mage.php');
      if (preg_match("/getVersionInfo[^}]+\'major\' *=> *\'(\d+)\'[^}]+\'minor\' *=> *\'(\d+)\'[^}]+\'revision\' *=> *\'(\d+)\'[^}]+\'patch\' *=> *\'(\d+)\'[^}]+}/s", $ver, $match) == 1) {
        $mageVersion = $match[1] . '.' . $match[2] . '.' . $match[3] . '.' . $match[4];
        $this->cartVars['dbVersion'] = $mageVersion;
        unset($match);
      }
    }

    $this->cartVars['orderStatus'] = $result;
    $this->cartVars['AdminUrl']    = (string)$config->admin->routers->adminhtml->args->frontName;

    $this->setHostPort((string)$config->global->resources->default_setup->connection->host);
    $this->userName = (string)$config->global->resources->default_setup->connection->username;
    $this->dbName   = (string)$config->global->resources->default_setup->connection->dbname;
    $this->password = (string)$config->global->resources->default_setup->connection->password;

    if (!$this->cartVars['dbCharSet']
      && ($charSet = (string)$config->global->resources->default_setup->connection->initStatements) != ''
    ) {
      $this->cartVars['dbCharSet'] = str_replace('SET NAMES ', '', $charSet);
    }

    $this->imagesDir              = 'media/';
    $this->categoriesImagesDir    = $this->imagesDir . 'catalog/category/';
    $this->productsImagesDir      = $this->imagesDir . 'catalog/product/';
    $this->manufacturersImagesDir = $this->imagesDir;
    @unlink(M1_STORE_BASE_DIR . 'app/etc/use_cache.ser');
  }
}


class M1_Config_Adapter_Sunshop4 extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Sunshop4 constructor.
   */
  public function __construct()
  {
    @require_once M1_STORE_BASE_DIR . 'include' . DIRECTORY_SEPARATOR . 'config.php';

    $this->imagesDir = 'images/products/';

    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;

    if (defined('ADMIN_DIR')) {
      $this->cartVars['AdminUrl'] = ADMIN_DIR;
    }

    $this->setHostPort($servername);
    $this->userName  = $dbusername;
    $this->password  = $dbpassword;
    $this->dbName    = $dbname;

    if (isset($dbprefix)) {
      $this->tablePrefix = $dbprefix;
    }

    $version = $this->getCartVersionFromDb('value', 'settings', "name = 'version'");
    if ($version != '') {
      $this->cartVars['dbVersion'] = $version;
    }
  }
}



class M1_Config_Adapter_Shopware extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Shopware constructor.
   */
  public function __construct()
  {
    $config = file_get_contents(M1_STORE_BASE_DIR . 'config.php');
    preg_match("/(dbname'[ ]*=>[ ]*')(.+?)(',)/", $config, $match);
    $this->dbName = $match[2];
    preg_match("/(username'[ ]*=>[ ]*')(.+?)(',)/", $config, $match);
    $this->userName = $match[2];
    preg_match("/(password'[ ]*=>[ ]*')(.+?)(',)/", $config, $match);
    $this->password = (string)@$match[2];
    preg_match("/(host'[ ]*=>[ ]*')(.+?)(',)/", $config, $match);
    $hostPort = $match[2];
    preg_match("/(port'[ ]*=>[ ]*')(.+?)(',)/", $config, $match);
    $this->setHostPort($hostPort . ':' . @$match[2]);

    $this->imagesDir = 'media/image/';
    $this->categoriesImagesDir = $this->imagesDir;
    $this->productsImagesDir = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;

    $applicationphp = file_get_contents(M1_STORE_BASE_DIR . 'engine/Shopware/Application.php');
    preg_match("/(const VERSION\s+= ')(.+)(';)/", $applicationphp, $match);
    $version = $match[2];
    if ($version != '') {
      $this->cartVars['dbVersion'] = $version;
    }
  }
}


class M1_Config_Adapter_Oscommerce22ms2 extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Oscommerce22ms2 constructor.
   */
  public function __construct()
  {
    $currentDir = getcwd();

    chdir(M1_STORE_BASE_DIR);

    @require_once M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'configure.php';

    chdir($currentDir);

    $this->imagesDir = DIR_WS_IMAGES;

    $this->categoriesImagesDir = $this->imagesDir;
    $this->productsImagesDir   = $this->imagesDir;

    if (defined('DIR_WS_PRODUCT_IMAGES')) {
      $this->productsImagesDir = DIR_WS_PRODUCT_IMAGES;
    }

    if (defined('DIR_WS_ORIGINAL_IMAGES')) {
      $this->productsImagesDir = DIR_WS_ORIGINAL_IMAGES;
    }

    $this->manufacturersImagesDir = $this->imagesDir;

    $this->setHostPort(DB_SERVER);
    $this->userName  = DB_SERVER_USERNAME;
    $this->password  = DB_SERVER_PASSWORD;
    $this->dbName    = DB_DATABASE;
    chdir(M1_STORE_BASE_DIR);

    if (@file_exists(M1_STORE_BASE_DIR  . 'includes' . DIRECTORY_SEPARATOR . 'application_top.php')) {
      $conf = file_get_contents(M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'application_top.php');
      preg_match("/define\('PROJECT_VERSION.*/", $conf, $match);
      if (isset($match[0]) && !empty($match[0])) {
        preg_match("/\d.*/", $match[0], $project);
        if (isset($project[0]) && !empty($project[0])) {
          $version = $project[0];
          $version = str_replace(array(' ','-','_',"'",');'), '', $version);
          if ($version != '') {
            $this->cartVars['dbVersion'] = strtolower($version);
          }
        }
      } else {
        //if another oscommerce based cart
        if (@file_exists(M1_STORE_BASE_DIR  . 'includes' . DIRECTORY_SEPARATOR . 'version.php')) {
          @require_once M1_STORE_BASE_DIR . 'includes' . DIRECTORY_SEPARATOR . 'version.php';
          if (defined('PROJECT_VERSION') && PROJECT_VERSION != '') {
            $version = PROJECT_VERSION;
            preg_match("/\d.*/", $version, $vers);
            if (isset($vers[0]) && !empty($vers[0])) {
              $version = $vers[0];
              $version = str_replace(array(' ','-','_'), '', $version);
              if ($version != '') {
                $this->cartVars['dbVersion'] = strtolower($version);
              }
            }
            //if zen_cart
          } else {
            if (defined('PROJECT_VERSION_MAJOR') && PROJECT_VERSION_MAJOR != '') {
              $this->cartVars['dbVersion'] = PROJECT_VERSION_MAJOR;
            }

            if (defined('PROJECT_VERSION_MINOR') && PROJECT_VERSION_MINOR != '') {
              $this->cartVars['dbVersion'] .= '.' . PROJECT_VERSION_MINOR;
            }
          }
        }
      }
    }

    chdir($currentDir);
  }
}



class M1_Config_Adapter_Prostores extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Prostores constructor.
   */
  public function __construct()
  {
    $config = file_get_contents(M1_STORE_BASE_DIR . 'ps_config.php');

    preg_match('/\$db_host.+\'(.+)\';/', $config, $matches);
    $this->setHostPort($matches[1]);

    preg_match('/\$db_user.+\'(.+)\';/', $config, $matches);
    $this->userName = $matches[1];

    preg_match('/\$db_name.+\'(.+)\';/', $config, $matches);
    $this->dbName   = $matches[1];

    preg_match('/\$db_password.+\'(.*)\';/', $config, $matches);
    $this->password = $matches[1];

    unset($matches);

    $this->imagesDir = 'catalog/';
    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;

    if (($version = $this->getCartVersionFromDb('SchemaVersion', 'DatabaseShard', 'Status = 1')) != '') {
      $this->cartVars['dbVersion'] = $version;
    }
  }
}

class M1_Config_Adapter_Virtuemart extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Virtuemart constructor.
   */
  public function __construct()
  {
    require_once M1_STORE_BASE_DIR . '/configuration.php';

    if (class_exists('JConfig')) {

      $jconfig = new JConfig();

      $this->setHostPort($jconfig->host);
      $this->dbName   = $jconfig->db;
      $this->userName = $jconfig->user;
      $this->password = $jconfig->password;

    } else {

      $this->setHostPort($mosConfig_host);
      $this->dbName   = $mosConfig_db;
      $this->userName = $mosConfig_user;
      $this->password = $mosConfig_password;
    }

    if (@file_exists(M1_STORE_BASE_DIR . '/administrator/components/com_virtuemart/version.php')) {
      $ver = file_get_contents(M1_STORE_BASE_DIR . '/administrator/components/com_virtuemart/version.php');
      if (preg_match('/\$RELEASE.+\'(.+)\'/', $ver, $match) != 0) {
        $this->cartVars['dbVersion'] = $match[1];
        unset($match);
      }
    }

    $this->imagesDir = 'components/com_virtuemart/shop_image/';
    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;

    if (!is_dir( M1_STORE_BASE_DIR . $this->imagesDir)) {
      $this->imagesDir = 'images/stories/virtuemart/';
      $this->productsImagesDir      = $this->imagesDir . 'product/';
      $this->categoriesImagesDir    = $this->imagesDir . 'category/';
      $this->manufacturersImagesDir  = $this->imagesDir . 'manufacturer/';
    }
  }
}


class M1_Config_Adapter_Oxid extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Oxid constructor.
   */
  public function __construct()
  {
    //@include_once M1_STORE_BASE_DIR . "config.inc.php";
    $config = file_get_contents(M1_STORE_BASE_DIR . 'config.inc.php');
    preg_match("/dbName(.+)?=(.+)?\'(.+)\';/", $config, $match);
    $this->dbName = $match[3];
    preg_match("/dbUser(.+)?=(.+)?\'(.+)\';/", $config, $match);
    $this->userName = $match[3];
    preg_match("/dbPwd(.+)?=(.+)?\'(.+)\';/", $config, $match);
    $this->password = isset($match[3]) ? $match[3] : '';
    preg_match("/dbHost(.+)?=(.+)?\'(.*)\';/", $config, $match);
    $this->setHostPort($match[3]);

    //check about last slash
    $this->imagesDir = 'out/pictures/';
    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;

    //add key for decoding config values in oxid db
    //check slash
    $keyConfigFile = file_get_contents(M1_STORE_BASE_DIR . '/core/oxconfk.php');
    preg_match("/sConfigKey(.+)?=(.+)?\"(.+)?\";/", $keyConfigFile, $match);
    $this->cartVars['sConfigKey'] = $match[3];
    $version = $this->getCartVersionFromDb('OXVERSION', 'oxshops', 'OXACTIVE=1 LIMIT 1');
    if ($version != '') {
      $this->cartVars['dbVersion'] = $version;
    }
  }
}



class M1_Config_Adapter_Loaded7 extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Loaded7 constructor.
   */
  public function __construct()
  {
    include_once( M1_STORE_BASE_DIR . '/includes/config.php');

    $this->setHostPort(DB_SERVER);
    $this->userName = DB_SERVER_USERNAME;
    $this->password = DB_SERVER_PASSWORD;
    $this->dbName   = DB_DATABASE;

    $this->imagesDir              = '/' . DIR_WS_IMAGES;
    $this->categoriesImagesDir    = $this->imagesDir . 'categories/';
    $this->productsImagesDir      = $this->imagesDir . 'products/';
    $this->manufacturersImagesDir = $this->imagesDir . 'manufacturers/';
  }
}

class M1_Config_Adapter_Pinnacle extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Pinnacle constructor.
   */
  public function __construct()
  {
    include_once M1_STORE_BASE_DIR . 'content/engine/engine_config.php';

    $this->imagesDir = 'images/';
    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;

    //$this->Host = DB_HOST;
    $this->setHostPort(DB_HOST);
    $this->dbName = DB_NAME;
    $this->userName = DB_USER;
    $this->password = DB_PASSWORD;

    $version = $this->getCartVersionFromDb('value', (defined('DB_PREFIX') ? DB_PREFIX : '') . 'settings', "name = 'AppVer'");
    if ($version != '') {
      $this->cartVars['dbVersion'] = $version;
    }
  }
}


class M1_Config_Adapter_Interspire extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Interspire constructor.
   */
  public function __construct()
  {
    require_once M1_STORE_BASE_DIR . 'config/config.php';

    $this->setHostPort($GLOBALS['ISC_CFG']['dbServer']);
    $this->userName = $GLOBALS['ISC_CFG']['dbUser'];
    $this->password = $GLOBALS['ISC_CFG']['dbPass'];
    $this->dbName   = $GLOBALS['ISC_CFG']['dbDatabase'];

    if (isset($GLOBALS['ISC_CFG']['CharacterSet'])
      && $GLOBALS['ISC_CFG']['CharacterSet'] != ''
    ) {
      $this->cartVars['dbCharSet'] = $GLOBALS['ISC_CFG']['CharacterSet'];
    }

    $this->imagesDir = $GLOBALS['ISC_CFG']['ImageDirectory'];
    $this->categoriesImagesDir    = $this->imagesDir;
    $this->productsImagesDir      = $this->imagesDir;
    $this->manufacturersImagesDir = $this->imagesDir;

    define('DEFAULT_LANGUAGE_ISO2', $GLOBALS['ISC_CFG']['Language']);

    $version = $this->getCartVersionFromDb('database_version', $GLOBALS['ISC_CFG']['tablePrefix'] . 'config', '1');
    if ($version != '') {
      $this->cartVars['dbVersion'] = $version;
    }
  }
}

class M1_Config_Adapter_Squirrelcart242 extends M1_Config_Adapter
{
  /**
   * M1_Config_Adapter_Squirrelcart242 constructor.
   */
  public function __construct()
  {
    include_once(M1_STORE_BASE_DIR . 'squirrelcart/config.php');

    $this->setHostPort($sql_host);
    $this->dbName = $db;
    $this->userName = $sql_username;
    $this->password = $sql_password;

    $this->imagesDir = $img_path;
    $this->categoriesImagesDir = $img_path . '/categories';
    $this->productsImagesDir = $img_path . '/products';
    $this->manufacturersImagesDir = $img_path;

    $version = $this->getCartVersionFromDb('DB_Version', 'Store_Information', 'record_number = 1');
    if ($version != '') {
      $this->cartVars['dbVersion'] = $version;
    }
  }
}

class M1_Pdo
{
  public $config = null; // config adapter
  public $result = array();
  public $noResult = array('delete', 'update', 'move', 'truncate', 'insert', 'set', 'create', 'drop', 'replace', 'alter', '/*', 'commit', 'start', 'load');

  /** @var PDO $dataBaseHandle */
  public $dataBaseHandle = null;

  public $insertedId = 0;
  public $affectedRows = 0;

  /**
   * M1_Pdo constructor.
   *
   * @param M1_Config_Adapter $config configuration
   *
   * @return M1_Pdo
   */
  public function __construct($config)
  {
    $this->config = $config;
  }

  /**
   * @return bool|null|PDO
   */
  public function getDataBaseHandle()
  {
    if ($this->dataBaseHandle) {
      return $this->dataBaseHandle;
    }

    $this->dataBaseHandle = $this->connect();

    if (!$this->dataBaseHandle) {
      exit('[ERROR] MySQL Query Error: Can not connect to DB');
    }

    return $this->dataBaseHandle;
  }

  /**
   * @return bool|PDO
   */
  public function connect()
  {
    $triesCount = 3;
    $host = $this->config->host;
    $port = ($this->config->port ? ('port=' . $this->config->port . ';') : '');
    $socket = ($this->config->socket ? ('unix_socket=' . $this->config->socket . ';') : '');
    $userName = $this->config->userName;
    $password = (stripslashes($this->config->password));
    $dbName = $this->config->dbName;

    while ($triesCount) {
      try {
        $link = new PDO("mysql:host=$host;$port$socket dbname=$dbName", $userName, $password);
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $link;

      } catch (PDOException $e) {
        $triesCount--;

        if ($triesCount == 1) {
          $password = sprintf($password);
        }

        // fix invalid port and socket
        $port = '';
        $socket = '';
      }
    }
    return false;
  }

  /**
   * @param string $sql sql query
   *
   * @return array|bool
   */
  public function localQuery($sql)
  {
    $result = array();
    $dataBaseHandle = $this->getDataBaseHandle();

    $sth = $dataBaseHandle->query($sql);

    if (!$sth) {
      return true;
    }

    $sql = trim($sql);
    foreach ($this->noResult as $statement) {
      if (stripos($sql, $statement) === 0) {
        return true;
      }
    }

    while (($row = $sth->fetch(PDO::FETCH_ASSOC)) != false) {
      $result[] = $row;
    }

    return $result;
  }

  /**
   * @param string $sql       sql query
   * @param int    $fetchType fetch Type
   *
   * @return array
   */
  public function query($sql, $fetchType)
  {
    $result = array(
      'result' => null,
      'message' => '',
      'fetchedFields' => array(),
    );
    $dataBaseHandle = $this->getDataBaseHandle();
    try {
      switch ($fetchType) {
        case 3:
          $dataBaseHandle->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_BOTH);
          break;
        case 2:
          $dataBaseHandle->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
          break;
        case 1:
        default:
          $dataBaseHandle->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
          break;
      }

      $res = $dataBaseHandle->query($sql);
      $this->affectedRows = $res->rowCount();
      $this->insertedId = $dataBaseHandle->lastInsertId();
    } catch (PDOException $e) {
      $errorinfo = $e->errorInfo;
      $result['mysql_error_num'] = @$errorinfo[1];
      $result['message'] = '[ERROR] Mysql Query Error: ' . $e->getCode() . ', ' . $e->getMessage();
      return $result;
    }

    if (!$res) {
      $result['result'] = true;
      return $result;
    }

    $sql = trim($sql);
    foreach ($this->noResult as $statement) {
      if (stripos($sql, $statement) === 0) {
        $result['result'] = true;
        return $result;
      }
    }

    $rows = array();
    while (($row = $res->fetch()) !== false) {
      $rows[] = gzdeflate(serialize($row));
    }

    $result['result'] = $rows;

    unset($res);
    return $result;
  }

  /**
   * get dbCharset from database
   *
   * @param string $default default charset
   *
   * @return string
   */
  public function getCharset($default = 'utf8')
  {
    if ($this->getDataBaseHandle()) {
      $res = $this->getDataBaseHandle()->query("
          SELECT CHARACTER_SET_NAME as cs, count(CHARACTER_SET_NAME) as count
          FROM INFORMATION_SCHEMA.COLUMNS
          WHERE CHARACTER_SET_NAME <> ''
          GROUP BY CHARACTER_SET_NAME
          ORDER BY count DESC
          LIMIT 1
        ",
        PDO::FETCH_ASSOC
      );

      if ($row = $res->fetch()) {
        return $row['cs'];
      }

      return $default;
    }

    return $default;
  }

  /**
   * @return mixed|string
   */
  public function getServerInfo()
  {
    if ($this->getDataBaseHandle() instanceof PDO) {
      return $this->getDataBaseHandle()->getAttribute(PDO::ATTR_SERVER_VERSION);
    }

    return '0.0.0';
  }

  /**
   * @return string|int
   */
  public function getLastInsertId()
  {
    return $this->insertedId;
  }

  /**
   * @return int
   */
  public function getAffectedRows()
  {
    return $this->affectedRows;
  }

  /**
   * @return  void
   */
  public function __destruct()
  {
    $this->dataBaseHandle = null;
  }
}

class M1_Bridge_Utils
{
  public static function checkDuplicateKey(M1_Bridge $bridge, $query, $message)
  {
    if (stripos($message, 'Duplicate entry') === false) {
      return false;
    }

    if (substr($message, -18) == " for key 'PRIMARY'") {
      return self::_getPrimaryKeyLastInsertId($message);
    }

    return self::_getUniqueKeyLastInsertId($bridge, $query, $message);
  }

  private static function _getPrimaryKeyLastInsertId($message)
  {
    preg_match('#Duplicate entry \'(.*)\'#U', $message, $matches);

    return (!isset($matches[1]) || strpos($matches[1], '-')) ? 0 : $matches[1];
  }

  private static function _getUniqueKeyLastInsertId(M1_Bridge $bridge, $query, $message)
  {
    preg_match('#Duplicate entry \'(.*)\'.*\'(.*)\'#U', $message, $matches);

    $tableName = null;
    if (preg_match('#INTO\s+`(.*)`#U', $query, $table)) {
      $tableName = $table[1];
      unset($table);
    }

    if (!$tableName) {
      return false;
    }

    $pattern = "#INTO\\s+.*\\s+\\((.*?)\\)\\s+VALUES\\s+\\((.*?)\\)#U";

    if (!preg_match($pattern, $query, $fieldsMatches)) {
      return false;
    }

    unset($fieldsMatches[0]);

    $fields = explode('`, `', $fieldsMatches[1]);
    $fields[0] = str_replace('`', '', $fields[0]);
    $fields[count($fields) - 1] = str_replace('`', '', $fields[count($fields) - 1]);
    $values = str_getcsv($fieldsMatches[2]);

    if (!($combined = @array_combine($fields, $values))) {
      return false;
    }

    $keys = array();
    $primaryKey = false;
    $res = $bridge->getLink()->localQuery('SHOW KEYS FROM ' . $tableName. " WHERE Key_name = '{$matches[2]}' OR Key_name = 'PRIMARY'");
    if (empty($res)) {
      return false;
    }

    foreach ($res as $row) {
      if ($row['Key_name'] == 'PRIMARY') {
        $primaryKey = $row['Column_name'];
      } else {
        in_array($row['Column_name'], $fields) and $keys[] = $row['Column_name'];
      }
    }

    if (!$primaryKey) {
      return 0;
    }

    $conditions = array();
    foreach ($keys as $key) {
      $conditions[] = '`' . $key . '` = ' . $combined[$key];
    }

    $res = $bridge->getLink()->localQuery(
      sprintf(
        'SELECT `%s` FROM `%s` WHERE %s',
        $primaryKey,
        $tableName,
        implode(' AND ', $conditions)
      )
    );

    return isset($res[0][$primaryKey]) ? $res[0][$primaryKey] : false;
  }
}

class M1_Mysqli
{

  public $config = null; // config adapter
  public $result = array();
  public $dataBaseHandle = null;

  /**
   * mysql constructor
   *
   * @param M1_Config_Adapter $config configuration
   *
   * @return M1_Mysqli
   */
  public function __construct($config)
  {
    $this->config = $config;
  }

  /**
   * @return bool|null|mysqli
   */
  public function getDataBaseHandle()
  {
    if ($this->dataBaseHandle) {
      return $this->dataBaseHandle;
    }

    $this->dataBaseHandle = $this->connect();

    if (!$this->dataBaseHandle) {
      exit('[ERROR] MySQL Query Error: Can not connect to DB');
    }

    return $this->dataBaseHandle;
  }

  /**
   * @return bool|null|resource
   */
  public function connect()
  {
    $triesCount = 5;
    $link = null;
    $host = $this->config->host;
    $port = $this->config->port;
    $socket = $this->config->socket;
    $password = (stripslashes($this->config->password));

    while (!$link) {
      if (!$triesCount--) {
        break;
      }

      $link = @mysqli_connect($host, $this->config->userName, $password, '', $port, $socket);
      if (!$link) {
        sleep(5);
      }

      if ($triesCount == 3) {
        // fix invalid port and socket
        $port = '';
        $socket = '';
      } elseif ($triesCount == 1) {
        $password = sprintf($password);
      }
    }

    if (!$link) {
      return false;
    } else {
      mysqli_select_db($link, $this->config->dbName);
      return $link;
    }
  }

  /**
   * @param string $sql sql query
   *
   * @return array
   */
  public function localQuery($sql)
  {
    $result = array();
    $dataBaseHandle = $this->getDataBaseHandle();

    $sth = mysqli_query($dataBaseHandle, $sql);

    if (is_bool($sth)) {
      return $sth;
    }

    while (($row = mysqli_fetch_assoc($sth)) != false) {
      $result[] = $row;
    }

    return $result;
  }

  /**
   * @param string $sql       sql query
   * @param int    $fetchType fetch Type
   *
   * @return array
   */
  public function query($sql, $fetchType)
  {
    $result = array(
      'result' => null,
      'message' => '',
    );
    $dataBaseHandle = $this->getDataBaseHandle();

    if (!$dataBaseHandle) {
      $result['message'] = '[ERROR] MySQL Query Error: Can not connect to DB';
      return $result;
    }

    switch ($fetchType) {
      case 3:
        $fetchMode = MYSQLI_BOTH;
        break;
      case 2:
        $fetchMode = MYSQLI_NUM;
        break;
      case 1:
        $fetchMode = MYSQLI_ASSOC;
        break;
      default:
        $fetchMode = MYSQLI_ASSOC;
        break;
    }

    $res = mysqli_query($dataBaseHandle, $sql);
    $triesCount = 10;
    while (mysqli_errno($dataBaseHandle) == 2013) {
      if (!$triesCount--) {
        break;
      }
      // reconnect
      $dataBaseHandle = $this->getDataBaseHandle();
      if ($dataBaseHandle) {

        if (isset($_REQUEST['set_names'])) {
          @mysqli_query($dataBaseHandle, "SET NAMES " . @mysqli_real_escape_string($dataBaseHandle, $_REQUEST['set_names']));
          @mysqli_query($dataBaseHandle, "SET CHARACTER SET " . @mysqli_real_escape_string($dataBaseHandle, $_REQUEST['set_names']));
          @mysqli_query($dataBaseHandle, "SET CHARACTER_SET_CONNECTION=" . @mysqli_real_escape_string($dataBaseHandle, $_REQUEST['set_names']));
        }

        // execute query once again
        $res = mysqli_query($dataBaseHandle, $sql);
      }
    }

    if (($errno = mysqli_errno($dataBaseHandle)) != 0) {
      $result['mysqli_error_num'] = $errno;
      $result['message'] = '[ERROR] Mysql Query Error: ' . $errno . ', ' . mysqli_error($dataBaseHandle);
      return $result;
    }

    if (!$res instanceof mysqli_result) {
      $result['result'] = $res;
      return $result;
    }

    $fetchedFields = array();
    while (($field = mysqli_fetch_field($res)) !== false) {
      $fetchedFields[] = $field;
    }

    $rows = array();
    while (($row = mysqli_fetch_array($res, $fetchMode)) !== null) {
      $rows[] = gzdeflate(serialize($row));
    }

    $result['result'] = $rows;
    $result['fetchedFields'] = $fetchedFields;

    mysqli_free_result($res);
    return $result;
  }

  /**
   * get dbCharset from database
   *
   * @param string $default default charset
   *
   * @return string
   */
  public function getCharset($default = 'utf8')
  {
    if ($this->getDataBaseHandle()) {
      $res = mysqli_query($this->getDataBaseHandle(), "
          SELECT CHARACTER_SET_NAME as cs, count(CHARACTER_SET_NAME) as count
          FROM INFORMATION_SCHEMA.COLUMNS
          WHERE CHARACTER_SET_NAME <> ''
          GROUP BY CHARACTER_SET_NAME
          ORDER BY count DESC
          LIMIT 1
        "
      );

      if (!is_resource($res)) {
        return $default;
      }

      if ($row = mysqli_fetch_assoc($res)) {
        return $row['cs'];
      }

      return $default;
    }

    return $default;
  }

  /**
   * @return string
   */
  public function getServerInfo()
  {
    if ($this->getDataBaseHandle()) {
      return mysqli_get_server_info($this->getDataBaseHandle());
    }

    return '0.0.0';
  }

  /**
   * @return int
   */
  public function getLastInsertId()
  {
    return mysqli_insert_id($this->dataBaseHandle);
  }

  /**
   * @return int
   */
  public function getAffectedRows()
  {
    return mysqli_affected_rows($this->dataBaseHandle);
  }

  /**
   * @return void
   */
  public function __destruct()
  {
    if ($this->dataBaseHandle) {
      mysqli_close($this->dataBaseHandle);
    }

    $this->dataBaseHandle = null;
  }
}

class M1_Bridge_Action_Deleteimages
{
  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    switch ($bridge->config->cartType) {
      case 'Pinnacle':
        $this->_pinnacleDeleteImages($bridge);
        break;
      case 'PrestaShop':
        $this->_prestashopDeleteImages($bridge);
        break;
      case 'Summercart3':
        $this->_summercartDeleteImages($bridge);
        break;
    }
  }

  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  protected function _pinnacleDeleteImages($bridge)
  {
    $dirs = array(
      M1_STORE_BASE_DIR . $bridge->config->imagesDir . 'catalog/',
      M1_STORE_BASE_DIR . $bridge->config->imagesDir . 'manufacturers/',
      M1_STORE_BASE_DIR . $bridge->config->imagesDir . 'products/',
      M1_STORE_BASE_DIR . $bridge->config->imagesDir . 'products/thumbs/',
      M1_STORE_BASE_DIR . $bridge->config->imagesDir . 'products/secondary/',
      M1_STORE_BASE_DIR . $bridge->config->imagesDir . 'products/preview/',
    );

    $ok = true;

    foreach ($dirs as $dir) {

      if (!@file_exists($dir)) {
        continue;
      }

      $dirHandle = opendir($dir);

      while (false !== ($file = readdir($dirHandle))) {
        if ($file != '.' && $file != '..' && !preg_match("/^readme\.txt?$/", $file) && !preg_match("/\.bak$/i", $file)) {
          $filePath = $dir . $file;
          if (is_file($filePath)) {
            if (!rename($filePath, $filePath . '.bak')) {
              $ok = false;
            }
          }
        }
      }

      closedir($dirHandle);
    }

    if ($ok) {
      echo 'OK';
    } else {
      echo 'ERROR';
    }
  }

  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  protected function _prestashopDeleteImages($bridge)
  {
    $dirs = array(
      M1_STORE_BASE_DIR . $bridge->config->imagesDir . 'c/',
      M1_STORE_BASE_DIR . $bridge->config->imagesDir . 'p/',
      M1_STORE_BASE_DIR . $bridge->config->imagesDir . 'm/',
    );

    $ok = true;

    foreach ($dirs as $dir) {

      if (!@file_exists($dir)) {
        continue;
      }

      $dirHandle = opendir($dir);

      if (!$dirHandle) {
        $ok = false;
      } else {
        while (false !== ($file = readdir($dirHandle))) {
          if ($file != '.' && $file != '..' && preg_match("/(\d+).*\.jpg?$/", $file)) {
            $filePath = $dir . $file;
            if (is_file($filePath)) {
              if (!rename($filePath, $filePath . '.bak')) {
                $ok = false;
              }
            }
          }
        }

        closedir($dirHandle);
      }
    }

    if ($ok) {
      echo 'OK';
    } else {
      echo 'ERROR';
    }
  }

  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  protected function _summercartDeleteImages($bridge)
  {
    $dirs = array(
      M1_STORE_BASE_DIR . $bridge->config->imagesDir . 'categoryimages/',
      M1_STORE_BASE_DIR . $bridge->config->imagesDir . 'manufacturer/',
      M1_STORE_BASE_DIR . $bridge->config->imagesDir . 'productimages/',
      M1_STORE_BASE_DIR . $bridge->config->imagesDir . 'productthumbs/',
      M1_STORE_BASE_DIR . $bridge->config->imagesDir . 'productboximages/',
      M1_STORE_BASE_DIR . $bridge->config->imagesDir . 'productlargeimages/',
    );

    $ok = true;

    foreach ($dirs as $dir) {

      if (!@file_exists($dir)) {
        continue;
      }

      $dirHandle = opendir($dir);

      while (false !== ($file = readdir($dirHandle))) {
        if (($file != '.') && ($file != '..') && !preg_match("/\.bak$/i", $file)) {
          $filePath = $dir . $file;
          if (is_file($filePath)) {
            if (!rename($filePath, $filePath . '.bak')) {
              $ok = false;
            }
          }
        }
      }

      closedir($dirHandle);
    }

    if ($ok) {
      echo 'OK';
    } else {
      echo 'ERROR';
    }
  }
}

class M1_Bridge_Action_Phpinfo
{
  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    phpinfo();
  }
}


class M1_Bridge_Action_Loadfromfile
{
  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return bool
   */
  public function perform($bridge)
  {
    $errorMessage = false;
    if (isset($_POST['tableName']) && isset($_POST['data'])) {

      $file =  sys_get_temp_dir() . '/queryFile.txt';

      $result = file_put_contents($file, gzinflate(base64_decode($_POST['data'])));
      if ($result) {
        chmod($file, 0777);

        try {
          if (isset($_GET['disable_checks'])) {
            $bridge->query('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0');
            $bridge->query("SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO'");
          }
          $bridge->query('SET character_set_database=utf8', (int)$_POST['fetchMode']);
          $res = $bridge->query('LOAD DATA INFILE "' . $file . '" IGNORE INTO TABLE ' . $_POST['tableName'] . ' FIELDS TERMINATED BY \',\' ENCLOSED BY "\'"', (int)$_POST['fetchMode']);
          if (isset($_GET['disable_checks'])) {
            $bridge->query("SET SQL_MODE=IFNULL(@OLD_SQL_MODE,'')");
            $bridge->query('SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS,0)');
          }
        } catch (Exception $e) {
          $errorMessage = $e->getMessage();
        }

        $res['message'] and $errorMessage = $res['message'];
        unlink($file);
      } else {
        $errorMessage = 'Cannot save file with inserts';
      }

      if (!$errorMessage) {

        $result = serialize(
          array(
            'res' => $res['result'],
            'affectedRows' => $bridge->getLink()->getAffectedRows(),
          )
        );

        echo base64_encode(gzdeflate($result));
      } else {
        echo $errorMessage;
      }
    } else {
      return false;
    }
  }
}

class M1_Bridge_Action_Cubecart
{
  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    $dirHandle = opendir(M1_STORE_BASE_DIR . 'language/');

    $languages = array();

    while ($dirEntry = readdir($dirHandle)) {
      if (!is_dir(M1_STORE_BASE_DIR . 'language/' . $dirEntry)
        || $dirEntry == '.' || $dirEntry == '..' || strpos($dirEntry, '_') !== false
      ) {
        continue;
      }

      $lang['id'] = $dirEntry;
      $lang['iso2'] = $dirEntry;

      $configurationFile = 'config.inc.php';

      if (!@file_exists(M1_STORE_BASE_DIR . 'language/' . $dirEntry . '/'. $configurationFile)) {
        $configurationFile = 'config.php';
      }

      if (!@file_exists( M1_STORE_BASE_DIR . 'language/' . $dirEntry . '/'. $configurationFile)) {
        continue;
      }

      $str = file_get_contents(M1_STORE_BASE_DIR . 'language/' . $dirEntry . '/' . $configurationFile);
      preg_match('/' . preg_quote('$langName') . "[\s]*=[\s]*[\"\'](.*)[\"\'];/", $str, $match);

      if (isset($match[1])) {
        $lang['name'] = $match[1];
        $languages[] = $lang;
      }
    }

    echo serialize($languages);
  }
}

class M1_Bridge_Action_Getfile
{
  protected $_notAllowedFileTypes = array(
    'php',
    'html',
    'htm',
    'aspx',
    'xml',
    'phar',
    'js',
  );

  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    $filePath = $_GET['filePath'];
    $this->_getFile($filePath);
  }

  /**
   * @param string $filePath
   */
  protected function _getFile($filePath = '')
  {
    $exts = $this->prepareExtensions();

    if ($filePath) {
      $pathInfo = @pathinfo(str_replace('../', '', $filePath));

      str_replace($exts, '', $filePath, $count);

      if (!$count && ((!empty($pathInfo['extension']) && !isset($this->_notAllowedFileTypes[$pathInfo['extension']]))
          || !isset($pathInfo['extension']))
      ) {
        $fileLocation = M1_STORE_BASE_DIR . $filePath;
        if (file_exists($fileLocation)) {
          header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
          header('Cache-Control: public');
          header('Content-Type: ' . mime_content_type($fileLocation));
          header('Content-Transfer-Encoding: Binary');
          header('Content-Length:' . filesize($fileLocation));
          header('Content-Disposition: attachment; filename=' . $pathInfo['filename']);
          readfile($fileLocation);
          die();
        }
      }
    }

    header($_SERVER['SERVER_PROTOCOL'] . ' 404 OK');
    echo 'Error: File not found.';
  }

  protected function prepareExtensions()
  {
    $extensions = array();

    foreach ($this->_notAllowedFileTypes as $value) {
      $extensions[] = '.' . $value;
    }

    return $extensions;
  }
}

class M1_Bridge_Action_Getfsfile
{
  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    $productsImage = (string)$_POST['product_image'];

    $imagesArray = array();

    if ($productsImage != '') {

      $productsImageExtension = substr($productsImage, strrpos($productsImage, '.'));
      $productsImageBase = str_replace($productsImageExtension, '', $productsImage);

      if (strrpos($productsImage, '/')) {
        $productsImageMatch = substr($productsImage, strrpos($productsImage, '/') + 1);
        $productsImageMatch = str_replace($productsImageExtension, '', $productsImageMatch) . '_';
        $productsImageBase = $productsImageMatch;
      }

      $productsImageDirectory = str_replace($productsImage, '', substr($productsImage, strrpos($productsImage, '/')));

      if ($productsImageDirectory != '') {
        $productsImageDirectory = DIR_WS_IMAGES . str_replace($productsImageDirectory, '', $productsImage) . '/';
      } else {
        $productsImageDirectory = DIR_WS_IMAGES;
      }

      $fileExtension = $productsImageExtension;

      if ($dir = dir(M1_STORE_BASE_DIR . $productsImageDirectory)) {
        while ($file = $dir->read()) {
          if (!is_dir($productsImageDirectory . $file)) {
            if (substr($file, strrpos($file, '.')) == $fileExtension) {
              if (preg_match('/' . $productsImageBase . '/i', $file) == '1') {
                if ($file != $productsImage) {
                  if ($productsImageBase . str_replace($productsImageBase, '', $file) == $file) {
                    $imagesArray[] = $productsImageDirectory . $file;
                  }
                }
              }
            }
          }
        }

        if (count($imagesArray)) {
          sort($imagesArray);
        }

        $dir->close();
      }
    }

    echo serialize($imagesArray);
  }
}

class M1_Bridge_Action_Getmagentolog
{
  const DIR = 'var/log';
  var $files = array(
    'exception.log',
    'system.log'
  );
  const MAX_READ_LINE = 200;

  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    if (!@file_exists(M1_STORE_BASE_DIR . self::DIR)) {
      die('ERROR_LOG_DIR_NOT_EXIST');
    }

    foreach ($this->files as $file) {
      $filename = M1_STORE_BASE_DIR . self::DIR . '/' . $file;

      echo '<h2>Read file "' . $filename . '": </h2>';

      if (!is_file($filename)) {
        echo 'ERROR_FILE_NOT_EXISTS<br />';
        continue;
      }

      if (filesize($filename) == 0) {
        echo 'ERROR_FILE_IS_EMPTY<br />';
        continue;
      }

      $lines = file($filename);

      if (count($lines) > self::MAX_READ_LINE) {
        $lines = array_slice($lines, count($lines) - self::MAX_READ_LINE, self::MAX_READ_LINE);
      }

      echo nl2br(implode('', $lines));
    }
  }
}

class M1_Bridge_Action_Carttype
{
  /**
   * return cart type
   *
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    echo $bridge->config->cartType;
  }
}

class M1_Bridge_Action_Getserverip
{
  public function perform($bridge)
  {
    echo serialize(!empty($_SERVER['SERVER_ADDR']) ? array('ip' => $_SERVER['SERVER_ADDR']) : array());
  }
}

class M1_Bridge_Action_Testsavefile{

  public $productImagesDir = null;

  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    if (version_compare(phpversion(), '5.0.0', '<')) {
      die('TESTSAVEFILE CAN`T BE PERFORMED FOR PHP VERSION ' . phpversion());
    }

    $this->productImagesDir = '../' . $bridge->config->productsImagesDir;
    $file = $this->getTestFile();

    $testDir = $this->productImagesDir . '/test_dir/';

    if (is_dir($testDir)) {
      //maneuver to trick PHP 4 syntax parser
      $iteratorIterator = 'RecursiveIteratorIterator';
      $directoryIterator = 'RecursiveDirectoryIterator';
      $objects = new $iteratorIterator(new $directoryIterator($testDir), constant('RecursiveIteratorIterator::SELF_FIRST'));
      foreach (array_keys($objects) as $name) {
        if (preg_match('/\.(jpe?g|png|gif)$/', $name)) {
          @unlink($name);
        }
      }
      if (!@rmdir($testDir)) {
        echo '<span style="color:red">[TEST ERROR]</span> Can\'t not delete test directory!<br>';
        exit();
      }
    }

    if (!@mkdir($testDir)) {
      echo '<span style="color:red">[TEST ERROR]</span> Can\'t not create directory!<br>';
      exit();
    }
    echo '<span style="color:green">Create directory - OK</span><br>';

    if (!@chmod($testDir, 0777)) {
      echo '<span style="color:red">[TEST ERROR]</span> Set permission error!<br>';
      exit();
    }
    echo '<span style="color:green">Set permission - OK</span><br>';

    $fileName = basename($file);
    if (!copy($file, $testDir . $fileName)) {
      echo '<span style="color:red">[TEST ERROR]</span> Can\'t not copy file!<br>';
      exit();
    }
    echo '<span style="color:green">Copy file - OK</span><br>';

    if (!@file_exists($testDir . $fileName)) {
      echo '<span style="color:red">[TEST ERROR]</span> Test file not found!<br>';
      exit();
    } else {
      echo '<span style="color:green">OK</span><br>Delete file '.$fileName.' ...';
      @unlink($testDir . $fileName);
      echo '<br>Delete directory test_dir ...';
      @rmdir($testDir);
    }

    echo "<br>End test!";
  }

  /**
   * @return int|string
   */
  public function getTestFile()
  {
    $iteratorIterator = 'RecursiveIteratorIterator';
    $directoryIterator = 'RecursiveDirectoryIterator';
    $objects = new $iteratorIterator(
      new $directoryIterator($this->productImagesDir),
      constant('RecursiveIteratorIterator::SELF_FIRST')
    );

    foreach (array_keys($objects) as $name) {
      if (preg_match('/\.(jpe?g|png|gif)$/', $name)) {
        return $name;
      }
    }
  }
}

class M1_Bridge_Action_Update
{
  public $uri = M1_BRIDGE_DOWNLOAD_LINK;
  public $pathToFile = __FILE__;

  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    $response = new stdClass();

    $settings = new M1_Setting();
    if (!$settings->allowedUpdate || $settings->setCustomAccess) {
      $response->isError = false;
      $response->message = "Bridge is custom, update skip.";
      $this->_viewResponse($response);
    }

    if (!($this->_checkBridgeDirPermission() && $this->_checkBridgeFilePermission())) {
      $response->isError = false;
      $response->message = "Bridge Update couldn't be performed. Please change permission for bridge folder to 777 "
        . "and bridge.php file inside it to 666";
      $this->_viewResponse($response);
    }

    if (($bridgeData = $this->_downloadFile()) === false) {
      $response->isError = false;
      $response->message = "Bridge Version is outdated. Files couldn't be updated automatically. Please set write permission "
        . "or re-upload files manually.";
      $this->_viewResponse($response);
    }

    $this->_viewResponse($this->_saveBridge($bridgeData));
  }

  /**
   * @param string $bridgeData
   *
   * @return stdClass
   */
  protected function _saveBridge ($bridgeData)
  {
    $response = new stdClass();

    if (!$this->_writeToFile($bridgeData, str_replace('bridge.php', 'tmp_bridge.php', $this->pathToFile))) {
      $response->isError = false;
      $response->message = "Couln't create temporary file in bridge folder or file is write protected.";
      return $response;
    }

    $checkBridge = @file_get_contents($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT']
      . str_replace('bridge.php', 'tmp_bridge.php', $_SERVER['SCRIPT_NAME'])
      . '?action=checkbridge&ver=' . M1_BRIDGE_VERSION . '&token=' . M1_TOKEN);

    switch ($checkBridge) {
      case false:
        $response->isError = false;
        $response->message = 'Not connect to tmp bridge file';
        unlink('./tmp_bridge.php');
        return $response;
      break;
      case 'BRIDGE_OK':
        if ($this->_writeToFile($bridgeData, $this->pathToFile)) {
          $response->isError = false;
          $response->message = 'Bridge successfully updated to latest version.';
          unlink('./tmp_bridge.php');
          return $response;
        } else  {
          $response->isError = false;
          $response->message = "Couln't write file in bridge folder or file is write protected.";
          return $response;
        }
      break;
      default:
        $response->isError = false;
        $response->message = $checkBridge;
        unlink('./tmp_bridge.php');
        return $response;
      break;
    }
  }

  /**
   * @param stdClass $response response
   *
   * @return void
   */
  protected function _viewResponse($response)
  {
    echo serialize($response);
    die;
  }

  /**
   * @param string $uri path
   *
   * @return stdClass
   */
  protected function _fetch($uri)
  {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = new stdClass();

    $response->error          = true;
    $response->body           = '';

    if ($data = @json_decode(curl_exec($ch))) {
      $response->error       = $data->error ? $data->error : $data->md5 != md5($data->body);
      $response->body        = $data->body;
    }

    $response->httpCode      = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $response->contentType   = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $response->contentLength = strlen($response->body);

    curl_close($ch);

    return $response;
  }

  /**
   * @return bool
   */
  protected function _checkBridgeDirPermission()
  {
    if (!is_writeable(dirname(__FILE__))) {
      @chmod(dirname(__FILE__), 0777);
    }

    return is_writeable(dirname(__FILE__));
  }

  /**
   * @return bool
   */
  protected function _checkBridgeFilePermission()
  {
    $pathToFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'bridge.php';
    if (!is_writeable($pathToFile)) {
      @chmod($pathToFile, 0666);
    }

    return is_writeable($pathToFile);
  }

  /**
   * @return bool
   */
  protected function _createTempDir()
  {
    @mkdir($this->pathToTmpDir, 0777);
    return @file_exists($this->pathToTmpDir);
  }

  /**
   * @return bool
   */
  protected function _removeTempDir()
  {
    @unlink($this->pathToTmpDir . DIRECTORY_SEPARATOR . 'bridge.php_c2c');
    @rmdir($this->pathToTmpDir);
    return !@file_exists($this->pathToTmpDir);
  }

  /**
   * @return bool|stdClass
   */
  protected function _downloadFile()
  {
    $file = $this->_fetch($this->uri);
    if (!$file->error) {
      return $file;
    }
    return false;
  }

  /**
   * @param stdClass $data data
   * @param string   $file path
   *
   * @return int
   */
  protected function _writeToFile($data, $file)
  {
    if (function_exists('file_put_contents')) {
      return file_put_contents($file, $data->body);
    }

    $handle = @fopen($file, 'w+');
    $bytes = fwrite($handle, $data->body);
    @fclose($handle);

    return $bytes;
  }
}

class M1_Bridge_Action_Getconfig
{
  /**
   * @param int $val memory limit value
   *
   * @return int
   */
  public function parseMemoryLimit($val)
  {
    $last = strtolower($val[strlen($val) - 1]);
    $val = substr($val, 0, strlen($val) - 1);
    switch ($last) {
      case 'g':
        $val *= 1024;
        break;
      case 'm':
        $val *= 1024;
        break;
      case 'k':
        $val *= 1024;
        break;
    }

    return $val;
  }

  /**
   * @return mixed
   */
  public function getMemoryLimit()
  {
    $memoryLimit = trim(@ini_get('memory_limit'));
    if (strlen($memoryLimit) === 0) {
      $memoryLimit = '0';
    }

    $memoryLimit = $this->parseMemoryLimit($memoryLimit);

    $maxPostSize = trim(@ini_get('post_max_size'));
    if (strlen($maxPostSize) === 0) {
      $maxPostSize = '0';
    }

    $maxPostSize = $this->parseMemoryLimit($maxPostSize);

    $suhosinMaxPostSize = trim(@ini_get('suhosin.post.max_value_length'));
    if (strlen($suhosinMaxPostSize) === 0) {
      $suhosinMaxPostSize = '0';
    }

    $suhosinMaxPostSize = $this->parseMemoryLimit($suhosinMaxPostSize);

    if ($suhosinMaxPostSize == 0) {
      $suhosinMaxPostSize = $maxPostSize;
    }

    if ($maxPostSize == 0) {
      $suhosinMaxPostSize = $maxPostSize = $memoryLimit;
    }

    return min($suhosinMaxPostSize, $maxPostSize, $memoryLimit);
  }

  /**
   * @return bool
   */
  public function isZlibSupported()
  {
    return function_exists('gzdecode');
  }

  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    if (!defined('DEFAULT_LANGUAGE_ISO2')) {
      define('DEFAULT_LANGUAGE_ISO2', ''); //variable for Interspire cart
    }

    if (!$bridge->config->cartVars['dbCharSet']) {
      $bridge->config->cartVars['dbCharSet'] = $bridge->config->getCharsetFromDb();
    }

    $result = array(
      'images' => array(
        'imagesPath'               => $bridge->config->imagesDir, // path to images folder - relative to store root
        'categoriesImagesPath'     => $bridge->config->categoriesImagesDir,
        'categoriesImagesPaths'    => $bridge->config->categoriesImagesDirs,
        'productsImagesPath'       => $bridge->config->productsImagesDir,
        'productsImagesPaths'      => $bridge->config->productsImagesDirs,
        'manufacturersImagesPath'  => $bridge->config->manufacturersImagesDir,
        'manufacturersImagesPaths' => $bridge->config->manufacturersImagesDirs,
      ),
      'languages'           => $bridge->config->languages,
      'baseDirFs'           => M1_STORE_BASE_DIR, // filesystem path to store root
      'defaultLanguageIso2' => $bridge->config->languageIso2 ? $bridge->config->languageIso2 : DEFAULT_LANGUAGE_ISO2,
      'databaseName'        => $bridge->config->dbName,
      'memoryLimit'         => $this->getMemoryLimit(),
      'zlibSupported'       => $this->isZlibSupported(),
      //'orderStatus'         => $bridge->config->orderStatus,
      'cartVars'            => $bridge->config->cartVars,
      'timeZone'            => date('P'),
    );

    echo serialize($result);
  }
}

class M1_Bridge_Action_Querymultiple
{
  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return bool
   */
  public function perform($bridge)
  {
    if (isset($_POST['query']) && isset($_POST['fetchMode'])) {

      $queries = unserialize(gzinflate(base64_decode($_POST['query'])));

      $dbHandle = $bridge->getLink();
      if (isset($_GET['disable_checks'])) {
        $dbHandle->localQuery('SET SESSION FOREIGN_KEY_CHECKS=0');
        $dbHandle->localQuery("SET SESSION SQL_MODE='NO_AUTO_VALUE_ON_ZERO'");
      }

      if (isset($_REQUEST['set_names'])) {
        $dbHandle->localQuery("SET NAMES '" . ($_REQUEST['set_names']) . "'");
        $dbHandle->localQuery("SET CHARACTER SET '" . ($_REQUEST['set_names']) . "'");
        $dbHandle->localQuery("SET CHARACTER_SET_CONNECTION = '" . ($_REQUEST['set_names']) . "'");
      }

      $result = false;
      $query = array_shift($queries);
      $res = $bridge->query($query, (int)$_POST['fetchMode']);
      if (is_array($res['result']) || is_bool($res['result'])) {

        $queryRes = array(
          'res'           => $res['result'],
          'fetchedFields' => @$res['fetchedFields'],
          'insertId'      => $bridge->getLink()->getLastInsertId(),
          'affectedRows'  => $bridge->getLink()->getAffectedRows(),
        );

        $result[md5($query)] = $queryRes;
      } elseif(strstr($query, '__REPLACE__')) {
        preg_match('/\/\*(.*)\*\/(.*)/s', $query, $matches);
        if (isset($matches[1]) && isset($matches[2])) {
          $query = trim($matches[2]);
          $replaces = explode('__END_REPLACE__', $matches[1]);
          foreach ($replaces as $replace) {
            if (!$replace) {
              continue;
            }

            if (strstr($replace, '__REPLACE__')) {
              list($from, $to) = explode('__REPLACE__', $replace);
              $query = str_replace($from, $to, $query);
            }
          }

          $_POST['query'] = base64_encode(gzdeflate(serialize(array($query))));
          $this->perform($bridge);
          return;
        }
      } elseif(($id = M1_Bridge_Utils::checkDuplicateKey($bridge, $query, $res['message'])) !== false) {
        $queryRes = array(
          'res'           => true,
          'fetchedFields' => array(),
          'insertId'      => $id,
          'affectedRows'  => 1,
        );

        $result[md5($query)] = $queryRes;
      } else {
        echo base64_encode(gzdeflate($res['message']));
        return false;
      }

      $lastInsert = $queryRes['insertId'];

      foreach ($queries as $query) {
        $query = str_replace('_C2C_LAST_INSERT_ID_', $lastInsert, $query);
        $res = $bridge->query($query, (int)$_POST['fetchMode']);
        if (is_array($res['result']) || is_bool($res['result'])) {

          $queryRes = array(
            'res'           => $res['result'],
            'fetchedFields' => @$res['fetchedFields'],
            'insertId'      => $bridge->getLink()->getLastInsertId(),
            'affectedRows'  => $bridge->getLink()->getAffectedRows(),
          );

          $result[md5($query)] = $queryRes;
        } elseif(strstr($query, '__REPLACE__')) {
          preg_match('/\/\*(.*)\*\/(.*)/s', $query, $matches);
          if (isset($matches[1]) && isset($matches[2])) {
            $query = trim($matches[2]);
            $replaces = explode('__END_REPLACE__', $matches[1]);
            foreach ($replaces as $replace) {
              if (!$replace) {
                continue;
              }

              if (strstr($replace, '__REPLACE__')) {
                list($from, $to) = explode('__REPLACE__', $replace);
                $query = str_replace($from, $to, $query);
              }
            }

            $_POST['query'] = base64_encode(gzdeflate($query));
            $this->perform($bridge);
            return;
          }
        } elseif(($id = M1_Bridge_Utils::checkDuplicateKey($bridge, $query, $res['message'])) !== false) {
          $queryRes = array(
            'res'           => true,
            'fetchedFields' => array(),
            'insertId'      => $id,
            'affectedRows'  => 1,
          );

          $result[md5($query)] = $queryRes;
        } else {
          echo base64_encode(gzdeflate($res['message']));
          return false;
        }
      }
      echo base64_encode(gzdeflate(serialize($result)));
    } else {
      return false;
    }
  }
}

class M1_Bridge_Action_Clearcache
{
  /**
   * clear cart cache
   *
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    switch ($bridge->config->cartType) {
      case 'Cubecart':
        $this->cubecartClearCache();
        break;
      case 'PrestaShop':
        $this->prestashopClearCache();
        return;
      case 'Interspire':
        $this->interspireClearCache();
        break;
      case 'Opencart':
        $this->opencartClearCache();
        break;
      case 'Xtcommerce':
        $this->xtcommerceClearCache();
        break;
      case 'Ubercart':
        $this->ubercartClearCache();
        break;
      case 'Tomatocart':
        $this->tomatocartClearCache();
        break;
      case 'Virtuemart':
        $this->virtuemartClearCache();
        break;
      case 'Magento':
        $this->magentoClearCache();
        return;
      case 'Oxid':
        $this->oxidClearCache();
        break;
      case 'Xcart':
        $this->xcartClearCache();
        return;
      case 'Cscart':
        $this->cscartClearCache();
        break;
      case 'Merchium':
        $this->merchiumClearCache();
        break;
      case 'Kickstart':
        $this->kickstartClearCache($bridge->link);
        break;
      case 'Woocommerce':
        $this->woocommerceClearCache($bridge);
        break;
      case 'Zoey':
        $this->zoeyClearCache();
        break;
    }
    echo 'OK!';
  }

  /**
   * @param array  $dirs        dirs
   * @param string $fileExclude file
   *
   * @return bool
   */
  public function removeGarbage($dirs = array(), $fileExclude = '')
  {
    $result = true;

    foreach ($dirs as $dir) {
      if (!$this->_removeDirRec($dir, false, $fileExclude)) {
        $result = false;
      }
    }

    return $result;
  }

  /**
   * clear Magento cache
   *
   * @return void
   */
  public function magentoClearCache()
  {
    chdir('../');
    $phpExecutable = getPHPExecutable();
    $removeDirStatus = true;
    $execStatus = false;
    if ($phpExecutable) {

      $memoryLimit = '-d memory_limit=1024M';

      // MAGENTO 2.X
      if (file_exists(M1_STORE_BASE_DIR . 'app/etc/env.php')) {

        $indexer = "nohup $phpExecutable $memoryLimit bin/magento indexer:reindex;";
        $imagesResize = "nohup $phpExecutable $memoryLimit bin/magento catalog:images:resize;";
        $clearCache = "nohup $phpExecutable $memoryLimit bin/magento cache:flush;";
        $rmCache = "nohup rm -rf var/cache;";

        // $removeDirStatus = $this->_removeDirRec('var/cache', false);
        @exec("($indexer $imagesResize $clearCache $rmCache) &>/dev/null &");

        //$execStatus = $this->checkMagentoOutPut($outPut);
      } else {
        @exec("nohup $phpExecutable shell/indexer.php --reindexall > /dev/null 2>/dev/null & echo $!");
        //$execStatus = $this->checkMagentoOutPut($outPut);
      }
    } else {
      echo 'Error: can not find PHP executable file.';
    }

    if ($execStatus && $removeDirStatus) {
      echo 'OK!';
    } else {
      echo 'ERROR';
    }
  }

  /**
   * @param $outPut
   *
   * @return bool
   */
  public function checkMagentoOutPut($outPut)
  {
    $statusOkTexts = array(
      'been rebuilt successfully',
      'index was rebuilt successfully',
      'cache types',
      'config',
      'layout',
      'block_html',
      'collections',
      'reflection',
      'db_ddl',
      'eav',
      'customer_notification',
      'full_page',
      'config_integration',
      'config_integration_api',
      'translate',
      'config_webservice',
      'images resized successfully',
    );
    $result = true;

    foreach ($outPut as $key => $row) {
      $rowCheck = false;
      foreach ($statusOkTexts as $statusOkText) {
        if (strpos($row, $statusOkText) !== false) {
          $rowCheck = true;
          break;
        }
      }

      if (!$rowCheck) {
        $result = false;
      }
    }

    return $result;
  }

  /**
   * clear Interspire cache
   *
   * @return void
   */
  public function interspireClearCache()
  {
    $res = true;
    $file = M1_STORE_BASE_DIR . 'cache' . DIRECTORY_SEPARATOR . 'datastore' . DIRECTORY_SEPARATOR . 'RootCategories.php';
    if (@file_exists($file)) {
      if (!unlink($file)) {
        $res = false;
      }
    }

    if ($res === true) {
      echo 'OK';
    } else {
      echo 'ERROR';
    }
  }

  /**
   * clear CubeCart cache
   *
   * @return void
   */
  public function cubecartClearCache()
  {
    $ok = true;

    if (@file_exists(M1_STORE_BASE_DIR . 'cache')) {
      $dirHandle = opendir(M1_STORE_BASE_DIR . 'cache/');

      while (false !== ($file = readdir($dirHandle))) {
        if ($file != '.' && $file != '..' && !preg_match("/^index\.html?$/", $file) && !preg_match("/^\.htaccess?$/", $file) ) {
          if (is_file(M1_STORE_BASE_DIR . 'cache/' . $file)) {
            if (!unlink(M1_STORE_BASE_DIR . 'cache/' . $file)) {
              $ok = false;
            }
          }
        }
      }

      closedir($dirHandle);
    }

    if (@file_exists(M1_STORE_BASE_DIR . 'includes/extra/admin_cat_cache.txt')) {
      unlink(M1_STORE_BASE_DIR . 'includes/extra/admin_cat_cache.txt');
    }

    if ($ok) {
      echo 'OK';
    } else {
      echo 'ERROR';
    }
  }

  /**
   * clear Prestashop cache
   *
   * @return void
   */
  public function prestashopClearCache()
  {
    $dirs = array(
      M1_STORE_BASE_DIR . 'tools/smarty/compile/',
      M1_STORE_BASE_DIR . 'tools/smarty/cache/',
      M1_STORE_BASE_DIR . 'img/tmp/',
      M1_STORE_BASE_DIR . 'cache/smarty/compile/',
      M1_STORE_BASE_DIR . 'cache/smarty/cache/',
    );

    if ($this->removeGarbage($dirs, 'index.php')) {
      echo 'OK!';
    } else {
      echo 'ERROR';
    }
  }

  /**
   * clear OpenCart cache
   *
   * @return void
   */
  public function opencartClearCache()
  {
    $dirs = array(
      M1_STORE_BASE_DIR . 'system/cache/',
    );

    $this->removeGarbage($dirs, 'index.html');
  }

  /**
   * clear Xtcommerce cache
   *
   * @return void
   */
  public function xtcommerceClearCache()
  {
    $dirs = array(
      M1_STORE_BASE_DIR . 'cache/',
    );

    if (!$this->removeGarbage($dirs, 'index.html')) {
      echo PHP_EOL, 'Warning! Cache was not completely cleared!', PHP_EOL;
    }
  }

  /**
   * clear UberCart cache
   *
   * @return void
   */
  public function ubercartClearCache()
  {
    $dirs = array(
      M1_STORE_BASE_DIR . 'sites/default/files/imagecache/product/',
      M1_STORE_BASE_DIR . 'sites/default/files/imagecache/product_full/',
      M1_STORE_BASE_DIR . 'sites/default/files/imagecache/product_list/',
      M1_STORE_BASE_DIR . 'sites/default/files/imagecache/uc_category/',
      M1_STORE_BASE_DIR . 'sites/default/files/imagecache/uc_thumbnail/',
    );

    $this->removeGarbage($dirs);
  }

  /**
   * clear TomatoCart cache
   *
   * @return void
   */
  public function tomatocartClearCache()
  {
    $dirs = array(
      M1_STORE_BASE_DIR . 'includes/work/',
    );

    $this->removeGarbage($dirs, '.htaccess');
  }

  /**
   * Try to change permissions actually :)
   *
   * @return void
   */
  public function virtuemartClearCache()
  {
    $pathToImages = 'components/com_virtuemart/shop_image';

    $dirParts = explode('/', $pathToImages);
    $path = M1_STORE_BASE_DIR;
    foreach ($dirParts as $item) {
      if ($item == '') {
        continue;
      }

      $path .= $item . DIRECTORY_SEPARATOR;
      @chmod($path, 0755);
    }
  }

  /**
   * clear Oxid cache
   *
   * @return void
   */
  public function oxidClearCache()
  {
    $dirs = array(
      M1_STORE_BASE_DIR . 'tmp/',
    );

    $this->removeGarbage($dirs, '.htaccess');
  }

  /**
   * clear XCart cache
   *
   * @return void
   */
  public function xcartClearCache()
  {
    $dirs = array(
      M1_STORE_BASE_DIR . 'var/cache/',
      M1_STORE_BASE_DIR . 'var/datacache/',
      M1_STORE_BASE_DIR . 'var/log/',
      M1_STORE_BASE_DIR . 'var/resources/css/http/all',
    );

    $status = $this->removeGarbage($dirs, '.htaccess');

    @unlink(M1_STORE_BASE_DIR . 'var/.decorator.dbSchema.php');

    if ($status) {
      echo "OK!";
    } else {
      echo "ERROR";
    }
  }

  /**
   * clear CSCart cache
   *
   * @return void
   */
  public function cscartClearCache()
  {
    $dir = M1_STORE_BASE_DIR . 'var/cache/';
    $res = $this->_removeDirRec($dir, false);

    if ($res) {
      echo "OK", PHP_EOL;
    } else {
      echo "ERROR", PHP_EOL;
    }
  }

  /**
   * clear Merchium cache
   *
   * @return void
   */
  public function merchiumClearCache()
  {
    fn_clear_cache();
    echo 'OK';
  }

  /**
   * @param string $dir         directory
   * @param bool   $removeDir   need to be removed
   * @param string $fileExclude file exclude
   *
   * @return bool
   */
  protected function _removeDirRec($dir, $removeDir = true, $fileExclude = '')
  {
    if (!@file_exists($dir)) {
      return true;
    }

    $result = true;
    if ($objs = glob($dir . '/*')) {
      foreach ($objs as $obj) {
        if ((trim($fileExclude) != '') && strpos($obj, $fileExclude) !== false) {
          continue;
        }
        if (is_dir($obj)) {
          $this->_removeDirRec($obj, true, $fileExclude);
        } else {
          if (!@unlink($obj)) {
            $result = false;
          }
        }
      }
    }

    if ($removeDir && !@rmdir($dir)) {
      $result = false;
    }

    return $result;
  }

  /**
   * clear KickStart cache
   *
   * @param M1_Mysql|M1_Pdo $link db handle
   *
   * @return void
   */
  public function kickstartClearCache($link)
  {
    $res = $link->localQuery("SHOW TABLES LIKE 'cache%'");
    foreach ($res as $row) {
      foreach ($row as $value) {
        $link->localQuery("TRUNCATE TABLE " . $value);
      }
    }

    $link->localQuery("TRUNCATE TABLE watchdog");
  }

  /**
   * clear WooCommerce cache
   *
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function woocommerceClearCache($bridge)
  {
    $link = $bridge->link;
    $tablePrefix = trim($bridge->config->tablePrefix);

    // clear product attributes cache
    $link->localQuery("
    DELETE
    FROM
      " . $tablePrefix . "options
    WHERE
      option_name = '_transient_wc_attribute_taxonomies'"
    );

    // update TermsTaxonomy Count
    $link->localQuery("
      UPDATE
        " . $tablePrefix . "term_taxonomy tt
      SET
        tt.count =
        (
          SELECT
            COUNT(*) as total
          FROM
            " . $tablePrefix . "term_relationships r
          JOIN
            " . $tablePrefix . "posts p
          ON r.object_id = p.ID
          WHERE
            r.term_taxonomy_id = tt.term_taxonomy_id
            AND p.post_type = 'product'
            AND p.post_parent = ''
        )
      WHERE
        tt.taxonomy IN ('product_cat', 'product_type', 'product_tag', 'product_brand')"
    );
  }

  /**
   * clear Zoey Commerce cache
   *
   * @return void
   */
  public function zoeyClearCache()
  {
    chdir('../');

    $phpExecutable = getPHPExecutable();
    if ($phpExecutable) {
      @exec($phpExecutable . " shell/indexer.php --reindexall > /dev/null &");
    } else {
      echo 'Error: can not find PHP executable file.';
    }
  }
}

class M1_Bridge_Action_Batchsavefile extends M1_Bridge_Action_Savefile
{
  /**
   * save file to server
   *
   * @param M1_Bridge $bridge bridge class
   *
   * @return string
   */
  public function perform($bridge)
  {
    $result = array();
    foreach ($_POST['files'] as $fileInfo) {
      $result[$fileInfo['id']] = $this->_saveFile($fileInfo['source'], $fileInfo['target'], (int)$fileInfo['width'],
        (int)$fileInfo['height'], $fileInfo['local_source']);
    }

    echo serialize($result);
  }
}

class M1_Bridge_Action_Selfdelete
{
  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    $cache = new M1_Bridge_Action_Clearcache();
    if ($cache->removeGarbage(array(__DIR__)) && @rmdir(__DIR__)) {
      die('Deleted successfully');
    }
    die('Error occurred');
  }
}
class M1_Bridge_Action_Move
{
  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    $filesToMove = array(
      'bridge.php',
      'config.php',
    );
    $response = array(
      'isError' => false,
      'message' => 'OK',
    );

    if (isset($_POST['path'])) {
      if (!file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . $_POST['path'] . DIRECTORY_SEPARATOR . 'bridge2cart')) {
        mkdir(dirname(__FILE__) . DIRECTORY_SEPARATOR . $_POST['path'] . DIRECTORY_SEPARATOR . 'bridge2cart');
      }

      foreach ($filesToMove as $fileToMove) {
        if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . $fileToMove)) {
          $res = @rename(
            dirname(__FILE__) . DIRECTORY_SEPARATOR .  $fileToMove,
            dirname(__FILE__) . DIRECTORY_SEPARATOR . $_POST['path'] . DIRECTORY_SEPARATOR . 'bridge2cart' . DIRECTORY_SEPARATOR . $fileToMove
          );

          if (!$res) {
            $response['isError'] = true;
            $response['message'] = 'Failed moving ' . $fileToMove;
            break;
          }
        } else {
          $response['isError'] = true;
          $response['message'] = 'Cannot find file ' . $fileToMove . ' to move';
          break;
        }
      }

      if (!$response['isError']) {
        $files = glob('*');
        foreach ($files as $file) {
          unlink($file);
        }
        $dir = dirname(__FILE__);
        @rmdir($dir);
        $dir = explode(DIRECTORY_SEPARATOR, $dir);
        array_pop($dir);
        $dir = DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $dir);
        @unlink($dir . DIRECTORY_SEPARATOR . 'readme.html');
        @rmdir($dir);
      }
    }

    $this->_viewResponse($response);
  }

  /**
   * @param array $response response data
   *
   * @return void
   */
  protected function _viewResponse($response)
  {
    echo json_encode($response);
    die();
  }
}

class M1_Bridge_Action_Query
{
  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return bool
   */
  public function perform($bridge)
  {
    if (isset($_POST['query']) && isset($_POST['fetchMode'])) {

      $query = gzinflate(base64_decode($_POST['query']));

      $dbHandle = $bridge->getLink();
      if (isset($_GET['disable_checks'])) {
        $dbHandle->localQuery('SET SESSION FOREIGN_KEY_CHECKS=0');
        $dbHandle->localQuery("SET SESSION SQL_MODE='NO_AUTO_VALUE_ON_ZERO'");
      }

      if (isset($_REQUEST['set_names'])) {
        $dbHandle->localQuery("SET NAMES '" . ($_REQUEST['set_names']) . "'");
        $dbHandle->localQuery("SET CHARACTER SET '" . ($_REQUEST['set_names']) . "'");
        $dbHandle->localQuery("SET CHARACTER_SET_CONNECTION = '" . ($_REQUEST['set_names']) . "'");
      }

      $res = $bridge->query($query, (int)$_POST['fetchMode']);

      if (is_array($res['result']) || is_bool($res['result'])) {

        $result = serialize(
          array(
            'res'           => $res['result'],
            'fetchedFields' => @$res['fetchedFields'],
            'insertId'      => $bridge->getLink()->getLastInsertId(),
            'affectedRows'  => $bridge->getLink()->getAffectedRows(),
          )
        );

        echo base64_encode(gzdeflate($result));
      } elseif(strstr($query, '__REPLACE__')) {
        preg_match('/\/\*(.*)\*\/(.*)/s', $query, $matches);
        if (isset($matches[1]) && isset($matches[2])) {
          $query = trim($matches[2]);
          $replaces = explode('__END_REPLACE__', $matches[1]);
          foreach ($replaces as $replace) {
            if (!$replace) {
              continue;
            }

            if (strstr($replace, '__REPLACE__')) {
              list($from, $to) = explode('__REPLACE__', $replace);
              $query = str_replace($from, $to, $query);
            }
          }

          $_POST['query'] = base64_encode(gzdeflate($query));
          $this->perform($bridge);
          return;
        }
      } elseif(($id = M1_Bridge_Utils::checkDuplicateKey($bridge, $query, $res['message'])) !== false) {
        $result = serialize(
          array(
            'res'           => true,
            'fetchedFields' => array(),
            'insertId'      => $id,
            'affectedRows'  => 1,
          )
        );

        echo base64_encode(gzdeflate($result));
      } else {
        echo base64_encode(gzdeflate($res['message']));
      }
    } else {
      return false;
    }
  }
}

class M1_Bridge_Action_Basedirfs
{
  /**
   * return base dir name
   *
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    echo M1_STORE_BASE_DIR;
  }
}

class M1_Bridge_Action_Mysqlver
{
  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    $match = array();
    preg_match('/^(\d+)\.(\d+)\.(\d+)/', $bridge->getLink()->getServerInfo(), $match);
    echo sprintf("%d%02d%02d", $match[1], $match[2], $match[3]);
  }
}

class M1_Bridge_Action_Apachemodules
{
  /**
   * @param M1_Bridge $bridge bridge class
   *
   * @return void
   */
  public function perform($bridge)
  {
    echo '<pre>';
    print_r(apache_get_modules());
    echo '</pre>';
  }
}

header("X-Robots-Tag: noindex");

define('M1_BRIDGE_VERSION', '21');
define('M1_BRIDGE_DOWNLOAD_LINK', 'https://app.shopping-cart-migration.com/api.get.bridge');
define('M1_BRIDGE_DIRECTORY_NAME', basename(getcwd()));

@ini_set('display_errors', 0);

error_reporting(E_ERROR);

require_once 'config.php';

/**
 * @param array $array array to strip slashes
 *
 * @return array|string
 */
function stripSlashesArray($array)
{
  return is_array($array) ? array_map('stripSlashesArray', $array) : stripslashes($array);
}

/**
 * @return bool|string
 */
function getPHPExecutable()
{
  $paths = explode(PATH_SEPARATOR, getenv('PATH'));
  $paths[] = PHP_BINDIR;
  foreach ($paths as $path) {
    // we need this for XAMPP (Windows)
    if (isset($_SERVER["WINDIR"]) && strstr($path, 'php.exe') && @file_exists($path) && is_file($path)) {
      return $path;
    } else {
      $phpExecutable = $path . DIRECTORY_SEPARATOR . "php" . (isset($_SERVER["WINDIR"]) ? ".exe" : "");
      if (@file_exists($phpExecutable) && is_file($phpExecutable)) {
        return $phpExecutable;
      }
    }
  }

  return false;
}

if (!isset($_SERVER)) {
  $_GET = &$HTTP_GET_VARS;
  $_POST = &$HTTP_POST_VARS;
  $_ENV = &$HTTP_ENV_VARS;
  $_SERVER = &$HTTP_SERVER_VARS;
  $_COOKIE = &$HTTP_COOKIE_VARS;
  $_REQUEST = array_merge($_GET, $_POST, $_COOKIE);
}

if (get_magic_quotes_gpc()) {
  $_COOKIE = stripSlashesArray($_COOKIE);
  $_FILES = stripSlashesArray($_FILES);
  $_GET = stripSlashesArray($_GET);
  $_POST = stripSlashesArray($_POST);
  $_REQUEST = stripSlashesArray($_REQUEST);
}

define('M1_STORE_BASE_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

if (!isset($_GET['action']) || $_GET['action'] != 'move') {
  $adapter = new M1_Config_Adapter();
  $bridge = new M1_Bridge($adapter->create());
} else {
  $bridge = new M1_Bridge(array());
}

$bridge->run();?>