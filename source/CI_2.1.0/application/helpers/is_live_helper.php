<?php
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /**
     *	This file extends the CI date_helper file in ./system/helpers/
     */

    /**
     * This method is for finding the due date of a given account, just pass the due day and (optional) format
     */
    if ( ! function_exists('isLive')) {
        function isLive() {
            return (base_url() == 'http://money.stretchnate.com');
        }
    }

    /* End of file N8_date_helper.php */
    /* Location: ./application/helpers/N8_date_helper.php */

