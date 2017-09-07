<?php

// autoload_real.php @generated by Composer

class WbsVendors_ComposerAutoloaderInitd4dac02398e5127358a70ccd5efd6e4b
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('WbsVendors\Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('WbsVendors_ComposerAutoloaderInitd4dac02398e5127358a70ccd5efd6e4b', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \WbsVendors\Composer\Autoload\ClassLoader();
        spl_autoload_unregister(array('WbsVendors_ComposerAutoloaderInitd4dac02398e5127358a70ccd5efd6e4b', 'loadClassLoader'));

        $useStaticLoader = PHP_VERSION_ID >= 50600 && !defined('HHVM_VERSION') && (!function_exists('zend_loader_file_encoded') || !zend_loader_file_encoded());
        if ($useStaticLoader) {
            require_once __DIR__ . '/autoload_static.php';

            call_user_func(\WbsVendors\Composer\Autoload\ComposerStaticInitd4dac02398e5127358a70ccd5efd6e4b::getInitializer($loader));
        } else {
            $map = require __DIR__ . '/autoload_namespaces.php';
            foreach ($map as $namespace => $path) {
                $loader->set($namespace, $path);
            }

            $map = require __DIR__ . '/autoload_psr4.php';
            foreach ($map as $namespace => $path) {
                $loader->setPsr4($namespace, $path);
            }

            $classMap = require __DIR__ . '/autoload_classmap.php';
            if ($classMap) {
                $loader->addClassMap($classMap);
            }
        }

        $loader->register(true);

        if ($useStaticLoader) {
            $includeFiles = \WbsVendors\Composer\Autoload\ComposerStaticInitd4dac02398e5127358a70ccd5efd6e4b::$files;
        } else {
            $includeFiles = require __DIR__ . '/autoload_files.php';
        }
        foreach ($includeFiles as $fileIdentifier => $file) {
            composerRequired4dac02398e5127358a70ccd5efd6e4b($fileIdentifier, $file);
        }

        return $loader;
    }
}

function composerRequired4dac02398e5127358a70ccd5efd6e4b($fileIdentifier, $file)
{
    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        require $file;

        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;
    }
}
