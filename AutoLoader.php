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

        if (is_file($file)) {
            require $file;
        }
    }

    public static function AutoLoadServer($class) {
        $namespace = 'Server';
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

    public static function load($class) {
        $classPath = __dir__ . DIRECTORY_SEPARATOR . $class . '.php';

        if (is_file($classPath)) {
            require $classPath;
        }
    }
}

spl_autoload_register(array('AutoLoader', 'AutoLoadCoroutine'));
spl_autoload_register(array('AutoLoader', 'AutoLoadServer'));
spl_autoload_register(array('AutoLoader', 'load'));

// end of script
