<?php

class AutoLoader {
    public static function AutoLoadCoroutine($class) {
        $namespace = 'Coroutine';
        $prefix = $namespace . '\\';

        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // Another namespace.
            return;
        }

        $class_name = substr($class, $len);

        $file = rtrim(__DIR__, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . strtr($class_name, '\\', DIRECTORY_SEPARATOR) . '.php';
var_dump($file);
        if (is_file($file)) {
            require $file;
        }
    }

    public static function AutoLoadService($class) {
        $namespace = 'Service';
        $prefix = $namespace . '\\';

        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // Another namespace.
            return;
        }

        $class_name = substr($class, $len);

        $file = rtrim(__DIR__, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . strtr($class_name, '\\', DIRECTORY_SEPARATOR) . '.php';

        if (is_file($file)) {
            require $file;
        }
    }
}

spl_autoload_register(array('AutoLoader', 'AutoLoadCoroutine'));
spl_autoload_register(array('AutoLoader', 'AutoLoadService'));

// end of script
