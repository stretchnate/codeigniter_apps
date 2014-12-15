<?php
    /**
     * This Class calculates the monthly need of an account based on the paySchedule and the
     * category due dates.
     *
     * @author stretch
     */
    class monthlyNeed {

        private $account_dm;
        private $total_necessary = 0.00;
        private $total           = 0.00;
        private $ci;
        private $deposit_dates;
        private $last_pay_date;

        private static $thirty_one_day_months = array(1,3,5,7,8,10,12);
        private static $thirty_day_months     = array(4,6,9,11);

        /**
         * gets the ci instance and calculates the monthly need for the account
         *
         * @param Budget_DataModel_AccountDM $account_dm
         */
        public function __construct(Budget_DataModel_AccountDM $account_dm) {
            $this->ci =& get_instance();

            $this->account_dm = $account_dm;
            $this->getLastPayrollDeposit();
            $this->calculateMonthlyNeed();
        }

        /**
         * attempts to determine the last date of a payroll deposit
         *
         * @return void
         */
        private function getLastPayrollDeposit() {
            $this->fetchDepositDates();
            $sorted_paydates = $this->scorePayDates();
            sort($sorted_paydates);
            $last = array_pop($sorted_paydates);

            if($last) {
                $last_pay_date = array_keys($last);
                $this->last_pay_date  = new DateTime($last_pay_date[0]);
            } else {
                //@todo - look farther back than 3 months if no paydates exist
                $this->last_pay_date = new DateTime();
            }
        }

        /**
         * fetches the deposit dates from the database
         *
         * @return void
         */
        private function fetchDepositDates() {
            $deposits_model = new Deposits();
            $deposits = $deposits_model->getDeposits(
                    $this->ci->session->userdata('user_id'),
                    $this->account_dm->getAccountId(),
                    date('Y/m/d', strtotime('-3 months')));

            foreach($deposits as $deposit) {
                $this->deposit_dates[] = $this->trimDate($deposit->date);
            }
        }

        /**
         * gives each deposit date a score based on how many other deposit dates are in a two week pattern with it
         * only goes backward from the deposit date being evaluated
         *
         * @return int
         */
        private function scorePayDates() {
            $i = 0;
            $weeks_back = array(' -2 weeks', ' -4 weeks', ' -6 weeks', ' -8 weeks', ' -10 weeks', ' -12 weeks');
            $pay_dates = array();

            //iterate through the deposit dates
            while(isset($this->deposit_dates[$i])) {
                $j = 0;
                $date = $this->deposit_dates[$i];
                //count back two weeks at a time to see which deposits have a two week pattern
                foreach($weeks_back as $weeks) {
                    //if the past date is in the array increase the score ($j) by one
                    if(in_array(strtotime($date . $weeks), $this->deposit_dates)) {
                        $j++;
                    }
                }
                //highest score ($j) will win
                $pay_dates[$date] = $j;
                $i++;
            }

            return $pay_dates;
        }

        /**
         * trims the timestamp off of a date
         *
         * @param string $date
         * @return string
         */
        private function trimDate($date) {
            $pattern = '/ \d{2}:\d{2}:\d{2}$/';
            return preg_replace($pattern, '', $date);
        }

        /**
         * calculates the monthly need based on category amount necessary values
         *
         * @return void
         */
        private function calculateMonthlyNeed() {
            foreach($this->account_dm->getCategories() as $category_dm){
                $this->total = $this->total + $category_dm->getCurrentAmount();
                if($category_dm->getDueDay() == 0) {
                    $category_need = $this->calcuateZeroDayNeed($category_dm);
                } else {
                    $category_need = $this->calculateNeed($category_dm);
                }

                $this->total_necessary += $category_need;
            }
        }

        /**
         * calculates the monthly need of non-zero due day categories.
         *
         * @param Budget_DataModel_CategoryDM $category_dm
         * @return float
         */
        private function calculateNeed(Budget_DataModel_CategoryDM $category_dm) {
            $months_due = count($category_dm->getDueMonths());
            $multiplier = ($months_due < 12) ? $months_due : 12;

            return (($category_dm->getAmountNecessary() * $multiplier) / 12);
        }

        /**
         * calculates the monthly need of zero due day categories.
         *
         * @param Budget_DataModel_CategoryDM $category_dm
         * @return float
         */
        private function calcuateZeroDayNeed(Budget_DataModel_CategoryDM $category_dm) {
            $months_due = count($category_dm->getDueMonths());

            switch($this->account_dm->getPayscheduleCode()) {
                case 3:// 24 checks per year
                    $multiplier = ($months_due < 12) ? $months_due*2 : 24;
                    break;
                case 2:// 52 checks per year
                    $multiplier = 52;
                    if($months_due < 12) {
                        $multiplier = $this->calculateChecksPerMonthsDue($category_dm);
                    }
                    break;
                case 4:// 12 checks per year
                    $multiplier = ($months_due < 12) ? $months_due : 12;
                    break;
                case 1:// 26 checks per year
                default:
                    $multiplier = 26;
                    if($months_due < 12) {
                        $multiplier = $this->calculateChecksPerMonthsDue($category_dm);
                    }
                    break;
            }

            return (($category_dm->getAmountNecessary() * $multiplier) / 12);
        }

        /**
         * calculates the number of checks needed for the months a bill is due
         *
         * @param Budget_DataModel_CategoryDM $category_dm
         * @return int
         */
        private function calculateChecksPerMonthsDue(Budget_DataModel_CategoryDM $category_dm) {
            $num_checks = 0;
            foreach($category_dm->getDueMonths() as $due_month) {
                $due_month_obj = $this->getDueMonthObject($due_month);
                $days_out = $this->getDaysUntilDueMonth($due_month_obj);
                $pay_date = $this->getFirstPayDateOfMonth($days_out);
                $num_checks += $this->calculateChecksPerMonth($pay_date);
            }

            return $num_checks;
        }

        /**
         * calculates the number of checks in a given month based on the timestamp of the param
         *
         * @param DateTime $pay_date
         * @return int
         */
        private function calculateChecksPerMonth(DateTime $pay_date) {
            $num_checks = ($this->account_dm->getPayFrequency() == 14) ? 2 : 4;
            if($pay_date->format('d') < 4) {
                //31 day monts
                if(in_array($pay_date->format('m'), self::$thirty_one_day_months)) {
                    $num_checks++;
                } else if(in_array($pay_date->format('m'), self::$thirty_day_months)) {
                    if($pay_date->format('d') < 3) {
                        $num_checks++;
                    }
                } else {
                    //check for leap year if pay date is the 1st of the month
                    if($pay_date->format('d') == 1 && $this->isLeapYear($pay_date->format('Y'))) {
                        $num_checks++;
                    }
                }
            }

            return $num_checks;
        }

        /**
         * determines if the year passed in param is a leap year
         *
         * @param string $year
         * @return boolean
         */
        private function isLeapYear($year) {
            return ((($year % 4) == 0) && ((($year % 100) != 0) || (($year % 400) == 0)));
        }

        /**
         * calculates the first pay date of a month
         *
         * @param int $days_out
         * @return \DateTime
         */
        private function getFirstPayDateOfMonth($days_out) {
            $weeks_away = ceil($days_out / 7);

            if($this->account_dm->getPayFrequency() == 14 && $weeks_away % 2 != 0) {
                //add one week to our count for 26 check schedules
                $weeks_away++;
            }

            $pay_date = new DateTime($this->last_pay_date->getTimestamp());
            $pay_date->modify('+'.$weeks_away.' weeks');

            return $pay_date;
        }

        /**
         * determines how many days until the next due month arrives (not the next due date)
         *
         * @param DateTime $due_month_obj
         * @return type
         */
        private function getDaysUntilDueMonth(DateTime $due_month_obj) {
            $diff = $this->last_pay_date->diff($due_month_obj);
            return $diff->format('d');
        }

        /**
         * creates a DateTime object for the first day of a due month
         *
         * @param int $due_month
         * @return \DateTime
         */
        private function getDueMonthObject($due_month) {
            if(date('m') < $due_month) {
                $year = date('Y');
            } else {
                $year = date('Y', strtotime('+1 year'));
            }

            return new DateTime($year . '-' . $due_month . '-1');
        }

        public function getTotal() {
            return $this->total;
        }

        public function getTotalNecessary() {
            return $this->total_necessary;
        }

        public function getAccountId() {
            return $this->account_dm->getAccountId();
        }

        public function getAccountDM() {
            return $this->account_dm;
        }
    }
