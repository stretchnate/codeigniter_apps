<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/13/18
 * Time: 9:15 PM
 */

abstract class _AjaxResponse extends CI_Controller {

    /**
     * @param $success
     * @param string|null $message
     * @param array|stdClass $data
     */
    protected function jsonResponse($success, $message = null, $data = []) {
        $response = new stdClass();
        $response->success = $success;
        if($message) {
            $response->message = $message;
        }
        if(!empty($data)) {
            $response->data = $data;
        }

        echo json_encode($response);
    }
}