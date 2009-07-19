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

        $q = "UPDATE linkdata SET linking_page_inlinks='$incoming_links',linking_page_pr='$pagerank' WHERE linking_page='$linking_page' LIMIT 1";

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
                $linking_page_title = mysql_escape_string($page['Title']);
                $linking_page       = mysql_escape_string($page['Url']);

                $pagerank = Google::get_pagerank($linking_page);

                $q = "SELECT COUNT(1) FROM linkdata WHERE linking_page = '$linking_page'";

                if ($db->query($q)->fetchColumn() == 0){
                    $db->exec("INSERT INTO linkdata VALUES('','$url','$linking_page','$linking_page_title','$pagerank','0')");
                } else {
                    $db->exec("UPDATE linkdata SET linking_page_inlinks='$incoming_links',linking_page_pr='$pagerank' WHERE linking_page='$linking_page' LIMIT 1");
                }

                $today          = date("Y-m-d");
                $incoming_links = Yahoo::get_inlink_count(array('query' => $linking_page));

                $db->exec("UPDATE linkdata SET linking_page_inlinks='$incoming_links' WHERE linking_page='$linking_page' LIMIT 1");
            }
        }

        // redirect to site subpage page
        header('HTTP/1.1 302 Found');
        header("Location: ". Options::get('base_url') ."site/page/?url=" . urlencode($url));
        exit();
    }

    public function display_site_page()
    {
        $url = mysql_escape_string($_GET['url']);

        $q = "SELECT * FROM linkdata WHERE url='$url' ORDER BY linking_page_inlinks DESC LIMIT 1000";

        $incoming_links = DB::connect()->query($q)->fetchAll();

        $this->template->url            = $url;
        $this->template->incoming_links = $incoming_links;

        $this->template->display('page.php');
    }

    public function site_update()
    {
        $db = DB::connect();

        $id    = intval($_GET['id']);
        $site  = Sites::get(array('id' => $id));
        $today = date("Y-m-d");

        $q = "SELECT * FROM page WHERE updated_at !='$today' AND url LIKE 'http://$site->domain%' ORDER BY id DESC LIMIT 5000";

        foreach ($db->query($q) as $row) {
            $url          = $row['url'];
            $inlink_count = Yahoo::get_inlink_count(array('query' => $url));
            $pagerank     = Google::get_pagerank($url);

            $db->exec("UPDATE page SET updated_at = '$today', inlink_count = '$inlink_count', pagerank = '$pagerank' WHERE url = '$url' LIMIT 1");
        }

        $pagerank = Google::get_pagerank($site->domain);
        $db->exec("UPDATE site SET pagerank = '$pagerank' WHERE id = '$site->id' LIMIT 1");

        // redirect to site display page
        header('HTTP/1.1 302 Found');
        header("Location: ". Options::get('base_url') ."site/?id=" . $site->id);
        exit();
    }

    public function site_create()
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
        $allowed = array('base_url', 'theme_path', 'timezone', 'pagination', 'yahoo_api_key');
        $options = array_intersect_key($_POST, array_fill_keys($allowed, true));

        foreach ($options as $name => $value) {
            Options::set($name, $value);
        }
    }
}
