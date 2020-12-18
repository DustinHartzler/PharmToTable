<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite720f7eabe203ab4827b3dd01fddba41
{
    public static $prefixLengthsPsr4 = array (
        'o' => 
        array (
            'oldmine\\RelativeToAbsoluteUrl\\Tests\\' => 36,
            'oldmine\\RelativeToAbsoluteUrl\\' => 30,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'oldmine\\RelativeToAbsoluteUrl\\Tests\\' => 
        array (
            0 => __DIR__ . '/..' . '/oldmine/relative-to-absolute-url/tests',
        ),
        'oldmine\\RelativeToAbsoluteUrl\\' => 
        array (
            0 => __DIR__ . '/..' . '/oldmine/relative-to-absolute-url/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'K' => 
        array (
            'KubAT\\PhpSimple\\HtmlDomParser' => 
            array (
                0 => __DIR__ . '/..' . '/kub-at/php-simple-html-dom-parser/src',
            ),
        ),
    );

    public static $classMap = array (
        'KubAT\\PhpSimple\\HtmlDomParser' => __DIR__ . '/..' . '/kub-at/php-simple-html-dom-parser/src/KubAT/PhpSimple/HtmlDomParser.php',
        'oldmine\\RelativeToAbsoluteUrl\\RelativeToAbsoluteUrl' => __DIR__ . '/..' . '/oldmine/relative-to-absolute-url/src/RelativeToAbsoluteUrl.php',
        'oldmine\\RelativeToAbsoluteUrl\\Tests\\RelativeToAbsoluteUrlTest' => __DIR__ . '/..' . '/oldmine/relative-to-absolute-url/tests/oldmine/tests/RelativeToAbsoluteUrlTest.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite720f7eabe203ab4827b3dd01fddba41::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite720f7eabe203ab4827b3dd01fddba41::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInite720f7eabe203ab4827b3dd01fddba41::$prefixesPsr0;
            $loader->classMap = ComposerStaticInite720f7eabe203ab4827b3dd01fddba41::$classMap;

        }, null, ClassLoader::class);
    }
}
