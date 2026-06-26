<?php
namespace VirtueMart;
/**
 * This class is derived from the JFactory class of the Joomla! Content Management System
 *
 * @copyright  (C) 2005 Open Source Matters, Inc. <https://www.joomla.org>
 * @copyright  (C) 2025 The VirtueMart Team.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\User\User;
use Joomla\CMS\Version;
use Joomla\CMS\Factory;
use Joomla\CMS\Date\Date as Date;
use Joomla\CMS\Mail\Mail as JMail;
use Joomla\CMS\Mail\MailHelper as JMailHelper;

//defined('VM_VERSION') or die('Direct access to VMF is not allowed');

class vmFactory {

	private static $_db = null;
	static $_apps = array();
	static $_application = null;
	static $_appId = 'site';
	private static $_session = null;
	static $_document = false;
	static $_lang = false;
	static $_cache = false;
	static $_dates = array();
	static $_config = null;
	private static $_users = array();
	static $mailer = false;
	//private static $config;

	/**
	* Get a configuration object
	*
	* Returns the global {@link JConfig} object, only creating it if it doesn't already exist.
	*
	*/
	public static function getConfig() {

		if (self::$_config === null) {

			if(JVM_VERSION<=3){

				self::$_config = Factory::getConfig();
			} else if(JVM_VERSION>=4){

				self::$_config = self::getApplication()->getConfig();
			} else {    //Old idea
				$file = VMPATH_ROOT . '/config.php';
				if(!class_exists('JConfig')){
					require($file);
				}
				if(class_exists('JConfig')){
					$vo = new vObject();
					foreach (get_object_vars(new \JConfig()) as $k => $v) {
						$vo->{$k} = $v;
					}
					self::$_config = $vo;
				}
			}

		}
		return self::$_config;
	}

	static function getDbo(){

		if(self::$_db===null){

			if(class_exists('\Joomla\CMS\Factory')){
				self::$_db = \Joomla\CMS\Factory::getDbo();
			} else {
				$conf = self::getConfig();

				$host = $conf->get('host');
				$user = $conf->get('user');
				$password = $conf->get('password');
				$database = $conf->get('db');
				$prefix = $conf->get('dbprefix');
				$driver = $conf->get('dbtype');
				$debug = $conf->get('debug');

				$options = array('driver' => $driver, 'host' => $host, 'user' => $user, 'password' => $password, 'database' => $database, 'prefix' => $prefix);

				try {
					/*if(!class_exists('vDatabaseDriverMysqli'))
						require(VMPATH_ADMIN .'/vmf/db/driver/mysqli.php');
					self::$_db = vDatabaseDriverMysqli::getInstance($options);*/
					self::$_db = \JDatabaseDriver::getInstance($options);
				}
				catch (RuntimeException $e) {
					if (!headers_sent()) {
						header('HTTP/1.1 500 Internal Server Error');
					}

					jexit('Database Error: ' . $e->getMessage());
				}

				self::$_db->setDebug($debug);
			}

		}
		return self::$_db;
	}

	static function getApplication($id=0, $config = array(), $prefix = 'v') {

		if ($id === 0) {
			$id = self::$_appId;
		}

		if (!isset(self::$_apps[$id])) {
			self::$_apps[$id] = Factory::getApplication($id, $config, $prefix);
		}

		if (isset(self::$_apps[$id])) {
			self::$_application = self::$_apps[$id];
			return self::$_apps[$id];
		} else {
			return false;
		}
	}
	
	public static function getUser($id = null) {

		static $lUser = null;
		if ($id === 0) {
			self::$_users[$id] = new User;
			return self::$_users[$id];
		} else if($id === null){
			if($lUser === null){
				$instance = self::getSession()->get('user');
				if (!($instance instanceof User)) {
					$lUser = new User;
				} else {
					$lUser = $instance;
				}
			}
			return $lUser;
		}

		if (empty(self::$_users[$id])) {
			self::$_users[$id] = new User($id);
		}

		return self::$_users[$id];
	}

	/**
	 * Return the {@link JDate} object
	 *
	 * @param   mixed  $time      The initial time for the JDate object
	 * @param   mixed  $tzOffset  The timezone offset.
	 *
	 * @return  JDate object
	 *
	 * @see     JDate
	 * @since   11.1
	 */
	public static function getDate($time = 'now', $tzOffset = null)
	{
		static $classname;
		static $mainLocale;

		$language = vmLanguage::getLanguage();
		$locale = $language->getTag();

		/*if (!isset($classname) || $locale != $mainLocale)
		{
			// Store the locale for future reference
			$mainLocale = $locale;

			if ($mainLocale !== false)
			{
				$classname = str_replace('-', '_', $mainLocale) . 'JDate';

				if (!class_exists($classname))
				{
					// The class does not exist, default to JDate
					$classname = 'JDate';
				}
			}
			else
			{
				// No tag, so default to JDate
				$classname = 'JDate';
			}
		}*/

		$key = $time . '-' . ($tzOffset instanceof DateTimeZone ? $tzOffset->getName() : (string) $tzOffset);

		if (!isset(self::$_dates[$classname][$key]))
		{
			self::$_dates[$classname][$key] = new Date($time, $tzOffset);
		}

		$date = clone self::$_dates[$classname][$key];

		return $date;
	}

	public static function getLanguage(){

		return vmLanguage::getLanguage();

	}

	public static function getSession(array $options = array()) {

		if (self::$_session === null) {

			self::$_session = self::getApplication()->getSession($options);

		}

		return self::$_session;
	}

	public static function getEditor(){
		$editorName = self::getApplication()->get('editor');
		$editor = JEditor::getInstance($editorName);
		return $editor;
	}

	public static function getDocument(){

		if (!self::$_document) {
			self::$_document = self::getApplication()->getDocument();
		}

		return self::$_document;
	}

	/**
	 * Get a mailer object.
	 *
	 * Returns the global {@link JMail} object, only creating it if it doesn't already exist.
	 *
	 * @return  JMail object
	 *
	 * @see     JMail
	 * @since   11.1
	 */
	public static function getMailer()
	{
		if (!self::$mailer)
		{
			self::$mailer = self::createMailer();
		}

		$copy = clone self::$mailer;

		return $copy;
	}

	/**
	 * Create a mailer object
	 *
	 * @return  JMail object
	 *
	 * @see     JMail
	 * @since   11.1
	 */
	protected static function createMailer()
	{
		$conf = self::getConfig();

		$smtpauth = ($conf->get('smtpauth') == 0) ? null : 1;
		$smtpuser = $conf->get('smtpuser');
		$smtppass = $conf->get('smtppass');
		$smtphost = $conf->get('smtphost');
		$smtpsecure = $conf->get('smtpsecure');
		$smtpport = $conf->get('smtpport');
		$mailfrom = $conf->get('mailfrom');
		$fromname = $conf->get('fromname');
		$mailer = $conf->get('mailer');

		// Create a JMail object
		$mail = JMail::getInstance();

		// Set default sender without Reply-to
		$mail->SetFrom(JMailHelper::cleanLine($mailfrom), JMailHelper::cleanLine($fromname), 0);

		// Default mailer is to use PHP's mail function
		switch ($mailer)
		{
			case 'smtp':
				$mail->useSMTP($smtpauth, $smtphost, $smtpuser, $smtppass, $smtpsecure, $smtpport);
				break;

			case 'sendmail':
				$mail->IsSendmail();
				break;

			default:
				$mail->IsMail();
				break;
		}

		return $mail;
	}

}