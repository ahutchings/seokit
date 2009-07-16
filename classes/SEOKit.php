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
    public static function autoload($class)
    {
        require APP_PATH . '/classes/' . ucfirst($class) . '.php';
    }
}
