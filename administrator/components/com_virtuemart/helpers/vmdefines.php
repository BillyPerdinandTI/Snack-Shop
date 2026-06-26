<?php
/**
 * defines helper class
 *
 * We define here paths and registere classes
 *
 * @package	VirtueMart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (c) 2016-2020 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL 2, see COPYRIGHT.php
 */

defined('_JEXEC') or die('Restricted access');

/**
 *
 * We need this extra paths to have always the correct path undependent by loaded application, module or plugin
 * Plugin, module developers must always include this config at start of their application
 *   $vmConfig = VmConfig::loadConfig(); // load the config and create an instance
 *  $vmConfig -> jQuery(); // for use of jQuery
 *  Then always use the defined paths below to ensure future stability
 */

class vmDefines {

	static $_appId = 'site';

	public static function loadJoomlaCms(){


		if (file_exists(VMPATH_ROOT . '/defines.php'))
		{
			include_once VMPATH_ROOT . '/defines.php';
		}

		if (!defined('_JDEFINES'))
		{
			define('JPATH_BASE',VMPATH_BASE);
			require_once JPATH_BASE . '/includes/defines.php';
		}

		require_once JPATH_BASE . '/includes/framework.php';

	}

	static $included = false;

	static function defines ($appId=0, $path = null){


		if(self::$included) return true;
		self::$included = true;

		defined('DS') or define('DS', DIRECTORY_SEPARATOR);

		self::define_VMPATH_LIBS();


		defined ('VMPATH_ADMINISTRATOR') or define ('VMPATH_ADMINISTRATOR',	VMPATH_ROOT .'/administrator');
		defined ('VMPATH_ADMIN') or define ('VMPATH_ADMIN', VMPATH_ADMINISTRATOR .'/components/com_virtuemart' );

		defined('VM_VERSION') or define ('VM_VERSION', 4);

		if($appId===0){
			if(defined('JVERSION')){
				$appId = JFactory::getApplication()->getName();
			} else {
				$appId = 'site';
			}
		}

		self::$_appId = $appId;

		$admin = '';
		if($appId == 'administrator'){
			$admin = '/administrator';//echo('in administrator');
		}
		defined ('VMPATH_BASE') or define ('VMPATH_BASE',VMPATH_ROOT.$admin);
		defined ('VMPATH_THEMES') or define ('VMPATH_THEMES', VMPATH_ROOT.$admin.'/templates' );
		defined ('VMPATH_COMPONENT') or define( 'VMPATH_COMPONENT', VMPATH_BASE .'/components/com_virtuemart' );

		//vmSetStartTime('includefiles');

		defined ('VM_USE_BOOTSTRAP') or define ('VM_USE_BOOTSTRAP', 0);
		defined ('VMPATH_SITE') or define ('VMPATH_SITE', VMPATH_ROOT .'/components/com_virtuemart' );

		defined ('VMPATH_PLUGINLIBS') or define ('VMPATH_PLUGINLIBS', VMPATH_ADMIN .'/plugins');
		defined ('VMPATH_PLUGINS') or define ('VMPATH_PLUGINS', VMPATH_ROOT .'/plugins' );
		defined ('VMPATH_MODULES') or define ('VMPATH_MODULES', VMPATH_ROOT .'/modules' );


//legacy
		defined ('JPATH_VM_SITE') or define('JPATH_VM_SITE', VMPATH_SITE );
		defined ('JPATH_VM_ADMINISTRATOR') or define('JPATH_VM_ADMINISTRATOR', VMPATH_ADMIN);
// define( 'VMPATH_ADMIN', JPATH_ROOT.'/administrator'.'/components'.'/com_virtuemart' );
		defined('JPATH_VM_PLUGINS') or define( 'JPATH_VM_PLUGINS', VMPATH_PLUGINS );
		defined('JPATH_VM_MODULES') or define( 'JPATH_VM_MODULES', VMPATH_MODULES );

		//This number is for obstruction, similar to the prefix jos_ of joomla it should be avoided
//to use the standard 7, choose something else between 1 and 99, it is added to the ordernumber as counter
// and must not be lowered.
		defined('VM_ORDER_OFFSET') or define('VM_ORDER_OFFSET',3);

		if($path === null){
			$path = VMPATH_ROOT;
		}

		if(!class_exists('vmVersion')){
			require_once $path .'/administrator/components/com_virtuemart/version.php';
		}
		self::core($path);

		defined('VM_REV') or define('VM_REV',vmVersion::$REVISION);
		$v = hash('crc32b',(VMPATH_ROOT.VM_REV));
		defined('VM_JS_VER') or define('VM_JS_VER', $v);

		if(!defined('JVERSION')){
			self::loadJoomlaCms();
		}

		/*		if(!interface_exists('vIObject'))
					require(VMPATH_ADMIN .'/vmf/vinterfaces.php');
				if(!class_exists('vObject')) require(VMPATH_ADMIN .'/vmf/vobject.php');

				if(!class_exists('vBasicModel'))
					require(VMPATH_ADMIN .'/vmf/vbasicmodel.php');

				if(!class_exists('vController')) require(VMPATH_ADMIN .'/vmf/vcontroller.php');
		*/
		//if(!class_exists('VmTable')){
		//require(VMPATH_ADMIN .'/helpers/vmtable.php');
		//VmTable::addIncludePath(VMPATH_ADMIN .'/tables','Table');
		//}

		//if(!class_exists('VmModel')) require(VMPATH_ADMIN .'/helpers/vmmodel.php');
//		if(!class_exists('vUri')) require(VMPATH_ADMIN .'/vmf/environment/uri.php');

		//if(!class_exists('vHtml')) require(VMPATH_ADMIN .'/vmf/html/html.php');
		//if(!class_exists('vmJsApi')) require(VMPATH_ADMIN .'/helpers/vmjsapi.php');

		/*		if(!class_exists('vDispatcher')) require(VMPATH_ADMIN .'/vmf/dispatcher.php');
				if(!class_exists('vPlugin')) require(VMPATH_ADMIN .'/vmf/plugin/plugin.php');
				if(!class_exists('vUser')) require(VMPATH_ADMIN .'/vmf/user/user.php');
				//vmTime('Time to create Config', 'includefiles');
		*/
		//Force Joomla to use the FE overrides
		//defined('JPATH_SITE') or define('JPATH_SITE','VMPATH_SITE');
	}

