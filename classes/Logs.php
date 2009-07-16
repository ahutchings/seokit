<?php

class Logs
{
    public static function get_count()
    {
        $q = 'SELECT COUNT(1) FROM log';

        $count = DB::connect()->query($q)->fetchColumn();

        return $count;
    }

    public static function get($paramarray = array())
    {
        // defaults
        $where  = array();
        $params = array();
        $limit  = is_numeric(Options::get('pagination')) ? Options::get('pagination') : 20;

        // extract overrides
        $allowed    = array('criteria', 'limit', 'offset', 'page');
        $paramarray = array_intersect_key($paramarray, array_fill_keys($allowed, true));
        extract($paramarray);

        if (isset($page) && is_numeric($page) ) {
            $offset = (intval($page) - 1) * intval($limit);
        }

        if (isset($criteria)) {
            $where[] = "(file LIKE CONCAT('%',?,'%') OR message LIKE CONCAT('%',?,'%'))";
            $params[] = $criteria;
            $params[] = $criteria;
        }

        $q = "SELECT * FROM log ";

        if (count($where)) {
            $q .= ' WHERE (' . implode(' AND ', $where) . ')';
        }

        $q .= " ORDER BY created_at DESC";
        $q .= " LIMIT $limit";

        if (isset($offset)) {
            $q .= " OFFSET $offset";
        }

        try {
            $sth = DB::connect()->prepare($q);

            $sth->setFetchMode(PDO::FETCH_CLASS, 'Log', array());

            $sth->execute($params);

            $logs = $sth->fetchAll();
        } catch (PDOException $e) {
            trigger_error($e->getMessage());

            return false;
        }

        return $logs;
    }
}
