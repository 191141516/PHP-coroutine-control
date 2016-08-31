<?php

class AutoLoader {
    public static function AutoLoadCoroutine($class) {
        $prefix = 'Coroutine\\';

        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // Another namespace.
            return;
        }

        $class_name = substr($class, $len);

        $file = rtrim(__DIR__, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . strtr($class_name, '\\', DIRECTORY_SEPARATOR) . '.php';

        if (is_file($file)) {
            require $file;
        }
    }

    public static function AutoLoadService($class) {
        $prefix = 'Service\\';

        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // Another namespace.
            return;
        }

        $class_name = substr($class, $len);

        $file = rtrim(__DIR__, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . strtr($class_name, '\\', DIRECTORY_SEPARATOR) . '.php';

        if (is_file($file)) {
            require $file;
        }
    }
}

spl_autoload_register(array('AutoLoader', 'AutoLoadCoroutine'));
spl_autoload_register(array('AutoLoader', 'AutoLoadService'));

// end of script
