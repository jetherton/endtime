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
    $endtimes = ORM::factory('endtime')
      ->with('incident')
      ->where(array('incident_active' => '1', 'endtime_date <' => 'NOW()'))
      ->find_all();

    //Kohana::log('info', 'S_Endtime_Controller::index: '. print_r($incidents, 3));

    foreach($endtimes as $endtime){
      Kohana::log('info', 'S_Endtime_Controller::index: '. print_r($endtime->incident->incident_title, 1));
      $incident = ORM::factory('incident')
        ->where('id', $endtime->incident->id)
        ->find();
      $incident->incident_active = 0;
      $incident->save();
    }
  }

}
