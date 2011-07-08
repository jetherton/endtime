<?php if ($applicable == 1) 
{
?>

					<br/>
					<span class="r_date">
						<?php echo Kohana::lang('endtime.endtime');?>:
						<?php echo date('H:i M j Y', strtotime($end_date)); ?>
					</li>
					
<?php } ?>