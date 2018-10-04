<?php
/*
Template Name: budgetsso
*/
//	wp_enqueue_script('jquery');
	//client sends user data to http://<budgeturl>/sso/User/userLogin to create user and retrieve token
	//upon receiving token browser sends post to http://<budgeturl>/sso/User/getCookie/<access_token>
	//after cookie is set, client redirects user to http://<budgeturl>/sso/User/ssoLogin

	//if user is not logged in send them to login page.
//	if(function_exists('is_user_logged_in')) {
//		if(!is_user_logged_in()) {
//			header('Location: http://whyibudget.com/rm_login');
//		}
//	}
//require_once('../../../plugins/memberium2/interface.php');

get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
//if(function_exists('do_shortcode')) {
//	echo "do_shortcode exists\n";
//}
//echo print_r(do_shortcode( '[memb_contact fields=Email]'), true);
//die;
				$email = do_shortcode('[memb_contact fields=Email]');
//echo $email."\n";
				if(empty($email)) {
					header('Location: http://whyibudget.com/');
				}
			//	global $current_user;
			//	get_currentuserinfo();

				$qc = new QuantumConnect();

			//	if(!$current_user->user_email) {
			//		die("There was a problem authenticating you to the budget, please ensure you are logged in, with a valid email, to whyibudget.com");
			//	}

				$qc->userLogin($email);
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer();

	class QuantumConnect {
		const BUDGET_URL = 'https://whyibudget.quantumfunds.net/sso/user/';
//		const BUDGET_URL = 'http://money.stretchnate.com/sso/user/';

		/**
		 * attempt to log the user in to the budget application
		 *
		 * @param string $user_email
		 */
		public function userLogin($user_email) {
			$post_fields = [
				'token' => base64_encode($this->fetchAuthToken()),
				'email' => $user_email,
				'username' => $user_email,
				'agree_to_terms' => true];
			$result = json_decode($this->send(self::BUDGET_URL.'userLogin', $post_fields));
			if($result->status == 200) {
				$this->getCookie($result->url, $result->access_token, $user_email);
			} else {
				$this->showError();
			}
		}

		/**
		 * display an error page
		 */
		private function showError() {
			?>
<!--			<html>
				<head>
					<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>-->
					<script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('body').append('<div>There was a problem fulfilling your request. You will be redirected back to the home page in a moment.</div>');
							window.setTimeout(function() {
								window.location.replace('/');
							}, 8000);
						});
					</script>
<!--				</head>
				<body></body>
			</html>-->
			<?php
		}

		/**
		 * load the iframe so the cookies can be set
		 *
		 * @param string $url
		 */
		private function getCookie($url, $access_token, $email) {
			$redirect = self::BUDGET_URL.'ssoLogin';
			?>
<!--			<html>
				<head>
					<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>-->
					<script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery.ajax({
								url:'<?=$url;?>',
								type: "POST",
								data: {access_token:'<?=$access_token;?>', email:'<?=$email;?>'},
								dataType: 'json',
								xhrFields: {
									withCredentials: true
								},
								crossDomain: true,
								success: function(response) {
									if(response.status === 200) {
										window.location.replace("<?=$redirect;?>");
									} else {
										var message = 'There was a problem fulfilling your request.';
										if(response.message) {
											message = response.message;
										}
										message += ' You will be redirected back to the home page in a moment.';
										jQuery('body').append('<div>'+message+'</div>');
										window.setTimeout(function() {
											window.location.replace('/');
										}, 8000);
									}
								}
							});
						});
					</script>
<!--				</head>
				<body></body>
			</html>-->
			<?php
		}

		/**
		 * fetch the client auth token
		 * @return string
		 */
		private function fetchAuthToken() {
			$auth_token = 'Success20171209!';
			$plaintext = "whyibudget.".$auth_token;//domain . auth_token

			$key = file_get_contents('/home2/whyibudg/public_html/wp-content/themes/oceanwp/templates/wib_public.pem');
			$pubkey = openssl_get_publickey($key);

			openssl_public_encrypt(base64_encode($plaintext), $crypted, $pubkey);

			return $crypted;
		}

		/**
		 * send a cURL request to the budget application
		 *
		 * @param type $url
		 * @param type $data
		 */
		private function send($url, $data) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_COOKIESESSION, true);
			curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//need to set to true once I figure out the ssl issue
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//need to set to true once I figure out the ssl issue
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);

			return curl_exec($ch);
		}
	}

