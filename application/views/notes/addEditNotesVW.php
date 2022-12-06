<span class="error">
	<?
	if( isset($result->message) ) {
		echo $result->message;
	}
	?>
</span>
<form id="add_notes" method="post" action="/notes/addEditNote/<?php echo  (isset($note)) ? $note->note_id : ""; ?>">
	<label for="note_priority">Display Position:</label>
	<input id="note_priority" name="note_priority" type="text" size="3" maxlength="3" value="<?php echo  (isset($note)) ? $note->note_priority : ""; ?>" />
	<br />
	<br />
	<label for="account_id">Account</label>
	<select name="account_id" id="account_id">
		<option value="<?php echo  $this->session->userdata('user_id'); ?>">Home Page Only</option>
		<option value="0">All Pages</option>
	<?
		if( is_array($accounts) ) {
			foreach($accounts as $account) {
				echo "<optgroup label='{$account->account_name}' />";
				foreach($account->categories as $category) {
					$selected = "";
					if($category->bookId == $selected_category) {
						$selected = " selected='selected'";
					}
	?>
					<option value="<?php echo $category->bookId; ?>"<?php echo $selected; ?>><?php echo $category->bookName; ?></option>
	<?
				}
			}
		}
	?>
	</select>
	<br />
	<br />
	<label for="note_text">Note Text:</label>
	<br />
	<textarea id="note_text" name="note_text" rows="15" cols="40"><?php echo  (isset($note)) ? $note->note_text : ""; ?></textarea>
	<br />
	<input type="button" value="Update" onclick="javascript:document.forms[0].submit()" />
</form>
