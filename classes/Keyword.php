<?php

class Keyword
{
    public function __construct()
    {
        $this->set_rankings();
    }

    /**
     * Retrieves and stores current rankings for the keyword.
     *
     * @return null
     */
    public function update_rankings()
    {
        $db   = DB::connect();
        $site = Sites::get(array('id' => $this->site_id));

        $rank = Scroogle::get_ranking($this->text, $site->domain);

        $q = "INSERT INTO keyword_rank (site_id, search_engine_id, keyword_id, created_at, rank)"
        	. " VALUES ($site->id, 1, $this->id, NOW(), $rank)";

        $db->exec($q);

        $rank = Yahoo::get_ranking($this->text, $site->domain);

        $q = "INSERT INTO keyword_rank (site_id, search_engine_id, keyword_id, created_at, rank)"
        	. " VALUES ($site->id, 2, $this->id, NOW(), $rank)";

        $db->exec($q);
    }

    public function set_rankings()
    {
        $db = DB::connect();

        $q = "SELECT * FROM keyword_rank WHERE keyword_id = $this->id"
            . " AND search_engine_id = 1 ORDER BY created_at DESC LIMIT 1";

        $google = $db->query($q)->fetch(PDO::FETCH_OBJ);

        $q = "SELECT * FROM keyword_rank WHERE keyword_id = $this->id"
            . " AND search_engine_id = 2 ORDER BY created_at DESC LIMIT 1";

        $yahoo = $db->query($q)->fetch(PDO::FETCH_OBJ);

        $q = "SELECT * FROM keyword_rank WHERE keyword_id = $this->id"
            . " AND search_engine_id = 3 ORDER BY created_at DESC LIMIT 1";

        $bing = $db->query($q)->fetch(PDO::FETCH_OBJ);

        $this->rankings->google = $google;
        $this->rankings->yahoo  = $yahoo;
        $this->rankings->bing   = $bing;
    }
}
