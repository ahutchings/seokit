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
        // displaying all domains
        if (!isset($_GET['domain'])) {
            $this->template->domains = Domains::get();
        }

        $this->template->display('domains.php');
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
