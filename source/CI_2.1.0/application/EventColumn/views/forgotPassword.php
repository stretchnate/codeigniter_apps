<?php
	require_once('baseVW.php');
	/**
	 * Description of forgotPassword
	 *
	 * @author stretch
	 */
	class forgotPasswordVW extends baseVW {

		protected $forgot_password_form;

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
				<h2>Forgot Password</h2>
				<div>
					Please provide your email so we can send you a temporary password.
				</div>
				<?=$this->forgot_password_form->renderForm();?>
			</div>
			<?
		}

		/**
		 * sets the forgot_password_form
		 *
		 * @param Form $forgot_password_form
		 * @return \forgotPasswordVW
		 * @since 1.0
		 */
		public function setForgotPasswordForm( Form $forgot_password_form) {
			$this->forgot_password_form = $forgot_password_form;
			return $this;
		}
	}

?>
