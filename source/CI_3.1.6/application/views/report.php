<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 7/16/2018
 * Time: 7:18 PM
 */

require_once(APPPATH.'/views/budget/baseVW.php');

class ReportView extends Budget_BaseVW {

    /**
     * @var \Budget\AccountIterator
     */
    private $accounts_iterator;

    public function __construct(&$CI, \Budget\AccountIterator $accounts) {
        parent::__construct($CI);

        $this->accounts_iterator = $accounts;
    }

    /**
     * @return string
     */
    public function generateView() {
        $contents = '<div id="report_container">
            <div id="report_list">'.$this->reportList().'</div>
            <div id="chart"></div>
            <div id="report"></div>
        </div>';

        return $contents;
    }

    /**
     * @return string
     */
    private function reportList() {
        $output = '<ul>';
        while($this->accounts_iterator->valid()) {
            $output .= '<li>';
            $output .= '<a href="javascript:void(0)" onclick="Report.fetchSpent('.$this->accounts_iterator->current()->getAccountId().')">';
            $output .= $this->accounts_iterator->current()->getAccountName().' Spent';
            $output .= '</a></li>';
            $this->accounts_iterator->next();
        }
        $output .= '</ul>';

        return $output;
    }
}