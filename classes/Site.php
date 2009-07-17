<?php

class Site
{
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
