<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/10/18
 * Time: 8:42 PM
 */

namespace Plaid\Metadata;


use Plaid\Plaid;

/**
 * Class Institution
 *
 * @package Plaid\Metadata
 */
class Institution extends Plaid {

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $institution_id;

    /**
     * Institution constructor.
     *
     * @param $raw_response
     */
    public function __construct($raw_response) {
        parent::__construct($raw_response);
        $this->name = $raw_response->name;
        $this->institution_id = $raw_response->institution_id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getInstitutionId() {
        return $this->institution_id;
    }


}