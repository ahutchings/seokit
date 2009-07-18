<?php

class Site
{
    public static function exists($domain)
    {
        $db = DB::connect();

        $q = "SELECT COUNT(1) FROM site WHERE domain = '$domain'";

        $exists = $db->query($q)->fetchColumn();

        return $exists;
    }

    public static function create($paramarray)
    {
        $db     = DB::connect();
        $domain = parse_url($paramarray['url'], PHP_URL_HOST);

        if (self::exists($domain)) {
            trigger_error(sprintf('The domain %s already exists.', $domain), E_USER_ERROR);
            return false;
        }

        $pr = Google::get_pagerank($domain);

        $db->exec("INSERT INTO site VALUES('', '$domain', '$pr')");

        // retrieve and add site pages
        for ($start = 1; $start <= 1000; $start += 100) {

            $params = array(
                'query' => $paramarray['url'],
                'start' => $start
            );

            $data = Yahoo::get_page_data($params);

            foreach ($data['ResultSet']['Result'] as $page) {
                // insert the page, update pagerank and incoming link count
                $url            = $page['Url'];
                $title          = $page['Title'];
                $today          = date("Y-m-d");
                $incoming_links = Yahoo::get_inlink_count(array('query' => $url));
                $pagerank       = Google::get_pagerank($url);

                $db->exec("INSERT INTO urls VALUES('','$url','$title','0','','$pagerank')");
                $db->exec("UPDATE urls SET checkdate='$today',links='$incoming_links' WHERE url='$url' LIMIT 1");
            }
        }

        // @todo return an instance of the created site
    }

    /*
     * Deletes a site.
     *
     * @todo Make this work based on ID
     *
     * @return null
     */
    public function delete()
    {
        $db = DB::connect();

        $db->exec("DELETE FROM site WHERE id = {$this->id} LIMIT 1");
        $db->exec("DELETE FROM urls WHERE url LIKE '%$this->domain%'");
        $db->exec("DELETE FROM linkdata WHERE url LIKE '%$this->domain%'");
    }
}
