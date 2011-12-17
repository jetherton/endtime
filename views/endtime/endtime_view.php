<?php if ($applicable == 1) 
{
?>

					<br/>
					<span class="r_date">
          <?php 
            echo Kohana::lang('endtime.endtime') .": ".
              date('H:i M j Y', strtotime($end_date)) .". ".
              ($remain_on_map ? Kohana::lang('endtime.will_remove_from_map') : Kohana::lang('endtime.will_remain_on_map')); 

          ?>
					</li>
					
<?php } ?>
