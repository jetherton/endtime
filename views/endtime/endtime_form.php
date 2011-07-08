
<div class="row">
	<h4>
		<?php echo Kohana::lang('endtime.endtime');?>
		
		<span><?php echo Kohana::lang('endtime.endtime_description');?></span>
		
		<div style="margin-left: 20px;"><?php echo Kohana::lang('endtime.applicable');?> <?php print form::checkbox('endtime_applicable', $applicable, $applicable); ?> 
		<span><?php echo Kohana::lang('endtime.applicable_description');?></span></div>
	</h4>
	<!--<div style = "border: 1px solid black; margin-left:10px;">-->
		<div id="endtime_form" style="margin-left:30px; <?php echo (($applicable == "1") ? "" : "display:none;"); ?>" >
			<?php print form::input('end_incident_date', $form['end_incident_date'], ' class="text"'); ?>								
			<?php print $date_picker_js; ?>				    
			<br/>
			<br/>
			<div class="time">

				<?php
				print '<span class="sel-holder">' .
				form::dropdown('end_incident_hour', $hour_array,
				$form['end_incident_hour']) . '</span>';
				
				print '<span class="dots">:</span>';
			
				print '<span class="sel-holder">' .
				form::dropdown('end_incident_minute', $minute_array,
				$form['end_incident_minute']) . '</span>';
				
				print '<span class="dots">:</span>';
				
				
				print '<span class="sel-holder">' .
				form::dropdown('end_incident_ampm', $ampm_array,
				$form['end_incident_ampm']) . '</span>';

				?>
			</div>
	</div>
</div>