	static public function define_VMPATH_LIBS () {
		if(defined('JVERSION')){	//We are in joomla
			defined ('VMPATH_ROOT') or define ('VMPATH_ROOT', JPATH_ROOT);

			$jVersion = JVERSION;
			if ($pos = strpos(JVERSION, '-')!==false) {
				//$delimiter = '-';
				$jVersion = substr($jVersion,0, $pos);
			}
			$delimiter = '.';
			$ja = explode( $delimiter, $jVersion);
			$jversion = $ja[0];
			defined('JVM_VERSION') or define ('JVM_VERSION', $jversion);
			defined('VM_USE_BOOTSTRAP') or define ('VM_USE_BOOTSTRAP', 1);
			$vmPathLibraries = JPATH_PLATFORM;

			defined('WP_VERSION') or define ('WP_VERSION', 0);
		} else {
			defined ('JVM_VERSION') or define ('JVM_VERSION', 0);

			//Todo ???? need to be checked
			!defined ('WPINC') or define ('WP_VERSION', get_bloginfo('version'));

			//defined ('VMPATH_ROOT') or define ('VMPATH_ROOT', dirname( __FILE__ ));

			//defined('_JEXEC') or define('_JEXEC', 1);
			$vmPathLibraries = VMPATH_ROOT .'/libraries';

		}

		defined ('VMPATH_LIBS') or define ('VMPATH_LIBS', $vmPathLibraries);
	}

	static public function core($rootPath = VMPATH_ROOT){

		$vmpath_admin = $rootPath.'/administrator/components/com_virtuemart';
		$vmpath_pluginlibs = $vmpath_admin.'/plugins';
		$vmpath_site = $rootPath.'/components/com_virtuemart';
		//if(!class_exists('JFile')) require(VMPATH_LIBS.DS.'joomla'.DS.'filesystem'.DS.'file.php');

		if(!defined('VMPATH_LIBS')) self::define_VMPATH_LIBS();
		if(!class_exists('JFile')) JLoader::register('JFile', VMPATH_LIBS.'/joomla/filesystem/file.php');
		if(!class_exists('JFolder')) JLoader::register('JFolder', VMPATH_LIBS.'/joomla/filesystem/folder.php');
		//JLoader::register('JToolbarHelper', JPATH_ADMINISTRATOR.'/includes/toolbar.php');

		require $vmpath_admin . '/vendor/autoload.php';

		class_exists('vmEcho', true);

		if(class_exists('VmConfig') and isset(VmConfig::$defined)){
			VmConfig::$defined = true;
		}

		//Compatibility for new code, Plugin or for VirtueMart code which uses VirtueMart namespaced
		//class_alias('vmFactory', '\VirtueMart\vmFactory'); not needed anymore, as we have the use statement in the files.
		//Either someone adds the use statement or the class is loaded by the autoloader or uses the full namespace.

	}

	static public function tcpdf(){

		static $tcPath = null;
		if($tcPath === null){
			$paths = array('/vendor/tecnickcom/tcpdf', '/tcpdf');
			foreach($paths as $p){
				if(file_exists(VMPATH_LIBS .$p.'/tcpdf.php')){
					$tcPath = $p;
					break;
				}
			}
			if($tcPath === null){
				vmLanguage::loadJLang('com_virtuemart_config');
				vmWarn('COM_VIRTUEMART_TCPDF_NINSTALLED');
				$tcPath = false;
			} else {
				defined ('VMPATH_TCPDF') or define ('VMPATH_TCPDF', VMPATH_LIBS .$tcPath );
				JLoader::register('TCPDF',VMPATH_TCPDF .'/tcpdf.php');
			}
		}
		return $tcPath;
	}
}