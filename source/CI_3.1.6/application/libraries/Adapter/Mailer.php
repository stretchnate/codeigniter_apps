<?php
/**
 * Created by PhpStorm.
 * User: Stretch
 * Date: 5/1/2018
 * Time: 6:39 PM
 */

namespace Adapter;

/**
 * Class Mailer
 *
 * @package Adapter
 */
class Mailer {

    /**
     * @var \CI_Controller
     */
    private $CI;

    /**
     * Mailer constructor.
     */
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('email');

        $this->clear();
    }

    /**
     * @param $to
     * @return $this
     */
    public function to($to) {
        $this->CI->email->to($to);

        return $this;
    }

    /**
     * @param $from
     * @return $this
     */
    public function from($from) {
        $this->CI->email->from($from);

        return $this;
    }

    /**
     * @param $cc
     * @return $this
     */
    public function cc($cc) {
        $this->CI->email->cc($cc);

        return $this;
    }

    /**
     * @param $bcc
     * @return $this
     */
    public function bcc($bcc) {
        $this->CI->email->bcc($bcc);

        return $this;
    }

    /**
     * @param $subject
     * @return $this
     */
    public function subject($subject) {
        $this->CI->email->subject($subject);

        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    public function message($message) {
        $this->CI->email->message($message);

        return $this;
    }

    /**
     * send the email
     */
    public function send() {
        $this->CI->email->send();
    }

    /**
     * @return $this
     */
    public function clear() {
        $this->CI->email->clear();

        return $this;
    }
}