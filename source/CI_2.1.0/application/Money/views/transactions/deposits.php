<div id="transactions">
<h2>Recent Deposits</h2>
	<?php
	if( isset($records) && is_array($records) && count($records) > 0) {
	?>
	<table>
		<tr>
			<th>Account</th>
			<th>Deposit ID</th>
			<th>Date</th>
			<th>Source</th>
			<th>Gross</th>
			<th>Net</th>
		</tr>
		<?php
		$i = 0;
		foreach($records as $record) {
			if( $i % 2 == 0 ) {
				$bg = "zebra";
			} else {
				$bg = "";
			}
			$i++;
		?>
		<tr>
			<td class="<?php echo $bg;?>"><?php echo $record->account_name;?></td>
			<td class="<?php echo $bg; ?>"><?php echo $record->id; ?></td>
			<td class="<?php echo $bg; ?>"><?php echo date("m/d/Y h:i:s a", strtotime($record->date)); ?></td>
			<td class="<?php echo $bg; ?>"><?php echo $record->source; ?></td>
			<td class="<?php echo $bg; ?>">$<?php echo number_format($record->gross,2); ?></td>
			<td class="<?php echo $bg; ?>">$<?php echo number_format($record->net,2); ?></td>
		</tr>
		<?php
		} ?>
	</table>
	<?php
	}
	?>
</div>
<div class="clear">&nbsp;</div>	
