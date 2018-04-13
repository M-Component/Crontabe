<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit489dff23cbb7ed9b0c9e152fb4a5ae61
{
    public static $files = array (
        'c964ee0ededf28c96ebd9db5099ef910' => __DIR__ . '/..' . '/guzzlehttp/promises/src/functions_include.php',
        'a0edc8309cc5e1d60e3047b5df6b7052' => __DIR__ . '/..' . '/guzzlehttp/psr7/src/functions_include.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
        '2c102faa651ef8ea5874edb585946bce' => __DIR__ . '/..' . '/swiftmailer/swiftmailer/lib/swift_required.php',
    );

    public static $firstCharsPsr4 = array (
        'U' => true,
        'P' => true,
        'J' => true,
        'G' => true,
    );

    public static $prefixDirsPsr4 = array (
        'Upyun\\' => 
        array (
            0 => __DIR__ . '/..' . '/upyun/sdk/src/Upyun',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Phalcon\\' => 
        array (
            0 => __DIR__ . '/..' . '/phalcon/incubator/Library/Phalcon',
        ),
        'JPush\\' => 
        array (
            0 => __DIR__ . '/..' . '/jpush/jpush/src/JPush',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PEAR' => 
            array (
                0 => __DIR__ . '/..' . '/pear/pear_exception',
            ),
        ),
    );

    public static $classMap = array (
        'Services_JSON' => __DIR__ . '/..' . '/pear/services_json/JSON.php',
        'Services_JSON_AssocArray_TestCase' => __DIR__ . '/..' . '/pear/services_json/Test-JSON.php',
        'Services_JSON_Empties_TestCase' => __DIR__ . '/..' . '/pear/services_json/Test-JSON.php',
        'Services_JSON_EncDec_TestCase' => __DIR__ . '/..' . '/pear/services_json/Test-JSON.php',
        'Services_JSON_Error' => __DIR__ . '/..' . '/pear/services_json/JSON.php',
        'Services_JSON_ErrorSuppression_TestCase' => __DIR__ . '/..' . '/pear/services_json/Test-JSON.php',
        'Services_JSON_NestedArray_TestCase' => __DIR__ . '/..' . '/pear/services_json/Test-JSON.php',
        'Services_JSON_Object_TestCase' => __DIR__ . '/..' . '/pear/services_json/Test-JSON.php',
        'Services_JSON_Spaces_Comments_TestCase' => __DIR__ . '/..' . '/pear/services_json/Test-JSON.php',
        'Services_JSON_UnquotedKeys_TestCase' => __DIR__ . '/..' . '/pear/services_json/Test-JSON.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->firstCharsPsr4 = ComposerStaticInit489dff23cbb7ed9b0c9e152fb4a5ae61::$firstCharsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit489dff23cbb7ed9b0c9e152fb4a5ae61::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit489dff23cbb7ed9b0c9e152fb4a5ae61::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit489dff23cbb7ed9b0c9e152fb4a5ae61::$classMap;

        }, null, ClassLoader::class);
    }
}
