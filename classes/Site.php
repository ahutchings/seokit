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
        $q  = "SELECT * FROM page WHERE url LIKE 'http://$this->domain%'";

        $sth = DB::connect()->prepare($q);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Page', array());
        $sth->execute();

        $pages = $sth->fetchAll();

        $this->pages      = $pages;
        $this->thumb_path = $this->get_thumb_path();

    }

    /**
     * Retrieves and caches a domain thumbnail, and returns the path
     *
     * @return string
     */
    private function get_thumb_path()
    {
        $path = APP_PATH . '/cache/' . md5($this->domain) . '.jpg';

        // if the thumbnail doesn't exist or it's older than a week
        if (!file_exists($path) || filemtime($path) < (time() - 604800)) {
            $request  = 'http://www.shrinktheweb.com/xino.php?embed=1&STWAccessKeyId=';
            $request .= Options::get('stw_access_key');
            $request .= '&Size=sm&stwUrl=';
            $request .= $this->domain;

            $thumbnail = file_get_contents($request);

            file_put_contents($path, $thumbnail);

            trigger_error("Refreshed cached thumbnail for $this->domain.", E_USER_NOTICE);
        }

        $relative_path = str_replace(APP_PATH, '', $path);

        return $relative_path;
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

        $pagerank = Google::get_pagerank($domain);

        $db->exec("INSERT INTO site VALUES('', '$domain', '$pagerank')");

        // return an instance of the created site
        $id = $db->query('SELECT id FROM site ORDER BY id DESC LIMIT 1')->fetchColumn();

        $site = Sites::get(array('id' => $id));

        $site->refresh_pages();
        $site->update_page_statistics();

        return $site;
    }

    /**
     * Retrieves and stores site pages
     *
     * @return null
     */
    public function refresh_pages()
    {
        $site_url = 'http://' . $this->domain;
        $db       = DB::connect();

        // retrieve and add site pages
        for ($start = 1; $start <= 1000; $start += 100) {

            $params = array(
                'query' => $site_url,
                'start' => $start
            );

            $data = Yahoo::get_page_data($params);

            foreach ($data['ResultSet']['Result'] as $page) {
                $page_url   = $page['Url'];
                $page_title = $page['Title'];

                // check for existence
                if ($db->query("SELECT COUNT(1) FROM page WHERE url = '$page_url'")->fetchColumn() == 0) {
                    // insert the page
                    $db->exec("INSERT INTO page VALUES('','$page_url','$page_title')");
                }
            }
        }
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

        $db->exec("DELETE FROM inlink WHERE url LIKE '%$this->domain%'");
        $db->exec("DELETE FROM page_data WHERE page_id IN (SELECT id FROM page WHERE url LIKE '%$this->domain%')");
        $db->exec("DELETE FROM page WHERE url LIKE '%$this->domain%'");
        $db->exec("DELETE FROM site WHERE id = $this->id LIMIT 1");
    }

    /**
     * Updates PageRank for the site domain.
     *
     * @return null
     */
    public function update_statistics()
    {
        $pagerank = Google::get_pagerank($this->domain);

        DB::connect()->exec("UPDATE site SET pagerank = '$pagerank' WHERE id = '$this->id' LIMIT 1");
    }

    /**
     * Updates statistics for all site pages.
     *
     * @return null
     */
    public function update_page_statistics()
    {
        $db = DB::connect();

        foreach ($this->pages as $page) {
            $page->update_statistics();
        }
    }
}
