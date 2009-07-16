<?php

class Domain
{
    /*
     * Deletes a domain.
     *
     * @todo Make this work based on ID
     *
     * @return null
     */
    public function delete()
    {
        $db = DB::connect();

        $db->exec("DELETE FROM domain WHERE id = {$this->id} LIMIT 1");
        $db->exec("DELETE FROM urls WHERE url LIKE '%$this->domain%'");
        $db->exec("DELETE FROM linkdata WHERE url LIKE '%$this->domain%'");
    }
}
