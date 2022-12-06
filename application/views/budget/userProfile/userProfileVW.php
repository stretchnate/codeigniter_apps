<?php
	require_once(APPPATH.'/views/budget/baseVW.php');

	class Budget_UserProfile_UserProfileVW extends Budget_BaseVW {

		/**
		 * @var \Budget_DataModel_UserDM
		 */
		private $user_dm;

		public function __construct(&$CI) {
			parent::__construct($CI);
		}

		/**
		 * generates the body of the view
		 *
		 * @access  protected
		 * @since   07.01.2013
		 */
		public function generateView() {
			//for some reason because this is in a class rather than just a file it is screwing up the html.
			//need to look into a template type structure rather than loading a bunch of files for header and footer.
			?>
			<form name="user_profile_form" action="/userCTL/update/" method="post">
				<div class="form-group">
					<label for="username"><?=$this->user_dm->getUsername();?></label>
				</div>
				<div class="form-group">
					<input type="text" value="<?=$this->user_dm->getEmail();?>" id="email" name="email" class="required email left form-control with-info" placeholder="Email" />
                    <a href="javascript:void(0)" class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="popover" data-placement="left" data-trigger="focus" data-content="Email"></a>
					<div class="error"> <?=form_error('email');?></div>
				</div>
				<div class="form-group clear-both">
					<input type="password"  value="" placeholder="New Password" class="form-control with-info" id="new_password" name="new_password" />
                    <a href="javascript:void(0)" class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="popover" data-placement="left" data-trigger="focus" data-content="New Password"></a>
				</div>
				<div class="form-group">
					<input type="password" value="" placeholder="Confirm New Password" id="confirm_new_password" name="confirm_new_password" class="form-control with-info" />
                    <a href="javascript:void(0)" class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="popover" data-placement="left" data-trigger="focus" data-content="Confirm New Password"></a>
					<div class="error"> <?=form_error('confirm_new_password');?></div>
				</div>
				<div class="form-group">
					<input type="password" value="" placeholder="Current Password" id="password" name="password" class="required form-control with-info" />
                    <a href="javascript:void(0)" class="glyphicon glyphicon-info-sign" aria-hidden="true"data-toggle="popover" data-placement="left" data-trigger="focus" data-content="Current Password"></a>
					<div class="error"> <?=form_error('password');?></div>
				</div>
				<input type="button" value="Submit Changes" name="user_form_submit" />
			</form>
			<?php
		}

		public function setUserDM(Budget_DataModel_UserDM $user_dm) {
			$this->user_dm = $user_dm;
		}
	}
?>