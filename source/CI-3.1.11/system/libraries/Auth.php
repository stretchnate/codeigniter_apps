<?php

/**
 * I belive this class was left over from Integra
 */
// class CI_Auth
// {

//     public $ci;

//     public $config;

//     public $salt_length;

//     /**
//      * Constructor
//      *
//      * Load session library and configuration
//      */
//     public function __construct() {
//         $this->ci = get_instance();
//         $this->ci->load->library('session');

//         include(APPPATH . 'config/auth' . '.php');
//         if (isset($auth)) {
//             $this->config['auth'] = $auth;
//         }
//         $this->salt_length = $auth['salt_length'] ?? 5;
//     }

//     /**
//      * Authenticate a login (username, password) and store session data
//      * specified in the auth config
//      * @param string $username
//      * @param string $plain_password
//      * @param bool $is_preauthenticated
//      * @return boolean
//      */
//     public function login(string $username, string $plain_password, bool $is_preauthenticated = false) {
//         $auth_config = $this->config['auth'];

//         //retain any current auth session data
//         $auth_session_data = $this->ci->session->auth;

//         if (empty($auth_config)) {
//             return FALSE;
//         }

//         //first we get the stored password, which contains salt for our hash...
//         if ($auth_config['auth_type'] == 'db') { //database method
//             $this->ci->db->where($auth_config['db']['username_field'], $username);
//             $query = $this->ci->db->get($auth_config['db']['table']);
//             if ($query->num_rows() == 0) {
//                 return FALSE;
//             }
//             $stored_password = $query->row()->password;

//             //copy session data
//             foreach ($auth_config['session']['save'] as $field) {
//                 $auth_session_data[$field] = $query->row()->$field;
//             }
//         } else { //config method, find the index for this user to get the password
//             for ($i = 0; isset($auth_config['config']['users'][$i]); $i++) {
//                 if ($auth_config['config']['users'][$i]['username'] == $username) {
//                     $found_user_index = $i;
//                     break;
//                 }
//             }
//             if (!isset($found_user_index)) {
//                 return FALSE;
//             }

//             //copy session data
//             foreach ($auth_config['session']['save'] as $field) {
//                 $auth_session_data[$field] = $auth_config['config']['users'][$found_user_index][$field];
//             }

//             $stored_password = $auth_config['config']['users'][$found_user_index]['password'];
//         } //end else, config method

//         if (!$is_preauthenticated) {
//             if (isset($auth_config['reversible_login_hash'])) {
//                 $hashed_password = $this->generateReversibleHash($plain_password, $stored_password);
//             } else {
//                 $hashed_password = $this->generateHash($plain_password, $stored_password);
//             }
//             if ($hashed_password != $stored_password) {
//                 return FALSE;
//             } 
//         }

//         $auth_session_data['return_uri'] = $this->get('return_uri');
//         $this->ci->session->auth = $auth_session_data;

//         return true;
//     }

//     /**
//      * Destroy the session
//      */
//     public function logout() {
//         session_start();
//         session_destroy();
//     }

//     /**
//      * 
//      * See if the auth data is stored in the session.
//      * @param string $send_uri used when given and not logged in
//      * @param boolean $handle_ajax detect XMLHttpRequest and accept mode, exit 
//      * with either a redirect script in the response (JSON mode) or a plain login 
//      * link. 
//      * @return boolean
//      */
//     public function isLoggedIn($send_uri = NULL, $handle_ajax = TRUE) {
//         if ($this->get('user_id')) {
//             return TRUE;
//         }
//         //return true if they are on the login recovery pages...
//         elseif (preg_match('/\/loginrecovery\/resetpassword/i', ($_SERVER['HTTP_REFERER'] ?? '')) || preg_match('/\/loginrecovery\/requestreset/i', ($_SERVER['HTTP_REFERER'] ?? '')) || preg_match('/\/loginrecovery\/requestusername/i', ($_SERVER['HTTP_REFERER'] ?? ''))) {
//             return TRUE;
//         }
        
//         if ($handle_ajax && $this->ci->input->server('HTTP_X_REQUESTED_WITH')) {
//             //detected AJAX request
//             if (stristr((string) $this->ci->input->server('HTTP_ACCEPT'), 'json')) {
//                 //JSON response request; hopefully redirect the receiving page via our response data
//                 $script = '<script type="application/javascript">$(document).ready(function(){ window.location="/login"; });</script>';
//                 $response = Integra_Ajax::json(TRUE, $script, $script);
//             } else {
//                 //plain AJAX response; provide link
//                 $response = "<a href=\"/login\">Login</a>";
//             }
//             exit($response);
//         }

//         if ($send_uri) {
//             $return_uri = implode('/', $this->ci->uri->rsegments);
//             $this->redirectSend($send_uri, $return_uri);
//         } else {
//             return FALSE;
//         }
//     }

//     /**
//      * Get the value of an auth variable
//      * @param string $session_var
//      * @return mixed
//      */
//     public function get($auth_var) {
//         $auth = $this->ci->session->auth;
//         return $auth[$auth_var] ?? NULL;
//     }

//     /**
//      * Set the value of an auth variable
//      * @param string $auth_var
//      * @param mixed $value
//      */
//     public function set($auth_var, $value) {
//         $auth = $this->ci->session->auth;
//         $auth[$auth_var] = $value;
//         $this->ci->session->auth = $auth;
//     }

//     /**
//      * Redirect, setting a return uri in the session for redirect return
//      * @param string $send_uri
//      * @param string $return_uri
//      */
//     public function redirectSend($send_uri, $return_uri) {
//         if (strcasecmp(explode('/', $return_uri)[0], $this->ci->router->default_controller)) {
//             $this->set('return_uri', $return_uri);
//         }
//         redirect($send_uri);
//     }

//     /**
//      * Redirect to the return uri in the session and return true, or return
//      * false if there was no return uri in the session
//      * @return bool
//      */
//     public function redirectReturn() {
//         $return_uri = $this->get('return_uri');
//         if (!empty($return_uri)) {
//             redirect($return_uri);
//             return TRUE;
//         }
//         return FALSE;
//     }

//     /**
//      * Hash a string prepending a random salt hash if salt is not given or
//      * hash a string using a known salt if it is given (fixed salt length
//      * of 5)
//      * This method adapted from James McGlinn, PHP Security Consortium
//      * @param string $plain_text
//      * @param string $salt
//      * @return string
//      */
//     public function generateHash($plain_text, $salt = NULL) {
//         $salt_length = $this->salt_length;

//         if ($salt === NULL) {
//             $salt = substr(md5(uniqid(random_int(0, mt_getrandmax()), TRUE)), 0, $salt_length);
//         } else {
//             $salt = substr($salt, 0, $salt_length);
//         }

//         return $salt . sha1($salt . $plain_text);
//     }

//     /**
//      * Hash a string prepending a radom salt hash.
//      * Use Base_64 so that the Hash can be reversed.
//      *
//      * @param string $plain_text
//      * @param string $salt
//      * @return string
//      */
//     public function generateReversibleHash($plain_text, $salt = NULL) {
//         $salt_length = $this->salt_length;

//         if ($salt === NULL) {
//             $salt = substr(md5(uniqid(random_int(0, mt_getrandmax()), TRUE)), 0, $salt_length);
//         } else {
//             $salt = substr($salt, 0, $salt_length);
//         }

//         return $salt . base64_encode($salt . $plain_text);
//     }

//     public function reverseHash($hash) {
//         $sl = $this->salt_length;
//         return substr(base64_decode(substr((string) $hash, $sl)), $sl);
//     }

// }
