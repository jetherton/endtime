<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Actionable Hook - Load All Events
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package	   Ushahidi - http://source.ushahididev.com
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class endtime {
	
	/**
	 * Registers the main event add method
	 */
	public function __construct()
	{
		$this->post_data = null; //initialize this for later use	
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
	}
	
	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
	{
		// Hook into the form itself
		Event::add('ushahidi_action.report_form_admin_after_time', array($this, '_report_form'));
		// Hook into the report_submit_admin (post_POST) event right before saving
		Event::add('ushahidi_action.report_submit_admin', array($this, '_report_validate'));
		// Hook into the report_edit (post_SAVE) event
		Event::add('ushahidi_action.report_edit', array($this, '_report_form_submit'));

	
		// Hook into the Report view (front end)
		Event::add('ushahidi_action.report_meta_after_time', array($this, '_report_view'));
		
	}
	
	/**
	 * Add Actionable Form input to the Report Submit Form
	 */
	public function _report_form()
	{
		// Load the View
		$view = View::factory('endtime/endtime_form');
		// Get the ID of the Incident (Report)
		$id = Event::$data;
		
		//initialize the array
		$form = array
			(
			    'end_incident_date'  => '',
			    'end_incident_hour'      => '',
			    'end_incident_minute'      => '',
			    'end_incident_ampm' => ''
			);
		
		
		if ($id)
		{
			// Do We have an Existing Actionable Item for this Report?
			$endtime_item = ORM::factory('endtime')
				->where('incident_id', $id)
				->find();

			$view->applicable = $endtime_item->applicable;
			$endtime_date = $endtime_item->endtime_date;
			
			if($endtime_date == "")
			{
				$incident = ORM::factory('incident')->where('id', $id)->find();
				$i_date_time = $incident->incident_date;
				$form['end_incident_date'] = date('m/d/Y', strtotime($i_date_time));
				$form['end_incident_hour'] = date('h', strtotime($i_date_time));
				$form['end_incident_minute'] = date('i', strtotime($i_date_time));
				$form['end_incident_ampm'] = date('a', strtotime($i_date_time));
			}
			else
			{
				$form['end_incident_date'] = date('m/d/Y', strtotime($endtime_date));
				$form['end_incident_hour'] = date('h', strtotime($endtime_date));
				$form['end_incident_minute'] = date('i', strtotime($endtime_date));
				$form['end_incident_ampm'] = date('a', strtotime($endtime_date));
			}
		}		
		else //initialize to now
		{
			$view->applicable = 0;
			$form['end_incident_date'] = date("m/d/Y",time());
			$form['end_incident_hour'] = date('h', time());
			$form['end_incident_minute'] = date('i', time());
			$form['end_incident_ampm'] = date('a', time());
		}
		
		// Time formatting
		$view->minute_array = $this->_minute_array();
		$view->hour_array = $this->_hour_array();
		$view->ampm_array = $this->_ampm_array();
		$view->date_picker_js = $this->_date_picker_js();

		$view->form = $form;
		$view->render(TRUE);
	}
	
	/**
	 * Validate Form Submission
	 */
	public function _report_validate()
	{
		$this->post_data = Event::$data;
		$this->post_data->add_rules('end_incident_date','date_mmddyyyy');
	}
	
	/**
	 * Handle Form Submission and Save Data
	 */
	public function _report_form_submit()
	{
		$post = $this->post_data;
		$incident = Event::$data;
		$id = $incident->id;
		
		
		if ($post)
		{
			$endtime = ORM::factory('endtime')
				->where('incident_id', $id)
				->find();
			$endtime->incident_id = $incident->id;
			$endtime->applicable = isset($post['endtime_applicable']) ?  "1" : "0";
			//create the date
			$incident_date=explode("/",$post->end_incident_date);
			$incident_date=$incident_date[2]."-".$incident_date[0]."-".$incident_date[1];
			$incident_time = $post->end_incident_hour . ":".$post->end_incident_minute.":00 " . $post->end_incident_ampm;
			
			$endtime->endtime_date = date( "Y-m-d H:i:s", strtotime($incident_date . " " . $incident_time) );
			$endtime->save();
			
		}
	}
	
	/**
	 * Render the Action Taken Information to the Report
	 * on the front end
	 */
	public function _report_view()
	{
		$incident_id = Event::$data;
		$endtime = ORM::factory('endtime')
			->where('incident_id', $incident_id)
			->find();
		
		$view = View::factory('endtime/endtime_view');
		$view->end_date = $endtime->endtime_date;
		$view->applicable = $endtime->applicable;
		$view->render(TRUE);
	}
	
	
	
	// Time functions
    private function _hour_array()
    {
        for ($i=1; $i <= 12 ; $i++)
        {
            $hour_array[sprintf("%02d", $i)] = sprintf("%02d", $i);     // Add Leading Zero
        }
        return $hour_array;
    }
    
    	// Time functions
    private function _minute_array()
    {
        for ($i=0; $i <= 59 ; $i++)
        {
            $minute_array[sprintf("%02d", $i)] = sprintf("%02d", $i);     // Add Leading Zero
        }
        return $minute_array;
    }


    private function _ampm_array()
    {
        return $ampm_array = array('pm'=>Kohana::lang('ui_admin.pm'),'am'=>Kohana::lang('ui_admin.am'));
    }

    private function _date_picker_js()
    {
        return "<script type=\"text/javascript\">
                $(document).ready(function() {
                $(\"#end_incident_date\").datepicker({
                showOn: \"both\",
                buttonImage: \"" . url::base() . "media/img/icon-calendar.gif\",
                buttonImageOnly: true
                });
                });
		
		
			$(\"#endtime_applicable\").click(function()
			{
				var state = $(this).val();
				if(state == \"1\")
				{
					$(\"#endtime_form\").hide();
					$(this).val(\"0\");
				}
				else
				{
					$(\"#endtime_form\").show();
					$(this).val(\"1\");
				}
			});
		
		
		
            </script>";
    }

}//end method

new endtime;