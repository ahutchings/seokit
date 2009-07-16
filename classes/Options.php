<?php

class Options
{
    public static function get($name)
    {
        $q = 'SELECT value FROM `option` WHERE name = ?';

        try {

            $sth = DB::connect()->prepare($q);

            $sth->execute(array($name));

            $value = $sth->fetchColumn();

        } catch (PDOException $e) {

            trigger_error($e->getMessage(), E_USER_ERROR);

            return false;

        }

        return $value;
    }

    public static function set($name, $value = '')
    {
        $q = 'UPDATE `option` SET value = ? WHERE name = ?';

        try {

            $sth = DB::connect()->prepare($q);

            $sth->execute(array($value, $name));

            if ($sth->rowCount() == 0) {

                // creating a new name/value pair

                $q = 'INSERT INTO `option` (name, value) VALUES (?, ?)';

                $sth = DB::connect()->prepare($q);

                $sth->execute(array($name, $value));

            }

        } catch (PDOException $e) {

            trigger_error($e->getMessage(), E_USER_ERROR);

            return false;
        }

        return true;
    }
}
