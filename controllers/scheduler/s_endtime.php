<?php defined('SYSPATH') or die('No direct script access.');
/**
 * EMAIL Scheduler Controller (IMAP/POP3)
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Mr Evoltech <evoltech@march-hare.org> 
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
*/

class S_Endtime_Controller extends Controller {
	
	public function __construct()
	{
		parent::__construct();
	}	
	
	public function index() 
  {
    // remain_on_map is not intended to be a boolean value, but instead allow a number of 
    // different values that will map to different actions to be taken when the incident
    // reaches its endtime. Ie. change marker icon, send notification
    $endtimes = ORM::factory('endtime')
      ->with('incident')
      // Does the use of php date cause an issue with application timezone vs system timezone?
      ->where(array(
        'incident_active' => '1', 
        'endtime_date <' => date("Y-m-d H:i:s"),
        'remain_on_map' => '1'))
      ->find_all();

    Kohana::log('debug', 'S_Endtime_Controller::index: '. print_r($endtimes, 1));

    foreach($endtimes as $endtime){
      Kohana::log('info', 'S_Endtime_Controller::index Setting '. $endtime->incident->incident_title .'('. $endtime->incident_id .') to inactive');
      $incident = ORM::factory('incident')
        ->where('id', $endtime->incident->id)
        ->find();
      $incident->incident_active = 0;
      $incident->save();
    }
  }

}
