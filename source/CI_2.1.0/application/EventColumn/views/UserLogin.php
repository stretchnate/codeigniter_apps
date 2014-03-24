<?php
	require_once('topViews/searchHeaderVW.php');
	/**
	 * Description of UserLogin
	 *
	 * @author stretch
	 */
	class UserLoginVW extends searchHeaderVW {

		protected $login_form;
		protected $register_form;

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
			<div id="login_register_container">
				<div id="login_content" class="login_register">
				<?php
					$this->login_form->renderForm();
				?>
					<div>
						<a href="login/forgotPassword">Forgot Password</a>
						&nbsp;|&nbsp;
						<a href="/register">Create an Account</a>
					</div>
				</div>
				<div id="register_content" class="login_register">
				<?php
					$this->register_form->renderForm();
				?>
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

		public function setRegisterForm(Form $register_form) {
			$this->register_form = $register_form;
			return $this;
		}
	}

?>
