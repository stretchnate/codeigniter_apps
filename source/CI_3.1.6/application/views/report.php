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
     * render the reports section of the view
     */
    public function generateView() {
        ?>
        <div id="report_container">
            <h1>Reports</h1>
            <div id="report_list"><?= $this->reportList(); ?></div>
            <div id="reports">
                <div id="chart"></div>
                <div id="report"></div>
            </div>
        </div>
        <?php
    }

    /**
     * @return string
     */
    private function reportList() {
        $output = '<h4>Spent by Category</h4><ul class="no-list-style">';
        while($this->accounts_iterator->valid()) {
            $output .= '<li>';
            $output .= '<a href="javascript:void(0)" onclick="Report.fetchSpent(';
            $output .= $this->accounts_iterator->current()->getAccountId();
            $output .= ', \''.$this->accounts_iterator->current()->getAccountName().'\')">';
            $output .= $this->accounts_iterator->current()->getAccountName();
            $output .= '</a></li>';
            $this->accounts_iterator->next();
        }
        $output .= '</ul>';

        return $output;
    }
}