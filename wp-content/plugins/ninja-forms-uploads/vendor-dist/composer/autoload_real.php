<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit644d6f9ed1c8b00dabe33d95e9592b54
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('NF_FU_VENDOR\Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit644d6f9ed1c8b00dabe33d95e9592b54', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \NF_FU_VENDOR\Composer\Autoload\ClassLoader();
        spl_autoload_unregister(array('ComposerAutoloaderInit644d6f9ed1c8b00dabe33d95e9592b54', 'loadClassLoader'));

        $useStaticLoader = PHP_VERSION_ID >= 50600 && !defined('HHVM_VERSION') && (!function_exists('zend_loader_file_encoded') || !zend_loader_file_encoded());
        if ($useStaticLoader) {
            require_once __DIR__ . '/autoload_static.php';

            call_user_func(\NF_FU_VENDOR\Composer\Autoload\ComposerStaticInit644d6f9ed1c8b00dabe33d95e9592b54::getInitializer($loader));
        } else {
            $classMap = require __DIR__ . '/autoload_classmap.php';
            if ($classMap) {
                $loader->addClassMap($classMap);
            }
        }

        $loader->setClassMapAuthoritative(true);
        $loader->register(true);

        if ($useStaticLoader) {
            $includeFiles = NF_FU_VENDOR\Composer\Autoload\ComposerStaticInit644d6f9ed1c8b00dabe33d95e9592b54::$files;
        } else {
            $includeFiles = require __DIR__ . '/autoload_files.php';
        }
        foreach ($includeFiles as $fileIdentifier => $file) {
            composerRequire644d6f9ed1c8b00dabe33d95e9592b54($fileIdentifier, $file);
        }

        return $loader;
    }
}

function composerRequire644d6f9ed1c8b00dabe33d95e9592b54($fileIdentifier, $file)
{
    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        require $file;

        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;
    }
}
