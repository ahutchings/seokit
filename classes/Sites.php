<?php

class Sites
{
    /**
     * get a site or sites
     *
     * @param array $paramarray Query parameters
     *
     * @return array An array of Site objects, or a single Site object, depending on request
     */
    public static function get($paramarray = array())
    {
        // defaults
        $where    = array();
        $params   = array();
        $limit    = 20;
        $fetch_fn = 'fetchAll';

        // extract overrides
        $allowed    = array('criteria', 'limit', 'offset', 'page', 'id');
        $paramarray = array_intersect_key($paramarray, array_fill_keys($allowed, true));
        extract($paramarray);

        if (isset($id) && is_numeric($id)) {
            $where[]  = "id = ?";
            $params[] = $id;
            $fetch_fn = 'fetch';
        }

        if (isset($page) && is_numeric($page) ) {
            $offset = (intval($page) - 1) * intval($limit);
        }

        if (isset($criteria)) {
            $where[] = "domain LIKE CONCAT('%',?,'%')";
            $params[] = $criteria;
            $params[] = $criteria;
        }

        $q = "SELECT * FROM site ";

        if (count($where)) {
            $q .= ' WHERE (' . implode(' AND ', $where) . ')';
        }

        $q .= " ORDER BY domain ASC";
        $q .= " LIMIT $limit";

        if (isset($offset)) {
            $q .= " OFFSET $offset";
        }

        try {
            $sth = DB::connect()->prepare($q);

            $sth->setFetchMode(PDO::FETCH_CLASS, 'Site', array());

            $sth->execute($params);

            $domains = $sth->$fetch_fn();
        } catch (PDOException $e) {
            trigger_error($e->getMessage());

            return false;
        }

        return $domains;
    }
}