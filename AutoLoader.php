<?php

class AutoLoader {
    public static function load($class) {
        $classPath = __dir__ . DIRECTORY_SEPARATOR . $class . '.php';

        var_dump($classPath);
        if (is_file($classPath)) {
            require $classPath;
        }
    }
}

spl_autoload_register(array('AutoLoader', 'load'));

// end of script
