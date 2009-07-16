<?php

class SEOKit
{
    /**
     * autoload method
     *
     * @param string $class Class name
     *
     * @return null
     */
    static function autoload($class)
    {
        require APP_PATH . '/classes/' . ucfirst($class) . '.php';
    }
}
