<?php

class Scroogle
{
    public static $endpoint = 'http://www.scroogle.org/cgi-bin/nbbw.cgi';

    /**
     * Queries Scroogle for a given term.
     *
     * @param string $term  Search term or phrase
     * @param int    $page  Page offset
     * @param int    $limit Result URLs (20, 50, or 100)
     *
     * @return array Position-indexed array of URLs
     */
    public static function search($term, $page = 1, $limit = 100)
    {
        if ($limit == 20) {
            $limit = 2;
        } elseif ($limit == 50) {
            $limit = 5;
        } else {
            $limit = 1;
        }

        $query = array(
            'Gw' => $term,
            'n' => $limit,
        );

        if ($page > 1) {
            $query['z'] = $page - 1;
        }

        $ch = curl_init(self::$endpoint);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query, '', '&'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $results = curl_exec($ch);

        curl_close($ch);

        $results = self::parse_results($results);
    }

    /**
     * Scrapes Scroogle results page
     *
     * @param string $text SERP content
     *
     * @return array Position-indexed array of URLs
     */
    private static function parse_results($text)
    {
        $regex = '/^(\d{1,5})\. <A Href="(.*)">/';

        $results = array();

        foreach (explode("\n", $text) as $line) {
            if (preg_match($regex, $line, $matches)) {
                $results[$matches[1]] = $matches[2];
            }
        }

        return $results;
    }
}
