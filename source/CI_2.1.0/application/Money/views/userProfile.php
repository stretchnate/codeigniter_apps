<?php
if($profile->edit === TRUE) {
?>
	<form name="profile-form" action="" method="post">
	Username: <?php echo form_input("username", $profile->Username); ?><br />
	Email: <?php echo form_input("email", $profile->Email); ?><br />
	Pay Schedule: <?php echo form_input("payshcedule", $profile->payschedule_translation); ?><br />
	<?php echo "save button"; ?>
	</form>
<?php
} else {
?>
	Username: <?php echo $profile->Username; ?><br />
	Email: <?php echo $profile->Email; ?><br />
	Payschedule: <?php echo $profile->payschedule_translation; ?><br />
	<a href="/user/edit/">Edit this info</a>
<?php
}
?>