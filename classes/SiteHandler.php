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
        if (!isset($_GET['domain'])) {
            // displaying all sites
            $this->template->sites = Sites::get();
        } else {
            // displaying single site
            $orderby = $_GET["orderby"];
            $this->template->checked = $_GET["checked"];
            $domain = mysql_escape_string($_GET["domain"]);

            $this->template->domain = $domain;

            $domain = "http://$domain";

            if (empty($orderby)) {
                $orderby = "links";
            }

            $q = "SELECT * FROM urls WHERE url LIKE '$domain%' ORDER BY $orderby DESC, id ASC";
            $this->template->site_pages = DB::connect()->query($q)->fetchAll();
        }

        $this->template->display('sites.php');
    }

    public function site_update()
    {
        if (empty($_GET['domain'])) {
            trigger_error('Please enter a domain to update', E_USER_ERROR);

            // redirect to index
            header('HTTP/1.1 302 Found');
            header("Location: " . Options::get('base_url'));
            exit();
        }

        $db = DB::connect();

        $domain = $_GET['domain'];

        $q   = "SELECT * FROM urls WHERE checkdate !='$today' AND url LIKE 'http://$domain%' ORDER BY id DESC LIMIT 5000";

        foreach ($db->query($q) as $row) {
            $url            = $row['url'];
            $incoming_links = Yahoo::get_inlink_count(array('query' => $url));
            $pr             = Google::get_pagerank($url);
            $today          = date("Y-m-d");

            $db->exec("UPDATE urls SET checkdate='$today', links='$incoming_links', pr='$pr' WHERE url='$url' LIMIT 1");
        }

        $pr = Google::get_pagerank($domain);
        $db->exec("UPDATE site SET pr='$pr' WHERE domain='$domain' LIMIT 1");

        // redirect to site display page
        header('HTTP/1.1 302 Found');
        header("Location: ". Options::get('base_url') ."?domain=" . $domain);
        exit();
    }

    public function site_create()
    {
        if (!isset($_POST['url']) || empty($_POST['url'])) {
            // @todo redirect to site create page
            trigger_error('Please enter a URL.', E_USER_ERROR);
            exit();
        }

        Site::create(array('url' => $_POST['url']));

        // @todo get the domain from the site create method results
        $domain = parse_url($_POST['url'], PHP_URL_HOST);

        // redirect to site display page
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ". Options::get('base_url') ."?domain=" . $domain);
        exit();
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
