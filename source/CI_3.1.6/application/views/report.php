<?php

require_once(APPPATH.'/views/budget/baseVW.php');

/**
 * Class ReportView
 */
class ReportView extends Budget_BaseVW {

    /**
     * @var \Budget\AccountIterator
     */
    private $accounts;

    /**
     * ReportView constructor.
     *
     * @param       $CI
     * @param \Budget\AccountIterator $accounts
     */
    public function __construct(&$CI, \Budget\AccountIterator $accounts) {
        parent::__construct($CI);

        $this->accounts = $accounts;
    }

    /**
     * view
     */
    public function generateView() {
        $html = form_open('/', ['method' => 'POST', 'name' => 'report']);
        $html .= $this->buildReportTypeSelect();
        $html .= $this->buildAccountsSelect();
        $html .= "<div id='categories-container'>";
        $html .= $this->buildCategoriesSelect();
        $html .= "</div>";
        $html .= $this->buildDateFrom();
        $html .= $this->buildDateTo();
        $html .= form_submit('Submit', 'Run Report', 'class="btn btn-primary"');
        $html .= form_close();

        echo $html;
    }

    /**
     * @return string
     */
    public function buildCategoriesSelect(Budget_DataModel_AccountDM $account = null) {
        $categories = [];
        if($account) {
            foreach($account->getCategories() as $category) {
                $categories[$category->getCategoryId()] = $category->getCategoryName();
            }
        }
        $options = $this->buildOptions($categories);
        return "<div class='form-group'>
                    <select multiple name='categories' id='categories' class='form-control' required>
                        <option value=''>- - Categories - -</option>
                        $options
                    </select>
                </div>";
    }

    /**
     * @return string
     */
    private function buildDateTo() {
        return "<div class='form-group'>
            <div class='input-group date'>
                <input type='date' class='form-control' name='date-to' id='date-to' value='' autocomplete='off'>
                <div class='input-group-addon'>
                    <span class='glyphicon glyphicon-calendar'></span>
                </div>
            </div>
        </div>";
    }

    /**
     * @return string
     */
    private function buildDateFrom() {
        return "<div class='form-group'>
            <div class='input-group date'>
                <input type='date' class='form-control' name='date-from' id='date-from' value='' autocomplete='off'>
                <div class='input-group-addon'>
                    <span class='glyphicon glyphicon-calendar'></span>
                </div>
            </div>
        </div>";
    }

    /**
     * @return string
     */
    private function buildReportTypeSelect() {
        return "<div class='form-group'>
						<select name='report-type' id='report-type' class='form-control' required>
							<option value=''>- - Report Type - -</option>
						    <option value='debits'>Debits</option>
						    <option value='credits'>Credits</option>
						</select>
					</div>";
    }

    /**
     * @return string
     */
    private function buildAccountsSelect() {
        $list = [];
        while($this->accounts->valid()) {
            $list[$this->accounts->current()->getAccountId()] = $this->accounts->current()->getAccountName();
            $this->accounts->next();
        }

        $options = $this->buildOptions($list);
        return "<div class='form-group'>
                    <select name='account' id='account' class='form-control' required>
                        <option value=''>- - Account - -</option>
                        $options
                    </select>
                </div>";
    }

    /**
     * @param $list
     * @return string
     */
    private function buildOptions($list) {
        $options = [];
        foreach($list as $value => $text) {
            if(is_array($text)) {
                $options[] = "<optgroup label='$value'>";
                foreach($text as $id => $display) {
                    $options[] = "<option value='$id'>$display</option>";
                }
                $options[] = "</optgroup>";
            } else {
                $options[] = "<option value='$value'>$text</option>";
            }
        }

        return implode('', $options);
    }
}