<?php
/**
 * Performs install/uninstall methods for the actionable plugin
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module	   Actionable Installer
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class Endtime_Install {

	/**
	 * Constructor to load the shared database library
	 */
	public function __construct()
	{
		$this->db = Database::instance();
	}

	/**
	 * Creates the required database tables for the actionable plugin
	 */
	public function run_install()
	{
		// Create the database tables.
		// Also include table_prefix in name
		$this->db->query("CREATE TABLE IF NOT EXISTS `".Kohana::config('database.default.table_prefix')."endtime` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `incident_id` int(11) NOT NULL,
				  `endtime_date` datetime DEFAULT NULL,
          `applicable` tinyint(4) DEFAULT '1',
          `remain_on_map` tinyint(4) DEFAULT '0',
				  PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");


    /**
     * Updates made to the endtime schema from March-Hare Communications 
     * Collective on 2011-12-16:
     * ALTER TABLE `endtime` ADD `remain_on_map` TINYINT( 4 ) NOT NULL 
     */
    $has_remain_on_map = false;
    foreach($this->db->field_data(Kohana::config('database.default.table_prefix')."endtime") as $field) {
      if ($field->Field == 'remain_on_map') {
        $has_remain_on_map = true;
        break;
      }
    }
    if (!$has_remain_on_map) {
      $this->db->query('ALTER TABLE '. Kohana::config('database.default.table_prefix') .'`endtime` ADD `remain_on_map` TINYINT( 4 ) NOT NULL');
    }

    $s_endtime = ORM::factory('scheduler')
      ->where(array('scheduler_name' => 'Endtime'))
      ->find();

    if (!$s_endtime->count_last_query()) {
      $this->db->query("INSERT INTO `".Kohana::config('database.default.table_prefix')."scheduler` (
        `id`, 
        `scheduler_name`, 
        `scheduler_last`, 
        `scheduler_weekday`, 
        `scheduler_day`, 
        `scheduler_hour`, 
        `scheduler_minute`, 
        `scheduler_controller`, 
        `scheduler_active`) 
        VALUES (NULL, 'Endtime', '0', '-1', '-1', '-1', '-1', 's_endtime', '1')");
    }
	}

	/**
	 * Deletes the database tables for the actionable module
	 */
	public function uninstall()
  {

		$this->db->query('DROP TABLE `'.Kohana::config('database.default.table_prefix').'endtime`');
		$this->db->query('DELETE FROM `'.Kohana::config('database.default.table_prefix').'scheduler` WHERE `scheduler_name`="Endtime"');
  }

}
