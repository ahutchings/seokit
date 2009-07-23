<?php

class Yahoo
{
    public static function get_inlink_data($params)
    {
        $request_url = 'http://search.yahooapis.com/SiteExplorerService/V1/inlinkData?';

        $defaults = array(
            'appid' => Options::get('yahoo_api_key'),
            'omit_inlinks' => 'domain'
        );

        $params = array_merge($defaults, $params);

        $params['output'] = 'php';

        foreach ($params as $k => $v) {
            $request_url .= "&$k=$v";
        }

        $output = unserialize(file_get_contents($request_url));

        return $output;
    }

    public static function get_inlink_count($params)
    {
        $data = self::get_inlink_data($params);

        return $data['ResultSet']['totalResultsAvailable'];
    }

    public static function get_page_data($params)
    {
        $request_url = 'http://search.yahooapis.com/SiteExplorerService/V1/pageData?';

        $defaults = array(
            'appid' => Options::get('yahoo_api_key'),
            'results' => 100
        );

        $params = array_merge($defaults, $params);

        $params['output'] = 'php';

        foreach ($params as $k => $v) {
            $request_url .= "&$k=$v";
        }

        $response = file_get_contents($request_url);

        $data = unserialize($response);

        return $data;
    }

    /**
     * Queries Yahoo BOSS for a given term.
     *
     * @param string $term  Search term or phrase
     * @param int    $page  Page offset
     * @param int    $limit Result URLs (up to 50)
     *
     * @return obj Anonymous object
     */
    public static function search($term, $page = 1, $limit = 50)
    {
        $endpoint = 'http://boss.yahooapis.com/ysearch/web/v1/' . urlencode($term) . '?';

        $start = ($page - 1) * $limit;

        $params = array(
        	'appid' => Options::get('yahoo_api_key'),
            'start' => $start,
            'count' => $limit
        );

        $query = http_build_query($params, '', '&');

        $endpoint .= $query;

        $response = file_get_contents($endpoint);

        $result = json_decode($response);

        return $result;
    }

    /**
     * Get the ranking of a search term.
     *
     * @param string $term         Search term
     * @param string $domain       Domain
     * @param int    $max_position Maximum position to check
     *
     * @return int Term ranking (0 for not found)
     */
    public static function get_ranking($term, $domain, $max_position = 500)
    {
        $match_url = 'http://' . $domain;

        $max_pages = ($max_position / 50);

        for ($page = 1; $page <= $max_pages; $page++) {
            $results = self::search($term, $page);

            foreach ($results->ysearchresponse->resultset_web as $pos => $obj) {

                if (strpos($obj->url, $match_url) === 0) {
                    return $pos + 1;
                }
            }
        }

        return 0;
    }
}
