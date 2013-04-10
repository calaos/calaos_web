<?php

        /**
         * Common defines and config options
         */

        //prevent direct file access
        if (!defined('ENNA_WWW'))
        {
                header('HTTP/1.1 403 Forbidden');
                die('Directory listing denied.');
        }

        define('PAGE_NAV_HOME', 1);
        define('PAGE_NAV_NETWORK', 2);
        define('PAGE_NAV_MUSIC_SOURCE', 3);
        define('PAGE_NAV_ABOUT', 4);

        $config = array();

        $config['sq_config_path'] = '/home/raoul/.squeezeplay/userpath/settings/';

        $config['sq_playback_file'] = 'Playback.lua';
        $config['sq_welcome_file'] = 'SetupWelcome.lua';

        $config['product_name'] = 'Enna-Box';
        $config['enna_version'] = '0.0.1';

?>