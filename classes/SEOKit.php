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

    public static function paginate($class, $paramarray, $url)
    {
        $limit     = isset($paramarray['limit']) ? $paramarray['limit'] : Options::get('pagination');
        $offset    = isset($paramarray['offset']) ? $paramarray['offset'] : 0;
        $current   = isset($paramarray['page']) ? $paramarray['page'] : ceil($offset / $limit);
        $current   = ($current == 0) ? 1 : $current;
        $has_query = (parse_url($url, PHP_URL_QUERY) == '') ? false : true;

        $class = new $class;

        // @todo base count on the params
        $total = ceil($class->get_count() / $limit);

        $out = array();

        if (isset($paramarray['page'])) {
            $offset = ($paramarray['page'] - 1) * $limit;
        }

        $out[] = '<ul class="pager">';

        if ($current > 1) {
            if ($has_query) {
                $prev_url = $url . '&page=' . ($current - 1);
            } else {
                $prev_url = $url . '?page=' . ($current - 1);
            }

            $out[] = '<li><a href="'.$prev_url.'">&#171; Prev</a></li>';
        }

        $pages[] = 1;

        for ($i = max($current - 2, 2); $i < $total && $i <= $current + 2; $i++ ) {
            $pages[] = $i;
        }

        if ($total > 1) {
            $pages[] = $total;
        }

        for ($i = 0, $n = count($pages); $i < $n; $i++) {
            $active = ($pages[$i] == $current) ? ' class="active"' : '';

            if ($has_query) {
                $page_url = $url . '&page=' . $pages[$i];
            } else {
                $page_url = $url . '?page=' . $pages[$i];
            }

            if ($pages[$i] > 1 && ($pages[$i] - $pages[$i - 1]) > 1) {
                $out[] = '<li>&#x2026;</li>';
            }

            $out[] = '<li'.$active.'><a href="'.$page_url.'">' . $pages[$i] . '</a></li>';
        }

        if ($current < $total) {

            if ($has_query) {
                $next_url = $url . '&page=' . ($current + 1);
            } else {
                $next_url = $url . '?page=' . ($current + 1);
            }

            $out[] = '<li><a href="'.$next_url.'">Next &#187;</a></li>';
        }

        $out[] = '</ul>';

        $out = implode('', $out);

        return $out;
    }

    /**
     * retrieves an array of valid timezones for date_default_timezone_set
     *
     * @return array
     */
    public static function getTimezones()
    {
        $cities = array();

        foreach (DateTimeZone::listAbbreviations() as $key => $zones) {
            foreach ($zones as $id => $zone) {
                if (preg_match('/^(America|Antartica|Arctic|Asia|Atlantic|Europe|Indian|Pacific)\//', $zone['timezone_id'])) {
                    $cities[$zone['timezone_id']][] = $key;
                }
            }
        }

        // for each city, have a comma separated list of all possible timezones for that city
        foreach ($cities as $k => $v) {
            $cities[$k] = implode( ',', $v);
        }

        // only keep one city (the first and also most important) for each set of possibilities
        $cities = array_unique($cities);

        // sort by area/city name
        ksort($cities);

        return array_keys($cities);
    }
}
