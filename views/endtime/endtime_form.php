
<div class="row">
	<h4>
		End Time:
		<span>Time that the event stopped.</span>
		<br/>
		Applicable? <?php print form::checkbox('endtime_applicable', $applicable, $applicable); ?> 
		<span>Is an end time applicable to this report?</span>
	</h4>
	<div id="endtime_form" <?php echo (($applicable == "1") ? "" : "style=\"display:none;\""); ?> >
		<?php print form::input('end_incident_date', $form['end_incident_date'], ' class="text"'); ?>								
		<?php print $date_picker_js; ?>				    
		<br/>
		<div class="time">

			<?php
			print '<span class="sel-holder">' .
			form::dropdown('end_incident_hour', $hour_array,
			$form['end_incident_hour']) . '</span>';
			
			print '<span class="dots">:</span>';
		
			print '<span class="sel-holder" style="padding:1px 3px;">00</span>';
			
			print '<span class="dots">:</span>';
			
			print '<span class="sel-holder">' .
			form::dropdown('end_incident_ampm', $ampm_array,
			$form['end_incident_ampm']) . '</span>';

			?>
		</div>
	</div>
</div>

