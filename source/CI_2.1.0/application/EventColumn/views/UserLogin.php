<?php
	require_once('baseVW.php');
	/**
	 * Description of UserLogin
	 *
	 * @author stretch
	 */
	class UserLoginVW extends BaseVW {

		protected $login_form;

		public function __construct() {
			parent::__construct();
		}

		public function generateView() {
			?>
			<div id="login-content">
			<?php
				$this->login_form->renderForm();
			?>
				<div>
					<a href="javascript:void(null);">Forgot Password</a>
					&nbsp;|&nbsp;
					<a href="/register">Create an Account</a>
				</div>
			</div>
			<?php
		}

		public function setLoginForm($login_form) {
			$this->login_form = $login_form;
		}
	}

?>
