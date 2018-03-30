<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/25/18
 * Time: 6:32 PM
 */

namespace Plaid\Income;


use Plaid\Income\Income\Stream;
use Plaid\Plaid;

/**
 * Class Income
 *
 * @package Plaid\Income\Response
 */
class Income extends Plaid {

    /**
     * @var Stream[]
     */
    private $income_streams;

    /**
     * @var int
     */
    private $last_year_income;

    /**
     * @var int
     */
    private $last_year_income_before_tax;

    /**
     * @var int
     */
    private $projected_yearly_income;

    /**
     * @var int
     */
    private $projected_yearly_income_before_tax;

    /**
     * @var int
     */
    private $max_number_of_overlapping_income_streams;

    /**
     * @var int
     */
    private $number_of_income_streams;

    /**
     * Income constructor.
     *
     * @param \stdClass $raw_response
     */
    public function __construct(\stdClass $raw_response) {
        parent::__construct($raw_response);
        $this->loadIncomeStreams($this->getRawResponse()->income_streams);
        $this->last_year_income = $this->getRawResponse()->last_year_income;
        $this->last_year_income_before_tax = $this->getRawResponse()->last_year_income_before_tax;
        $this->projected_yearly_income = $this->getRawResponse()->projected_yearly_income;
        $this->projected_yearly_income_before_tax = $this->getRawResponse()->projected_yearly_income_before_tax;
        $this->max_number_of_overlapping_income_streams = $this->getRawResponse()->max_number_of_overlapping_income_streams;
        $this->number_of_income_streams = $this->getRawResponse()->number_of_income_streams;
    }

    /**
     * @param $raw_streams
     */
    private function loadIncomeStreams($raw_streams) {
        foreach($raw_streams as $stream) {
            $this->income_streams[] = new Stream($stream);
        }
    }

    /**
     * @return Stream[]
     */
    public function getIncomeStreams() {
        return $this->income_streams;
    }

    /**
     * @return int
     */
    public function getLastYearIncome() {
        return $this->last_year_income;
    }

    /**
     * @return int
     */
    public function getLastYearIncomeBeforeTax() {
        return $this->last_year_income_before_tax;
    }

    /**
     * @return int
     */
    public function getProjectedYearlyIncome() {
        return $this->projected_yearly_income;
    }

    /**
     * @return int
     */
    public function getProjectedYearlyIncomeBeforeTax() {
        return $this->projected_yearly_income_before_tax;
    }

    /**
     * @return int
     */
    public function getMaxNumberOfOverlappingIncomeStreams() {
        return $this->max_number_of_overlapping_income_streams;
    }

    /**
     * @return int
     */
    public function getNumberOfIncomeStreams() {
        return $this->number_of_income_streams;
    }
}