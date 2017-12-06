<?php
//https://stackoverflow.com/questions/10916284/how-to-encrypt-decrypt-data-in-php


//http://php.net/manual/en/function.openssl-encrypt.php
//encrypt (client end)

//need to change this to use openssl_public_encrypt
//client sends user data to http://<budgeturl>/sso/User/userLogin to create user and retrieve token
//upon receiving token client opens iframe to http://<budgeturl>/sso/User/getCookie/<access_token>
//if needed - check for cookie set by posting [access_token => <access_token>] to http://<budgeturl>/sso/User/checkCookie until a 200 status_code is received
//after cookie is set, client redirects user to http://<budgeturl>/sso/User/ssoLogin

	$qc = new QuantumConnect();

	$qc->userLogin();

	class QuantumConnect {
		const BUDGET_URL = 'http://money.stretchnate.com/sso/user/';

		/**
		 * attempt to log the user in to the budget application
		 *
		 * @param string $user_email
		 */
		public function userLogin($user_email = 'test1@test.net') {
			$post_fields = [
				'token' => base64_encode($this->fetchAuthToken()),
				'email' => $user_email,
				'username' => $user_email,
				'agree_to_terms' => true];

			$result = json_decode($this->send(self::BUDGET_URL.'userLogin', $post_fields));

			if($result->status == 200) {
				$this->showIFrame($result->iframe_url);
			} else {
				$this->showError();
			}
		}

		/**
		 * display an error page
		 */
		private function showError() {
			?>
			<html>
				<head>
					<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
					<script type="text/javascript">
						$(document).ready(function() {
							window.setTimeout(function() {
								window.location.replace('/');
							}, 8000);
						});
					</script>
				</head>
				<body>
					<div>There was a problem fulfilling your request, you will be redirected back home shortly.</div>
				</body>
			</html>
			<?php
		}

		/**
		 * load the iframe so the cookies can be set
		 *
		 * @param string $url
		 */
		private function showIFrame($url) {
			$redirect = self::BUDGET_URL.'ssoLogin';
			$access_token = array_pop(explode("/", $url));
			?>
			<html>
				<head>
					<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
					<script type="text/javascript">
						$(document).ready(function() {
							$.post('<?=$url;?>', {access_token:'<?=$access_token;?>'}, function(response) {
								if(response.status === 200) {
									window.location.replace("<?=$redirect;?>");
								}
							}, 'json');
//							if($("#quantum-iframe").attr("src")) {
//								window.setTimeout(function() {
//									window.location.replace("<?=$redirect;?>");
//								}, 5000);
//							}
						});
					</script>
				</head>
				<body>
					<!--<iframe src="<?= $url; ?>" id="quantum-iframe"></iframe>-->
				</body>
			</html>
			<?php
		}

		/**
		 * fetch the client auth token
		 * @return string
		 */
		private function fetchAuthToken() {
			$auth_token = 'Success20171209!';
			$plaintext = "whyibudget.".$auth_token;//domain . auth_token

			$key = file_get_contents('/var/private/public.pem');
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
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);

			return curl_exec($ch);
		}
	}
