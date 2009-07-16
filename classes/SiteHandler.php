<?php

class SiteHandler
{
    public $template = null;

    public function __construct()
    {
        $this->template = new Template();
    }

    public function display_home()
    {
        if (!isset($_GET['domain'])) {
            // displaying all domains
            $this->template->domains = Domains::get();
        } else {
            // displaying single domain
            $orderby = $_GET["orderby"];
            $this->template->checked = $_GET["checked"];
            $domain  = mysql_escape_string($_GET["domain"]);

            $this->template->domain = $domain;

            $domain = "http://$domain";

            if (empty($orderby)) {
                $orderby = "links";
            }

            $q = "SELECT * FROM urls WHERE url LIKE '$domain%' ORDER BY $orderby DESC, id ASC";
            $this->template->domain_pages = DB::connect()->query($q)->fetchAll();
        }

        $this->template->display('domains.php');
    }

    public function display_domain_create()
    {
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            $db     = DB::connect();
            $domain = parse_url($_GET['url'], PHP_URL_HOST);
            $q      = "SELECT COUNT(1) FROM domain WHERE domain = '$domain'";

            if ($db->query($q)->fetchColumn() == 0){
                $pr = Google::get_pagerank($domain);

                $db->exec("INSERT INTO domain VALUES('','$domain','$pr')");
            }
        }

        $this->template->display('addsite.php');
    }

    public function display_domain_delete()
    {
        $domain = Domains::get(array('id' => $_GET['id']));

        if (!isset($_GET['confirm']) || $_GET['confirm'] != "yes"){

            $this->template->domain = $domain;

            $this->template->display('delete.php');

        } else {

            $domain->delete();

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
