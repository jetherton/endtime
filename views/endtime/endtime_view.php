<?php if ($applicable == 1) 
{
?>

					<li>
						<small>End Time</small>
						<?php echo date('M j Y, H:00', strtotime($end_date)); ?>
					</li>
					
<?php } ?>