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

		/**
		 * generates the view
		 *
		 * @return void
		 * @since 1.0
		 */
		public function generateView() {
			?>
			<div id="login-content">
			<?php
				$this->login_form->renderForm();
			?>
				<div>
					<a href="login/forgotPassword">Forgot Password</a>
					&nbsp;|&nbsp;
					<a href="/register">Create an Account</a>
				</div>
			</div>
			<?php
		}

		/**
		 * sets the login form
		 *
		 * @param Form $login_form
		 * @return \UserLoginVW
		 */
		public function setLoginForm(Form $login_form) {
			$this->login_form = $login_form;
			return $this;
		}
	}

?>
