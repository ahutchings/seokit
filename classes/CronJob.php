<?php

class CronJob
{
    private $now;

    /**
     * Constructor.
     *
     * @return null
     */
    public function __construct()
    {
        $this->now = time();
    }

    /**
     * Magic method to unserialize the callback.
     *
     * @param string $name Variable name
     *
     * @return array Callback
     */
    public function __get($name)
    {
        if ($name == 'callback') {
            trigger_error('unserializing callback');
            return unserialize($this->callback);
        }
    }

    /**
     * Executes the CronJob.
     *
     * @return null
     */
    public function execute()
    {
        $callback = unserialize($this->callback);

        $result = call_user_func($callback);

        $q = "UPDATE crontab SET"
        	. " result = $result,"
        	. " last_run = NOW(),"
        	. " next_run = ADDTIME(FROM_UNIXTIME($this->now), FROM_UNIXTIME(increment))"
        	. " WHERE id = $this->id";

    	DB::connect()->exec($q);
    }
}
