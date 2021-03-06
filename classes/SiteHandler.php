<?php

class SiteHandler
{
    public $template = null;

    public function __construct()
    {
        $this->template = new Template();
    }

    public function display_sites()
    {
        $this->template->sites = Sites::get();
        $this->template->display('sites.php');
    }

    private function site_keyword_add()
    {
        $site_id = $_POST['id'];
        $keyword = $_POST['keyword'];

        $q = "INSERT INTO keyword (site_id, text) VALUES ($site_id, '$keyword')";

        DB::connect()->exec($q);
    }

    /**
     * Updates incoming link count and pagerank for an page incoming link.
     *
     * @return null
     */
    public function site_page_inlink_update()
    {
        $url = $_GET['url'];

        $linking_page   = mysql_escape_string($_GET["linking_page"]);
        $incoming_links = Yahoo::get_inlink_count(array('query' => $linking_page));
        $pagerank       = Google::get_pagerank($linking_page);

        $q = "UPDATE inlink SET inlink_count = '$incoming_links', pagerank = '$pagerank' WHERE url = '$linking_page' LIMIT 1";

        DB::connect()->exec($q);

        header('HTTP/1.1 302 Found');
        header("Location: " . Options::get('base_url') . "site/page?url=" . urlencode($url));
        exit();
    }

    /**
     * Retrieves and stores incoming links for a page, and
     * incoming link count and pagerank of each incoming link.
     *
     * @return null
     */
    public function site_page_update()
    {
        $db  = DB::connect();
        $url = $_GET['url'];

        for ($start = 1; $start <= 1000; $start += 100) {

            $params = array(
                'start' => $start,
                'query' => $url
            );

            $data = Yahoo::get_inlink_data($params);

            foreach ($data['ResultSet']['Result'] as $page) {
                $title        = mysql_escape_string($page['Title']);
                $linking_page = mysql_escape_string($page['Url']);

                $pagerank = Google::get_pagerank($linking_page);

                $q = "SELECT COUNT(1) FROM inlink WHERE url = '$linking_page'";

                if ($db->query($q)->fetchColumn() == 0){
                    $db->exec("INSERT INTO inlink VALUES('','$url','$linking_page','$title','$pagerank','0')");
                } else {
                    $db->exec("UPDATE inlink SET inlink_count='$incoming_links',pagerank='$pagerank' WHERE url = '$linking_page' LIMIT 1");
                }


                // @todo this should be a part of the preceding INSERT query
                $inlink_count = Yahoo::get_inlink_count(array('query' => $linking_page));

                $db->exec("UPDATE inlink SET inlink_count='$inlink_count' WHERE url = '$linking_page' LIMIT 1");
            }
        }

        // redirect to site subpage page
        header('HTTP/1.1 302 Found');
        header("Location: ". Options::get('base_url') ."site/page/?url=" . urlencode($url));
        exit();
    }

    public function display_site_keyword_add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->site_keyword_add();

            $keyword = $_POST['keyword'];

            trigger_error("Keyword '$keyword' added.", E_USER_NOTICE);

            header('HTTP/1.1 302 Found');
            header("Location: " . Options::get('base_url') . "site/?id=" . $_POST['id']);
            exit();
        }

        $site = Sites::get(array('id' => $_GET['id']));

        $this->template->site = $site;

        $this->template->display('site_keyword_add.php');
    }

    public function display_site_page()
    {
        $url = mysql_escape_string($_GET['url']);

        $q = "SELECT * FROM inlink WHERE page_url = '$url' ORDER BY inlink_count DESC LIMIT 1000";

        $incoming_links = DB::connect()->query($q)->fetchAll(PDO::FETCH_OBJ);

        $this->template->url            = $url;
        $this->template->incoming_links = $incoming_links;

        $this->template->display('page.php');
    }

    public function site_update()
    {
        $id   = intval($_GET['id']);
        $site = Sites::get(array('id' => $id));

        $site->update_page_statistics();
        $site->update_statistics();

        // redirect to site display page
        header('HTTP/1.1 302 Found');
        header("Location: ". Options::get('base_url') ."site/?id=" . $site->id);
        exit();
    }

    private function site_create()
    {
        if (!isset($_POST['url']) || empty($_POST['url'])) {
            // @todo redirect to site create page
            trigger_error('Please enter a URL.', E_USER_ERROR);
            exit();
        }

        $site = Site::create(array('url' => $_POST['url']));

        // redirect to site display page
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ". Options::get('base_url') ."site/?id=" . $site->id);
        exit();
    }

    private function site_create_from_sitemap()
    {
        $db     = DB::connect();
        $domain = parse_url($_POST['url'], PHP_URL_HOST);

        if (!Site::exists($domain)){
            $pagerank = Google::get_pagerank($domain);

            $db->exec("INSERT INTO site VALUES('', '$domain', '$pagerank')");
        }

        if (!$xml = simplexml_load_file($_POST['url'])) {
            trigger_error('Unable to load sitemap at ' . $_POST['url'], E_USER_ERROR);

            header('HTTP/1.1 302 Found');
            header("Location: " . Options::get('base_url'));
            exit();
        }

        foreach ($xml->url as $url) {
            if (!Page::exists($url->loc)){
                $db->exec("INSERT INTO page VALUES('', '$url->loc', '')");

                // @todo update page metrics
            }
        }
    }

    public function display_site()
    {
        $id = intval($_GET['id']);

        $site = Sites::get(array('id' => $id));

        $this->template->site = $site;

        $this->template->display('site.php');
    }

    public function display_site_create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->site_create();
            return;
        }

        $this->template->display('addsite.php');
    }

    public function display_site_create_from_page()
    {
        $this->template->display('addurl.php');
    }

    public function display_site_create_from_sitemap()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->site_create_from_sitemap();

            header('HTTP/1.1 302 Found');
            header("Location: " . Options::get('base_url'));
            exit();
        }

        $this->template->display('sitemap.php');
    }

    public function display_site_delete()
    {
        $site = Sites::get(array('id' => $_GET['id']));

        if (!isset($_GET['confirm']) || $_GET['confirm'] != "yes"){

            $this->template->site = $site;

            $this->template->display('delete.php');

        } else {

            $site->delete();

            header("HTTP/1.1 301 Moved Permanently");
            header("Location: /");
            exit();

        }
    }

    public function display_logs()
    {
        $params = array();

        if (isset($_GET['page'])) {
            $params['page']       = $_GET['page'];
            $this->template->page = $_GET['page'];
        }

        $this->template->logs  = Logs::get($params);
        $this->template->pager = SEOKit::paginate('Logs', $params, 'http://seokit.localhost/logs');

        $this->template->display('logs.php');
    }

    public function display_settings()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            self::update_settings();
        }

        $this->template->display('settings.php');
    }

    public function display_login()
    {
    }

    public function display_404()
    {
        header('HTTP/1.1 404 Not Found');
    }

    public function do_logout()
    {
    }

    public function update_settings()
    {
        $allowed = array('base_url', 'theme_path', 'timezone', 'pagination', 'stw_access_key', 'yahoo_api_key');
        $options = array_intersect_key($_POST, array_fill_keys($allowed, true));

        foreach ($options as $name => $value) {
            Options::set($name, $value);
        }
    }
}
