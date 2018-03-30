<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/25/18
 * Time: 7:12 PM
 */

namespace Plaid;


trait RequestId {

    /**
     * @var string
     */
    protected $request_id;

    /**
     * @return string
     */
    public function getRequestId() {
        return $this->request_id;
    }

    /**
     * @param string $request_id
     */
    public function setRequestId($request_id) {
        $this->request_id = $request_id;
    }
}