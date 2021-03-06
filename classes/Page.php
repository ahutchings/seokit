<?php

class Page
{
    /**
     * Populates the Page object with the most recent metrics.
     *
     * @return null
     */
    public function __construct()
    {
        $db = DB::connect();

        $q              = "SELECT value FROM page_data WHERE page_id = $this->id AND metric_id = 0 ORDER BY created_at DESC LIMIT 1";
        $pagerank       = $db->query($q)->fetchColumn();
        $this->pagerank = $pagerank;

        $q                  = "SELECT value FROM page_data WHERE page_id = $this->id AND metric_id = 1 ORDER BY created_at DESC LIMIT 1";
        $inlink_count       = $db->query($q)->fetchColumn();
        $this->inlink_count = $inlink_count;
    }

    /**
     * Updates PageRank and inlink count for the Page.
     *
     * @return null
     */
    public function update_statistics()
    {
        $db = DB::connect();

        $pagerank = Google::get_pagerank($this->url);
        $db->exec("INSERT INTO page_data VALUES($this->id, '0', NOW(), $pagerank)");

        $inlink_count = Yahoo::get_inlink_count(array('query' => $this->url));
        $db->exec("INSERT INTO page_data VALUES($this->id, '1', NOW(), $inlink_count)");
    }

    /**
     * Checks whether a page exists in the database
     *
     * @param string $url URL to check
     *
     * @return bool
     */
    public static function exists($url)
    {
        $q = "SELECT COUNT(1) FROM page WHERE url = '$url'";

        $exists = DB::connect()->query($q)->fetchColumn();

        return $exists;
    }
}
