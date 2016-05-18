<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3d812baf8e8dac3a74879c21f8a37f9e
{
    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 
            array (
                0 => __DIR__ . '/..' . '/psr/log',
            ),
            'PHPExcel' => 
            array (
                0 => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes',
            ),
        ),
        'D' => 
        array (
            'Ddeboer\\DataImport' => 
            array (
                0 => __DIR__ . '/..' . '/ddeboer/data-import/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit3d812baf8e8dac3a74879c21f8a37f9e::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
