<?php

class Site
{
    /**
     * Populates pages variable with site subpages.
     *
     * @return null
     */
    public function __construct()
    {
        $q  = "SELECT * FROM page WHERE url LIKE 'http://$this->domain%' ORDER BY links DESC, id ASC";

        $pages = DB::connect()->query($q)->fetchAll();

        $this->pages = $pages;
    }

    /**
     * Checks whether a domain exists in the database
     *
     * @param string $domain Domain to check
     *
     * @return bool
     */
    public static function exists($domain)
    {
        $q = "SELECT COUNT(1) FROM site WHERE domain = '$domain'";

        $exists = DB::connect()->query($q)->fetchColumn();

        return $exists;
    }

    /**
     * Creates a Site object with PageRank and subpages
     *
     * @param array $paramarray Array of parameters
     *
     * @return Site object
     */
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

                $db->exec("INSERT INTO page VALUES('','$url','$title','0','','$pagerank')");
                $db->exec("UPDATE page SET checkdate='$today',links='$incoming_links' WHERE url='$url' LIMIT 1");
            }
        }

        // return an instance of the created site
        $q = 'SELECT id FROM site ORDER BY id DESC LIMIT 1';
        $id = $db->query($q)->fetchColumn();

        $site = Sites::get(array('id' => $id));

        return $site;
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
        $db->exec("DELETE FROM page WHERE url LIKE '%$this->domain%'");
        $db->exec("DELETE FROM linkdata WHERE url LIKE '%$this->domain%'");
    }
}
