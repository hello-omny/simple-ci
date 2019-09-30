<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0d96dceacccc7347a6287ea693aab520
{
    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'core\\' => 5,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/core',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0d96dceacccc7347a6287ea693aab520::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0d96dceacccc7347a6287ea693aab520::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
