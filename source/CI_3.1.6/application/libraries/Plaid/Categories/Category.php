<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 5/15/2018
 * Time: 4:42 PM
 */

namespace Plaid\Categories;

use Plaid\Plaid;

class Category extends Plaid {

    use RequestId;

    /**
     * @var string
     */
    private $group;
    /**
     * @var array
     */
    private $hierarchy;
    /**
     * @var int
     */
    private $category_id;

    /**
     * Category constructor.
     *
     * @param $raw_response
     */
    public function __construct($raw_response) {
        parent::__construct($raw_response);
        $this->group = $this->getRawResponse()->group;
        $this->hierarchy = $this->getRawResponse()->hierarchy;
        $this->category_id = $this->getRawResponse()->category_id;
    }

    /**
     * @return string
     */
    public function getGroup() {
        return $this->group;
    }

    /**
     * @return array
     */
    public function getHierarchy() {
        return $this->hierarchy;
    }

    /**
     * @return int
     */
    public function getCategoryId() {
        return $this->category_id;
    }
}