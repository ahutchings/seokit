<?php

class Domains
{
    /**
     * get a domain or domains
     *
     * @param array $paramarray Query parameters
     *
     * @return array An array of Domain objects, or a single Domain object, depending on request
     */
    public static function get($paramarray = array())
    {
        // defaults
        $where  = array();
        $params = array();
        $limit  = 20;

        // extract overrides
        $allowed    = array('criteria', 'limit', 'offset', 'page');
        $paramarray = array_intersect_key($paramarray, array_fill_keys($allowed, true));
        extract($paramarray);

        if (isset($page) && is_numeric($page) ) {
            $offset = (intval($page) - 1) * intval($limit);
        }

        if (isset($criteria)) {
            $where[] = "domain LIKE CONCAT('%',?,'%')";
            $params[] = $criteria;
            $params[] = $criteria;
        }

        $q = "SELECT * FROM domain ";

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

            $sth->setFetchMode(PDO::FETCH_CLASS, 'Domain', array());

            $sth->execute($params);

            $domains = $sth->fetchAll();
        } catch (PDOException $e) {
            trigger_error($e->getMessage());

            return false;
        }

        return $domains;
    }
}