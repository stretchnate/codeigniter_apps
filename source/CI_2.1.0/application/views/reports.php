<div>
<h2><?php echo $title; ?></h2>
<form action="<?php echo $form_data['action']; ?>" method="post">
	<table>
		<tr>
			<th>Start Date</th>
			<th>End Date</th>
			<?php
			if( is_array($accounts) ) { ?>
			<th>Account</th>
			<?php
			} ?>
		</tr>
		<tr>
			<td>
				<input name="start_date" type="text" value="" />
			</td>
			<td>
				<input name="end_date" type="text" value="" />
			</td>
			<?php
			if( is_array($accounts) ) { ?>
			<td>
				<select name="account">
				<?php
				foreach($accounts as $account) {
					if( is_array($account->categories) ) {
				?>
						<optgroup label="<?php echo $account->account_name;?>">
				<?php
					} else { ?>
						<option value="<?php echo $account->account_id;?>"><?php echo $account->account_name;?></option>
				<?php
					}
					foreach($account->categories as $category) { ?>
					<option value="<?php echo $category->bookId; ?>"><?php echo $category->bookName; ?></option>
					<?php
					}?>
				<?php
				}
				?>
				</select>
			</td>
			<?php
			} ?>
		</tr>
		<tr>
			<td colspan="3" align="right">
				<input name="run_report" type="submit" value="Run Report" />
			</td>
		</tr>
	</table>
</form>

</div>
