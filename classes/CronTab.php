<?php

class CronTab
{
    /**
     * Executes all cron jobs.
     *
     * @param bool $async Run asynchronously
     *
     * @return null
     */
    public static function run($async = false)
    {
        // check if it's time to run crons, and if crons are already running.
        $next_cron = Options::get('next_cron');
        if ($next_cron > time()
            || (Options::get('cron_running') && Options::get('cron_running') > microtime(true))
            ) {
            return;
        }

        // cron_running will timeout in 10 minutes
        // round cron_running to 4 decimals
        $run_time = microtime(true) + 600;
        $run_time = sprintf("%.4f", $run_time);
        Options::set('cron_running', $run_time);

        if ($async) {
            $url = Options::get('base_url') . 'cron?time=' . sprintf('%.6F', $run_time);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_exec($ch);
            curl_close($ch);
        } else {
            usleep(5000);

            if (Options::get('cron_running') != $run_time) {
                return;
            }

            trigger_error('Running cron.', E_USER_NOTICE);

            $q = "SELECT * FROM crontab WHERE next_run <= NOW()";

            $sth = DB::connect()->prepare($q);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'CronJob', array());
            $sth->execute();

            $cronjobs = $sth->fetchAll();

            foreach ($cronjobs as $cronjob) {
//                trigger_error('Executing cronjob: ' . $cronjob->name, E_USER_NOTICE);
                $cronjob->execute();
            }

            // set the next run time to the lowest next_run OR a max of one day.
            $next_cron = $db->query('SELECT TIMESTAMP(next_run) FROM crontab ORDER BY next_run ASC LIMIT 1')->fetchColumn();
            Options::set('next_cron', min(intval($next_cron), time() + 86400));
            Options::set('cron_running', false);
        }
    }

    /**
     * Handles asynchronous cron calls.
     *
     * @return null
     */
    function act_poll_cron()
    {
        $time = doubleval($_GET['time']);

        if ($time != Options::get('cron_running')) {
            return;
        }

        // allow script to run for 10 minutes
        set_time_limit(600);

        $db = DB::connect();

        $sth = $db->prepare("SELECT * FROM crontab WHERE next_run <= NOW()");
        $sth->setFetchMode(PDO::FETCH_CLASS, 'CronJob', array());
        $sth->execute();

        $cronjobs = $sth->fetchAll();

        foreach ($cronjobs as $cronjob) {
            $cronjob->execute();
        }

        // set the next run time to the lowest next_run OR a max of one day.
        $next_cron = $db->query('SELECT TIMESTAMP(next_run) FROM crontab ORDER BY next_run ASC LIMIT 1')->fetchColumn();
        Options::set('next_cron', min(intval($next_cron), time() + 86400));
        Options::set('cron_running', false);
    }
}
