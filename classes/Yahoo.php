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
}
