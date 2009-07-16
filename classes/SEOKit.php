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

    /**
    * error handler
    *
    * @param int    $level   Error level
    * @param string $message Error message
    * @param string $file    Filename the error was raised in
    * @param int    $line    Line number the error was raised at
    * @param array  $context Existing variables at the time the error was raised
    *
    * @return bool
    */
    public static function errorHandler($level, $message, $file, $line, $context)
    {
        $q = 'INSERT INTO log (level, file, line, message, created_at)'
            . ' VALUES (?, ?, ?, ?, FROM_UNIXTIME(?))';

        try {
            $sth = DB::connect()->prepare($q);

            $file = str_replace(APP_PATH, '', $file);

            $log = array($level, $file, $line, $message, time());

            $sth->execute($log);
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return true;
    }
}
