<?php











namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-trunk',
    'version' => 'dev-trunk',
    'aliases' => 
    array (
    ),
    'reference' => '8da9e1a883488306cf489ef4357ff63c39cae28c',
    'name' => 'woocommerce/payments',
  ),
  'versions' => 
  array (
    'automattic/jetpack-a8c-mc-stats' => 
    array (
      'pretty_version' => 'v1.4.10',
      'version' => '1.4.10.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f24331c8cc098e81225c131e3d25f7df85ee9cd7',
    ),
    'automattic/jetpack-assets' => 
    array (
      'pretty_version' => 'v1.15.0',
      'version' => '1.15.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c49569503e992a28ac8b6bff1d62f0845e58c3d8',
    ),
    'automattic/jetpack-autoloader' => 
    array (
      'pretty_version' => 'v2.10.10',
      'version' => '2.10.10.0',
      'aliases' => 
      array (
      ),
      'reference' => '8060f5c5822671b146ce8f57380193c8fb8e2571',
    ),
    'automattic/jetpack-config' => 
    array (
      'pretty_version' => 'v1.5.3',
      'version' => '1.5.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '3526c284d3b845d57275fe1f64fbb2c71649b16e',
    ),
    'automattic/jetpack-connection' => 
    array (
      'pretty_version' => 'v1.30.13',
      'version' => '1.30.13.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b6cb5e87ffe1dca5260307066f720f8ddcfebda3',
    ),
    'automattic/jetpack-constants' => 
    array (
      'pretty_version' => 'v1.6.13',
      'version' => '1.6.13.0',
      'aliases' => 
      array (
      ),
      'reference' => '5a14050ea6d27d92cba3cfcf76d02a8235912d1a',
    ),
    'automattic/jetpack-heartbeat' => 
    array (
      'pretty_version' => 'v1.3.15',
      'version' => '1.3.15.0',
      'aliases' => 
      array (
      ),
      'reference' => '990678483baf84ed18545782c8a2b1c324297d91',
    ),
    'automattic/jetpack-options' => 
    array (
      'pretty_version' => 'v1.14.1',
      'version' => '1.14.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a87629f83f0486ef505e3651ea6bf8c9412d2274',
    ),
    'automattic/jetpack-redirect' => 
    array (
      'pretty_version' => 'v1.7.8',
      'version' => '1.7.8.0',
      'aliases' => 
      array (
      ),
      'reference' => '09bab23e80bbb397986accfee6129f4c54fe935b',
    ),
    'automattic/jetpack-roles' => 
    array (
      'pretty_version' => 'v1.4.12',
      'version' => '1.4.12.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a39955125dde3480bcc111482e80ede86e6d3ccb',
    ),
    'automattic/jetpack-status' => 
    array (
      'pretty_version' => 'v1.9.4',
      'version' => '1.9.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '14da3bae567c96a8ea11adaeb37c276999fcf701',
    ),
    'automattic/jetpack-terms-of-service' => 
    array (
      'pretty_version' => 'v1.9.17',
      'version' => '1.9.17.0',
      'aliases' => 
      array (
      ),
      'reference' => '425420572afec29d8afe22561c7fc29579efa543',
    ),
    'automattic/jetpack-tracking' => 
    array (
      'pretty_version' => 'v1.13.19',
      'version' => '1.13.19.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd4aeda56969750ed2724831057707b6bf3a424a9',
    ),
    'composer/installers' => 
    array (
      'pretty_version' => 'v1.10.0',
      'version' => '1.10.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '1a0357fccad9d1cc1ea0c9a05b8847fbccccb78d',
    ),
    'myclabs/php-enum' => 
    array (
      'pretty_version' => '1.7.7',
      'version' => '1.7.7.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd178027d1e679832db9f38248fcc7200647dc2b7',
    ),
    'roundcube/plugin-installer' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'shama/baton' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'symfony/polyfill-php71' => 
    array (
      'pretty_version' => 'v1.20.0',
      'version' => '1.20.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '2d6cdeca7ea470e50db9e544c9ec4b1955036c22',
    ),
    'symfony/polyfill-php72' => 
    array (
      'pretty_version' => 'v1.23.0',
      'version' => '1.23.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '9a142215a36a3888e30d0a9eeea9766764e96976',
    ),
    'symfony/polyfill-php73' => 
    array (
      'pretty_version' => 'v1.23.0',
      'version' => '1.23.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'fba8933c384d6476ab14fb7b8526e5287ca7e010',
    ),
    'symfony/polyfill-php74' => 
    array (
      'pretty_version' => 'v1.23.0',
      'version' => '1.23.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a5d80cdf049bd3b0af6da91184a2cd37533c0fd8',
    ),
    'symfony/polyfill-php80' => 
    array (
      'pretty_version' => 'v1.23.1',
      'version' => '1.23.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '1100343ed1a92e3a38f9ae122fc0eb21602547be',
    ),
    'symfony/polyfill-php81' => 
    array (
      'pretty_version' => 'v1.23.0',
      'version' => '1.23.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e66119f3de95efc359483f810c4c3e6436279436',
    ),
    'woocommerce/payments' => 
    array (
      'pretty_version' => 'dev-trunk',
      'version' => 'dev-trunk',
      'aliases' => 
      array (
      ),
      'reference' => '8da9e1a883488306cf489ef4357ff63c39cae28c',
    ),
    'woocommerce/subscriptions-core' => 
    array (
      'pretty_version' => '1.6.2',
      'version' => '1.6.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'bc48cd0437fc4601643e72abc4a7cd9beffb35b5',
    ),
  ),
);
private static $canGetVendors;
private static $installedByVendor = array();







public static function getInstalledPackages()
{
$packages = array();
foreach (self::getInstalled() as $installed) {
$packages[] = array_keys($installed['versions']);
}


if (1 === \count($packages)) {
return $packages[0];
}

return array_keys(array_flip(\call_user_func_array('array_merge', $packages)));
}









public static function isInstalled($packageName)
{
foreach (self::getInstalled() as $installed) {
if (isset($installed['versions'][$packageName])) {
return true;
}
}

return false;
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

$ranges = array();
if (isset($installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = $installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['version'])) {
return null;
}

return $installed['versions'][$packageName]['version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getPrettyVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return $installed['versions'][$packageName]['pretty_version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getReference($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['reference'])) {
return null;
}

return $installed['versions'][$packageName]['reference'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getRootPackage()
{
$installed = self::getInstalled();

return $installed[0]['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
self::$installedByVendor = array();
}




private static function getInstalled()
{
if (null === self::$canGetVendors) {
self::$canGetVendors = method_exists('Composer\Autoload\ClassLoader', 'getRegisteredLoaders');
}

$installed = array();

if (self::$canGetVendors) {
foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
if (isset(self::$installedByVendor[$vendorDir])) {
$installed[] = self::$installedByVendor[$vendorDir];
} elseif (is_file($vendorDir.'/composer/installed.php')) {
$installed[] = self::$installedByVendor[$vendorDir] = require $vendorDir.'/composer/installed.php';
}
}
}

$installed[] = self::$installed;

return $installed;
}
}
