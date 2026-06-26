<?php
defined ('_JEXEC') or die('Restricted access');

/**
 *
 * VirtueMart script file
 *
 * This file is executed during install/upgrade and uninstall
 *
 * @author Max Milbers, Valérie Isaksen
 * @package VirtueMart
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;

class Com_TcpdfInstallerScript {
	/**
	 * Constructor
	 *
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 */
	public function __construct(InstallerAdapter $adapter)
	{

	}

	public function includeFileFolderClasses(){
		$jVersion = JVERSION;
		if ($pos = strpos(JVERSION, '-')!==false) {
			//$delimiter = '-';
			$jVersion = substr($jVersion,0, $pos);
		}
		$delimiter = '.';
		$ja = explode( $delimiter, $jVersion);
		$jversion = $ja[0];
		defined('JVM_VERSION') or define ('JVM_VERSION', $jversion);

		if(JVM_VERSION>3) {
			class_alias('\Joomla\CMS\Filesystem\File', 'File');
			class_alias('\Joomla\CMS\Filesystem\Folder', 'Folder');
		} else {
			class_alias('\Joomla\Filesystem\File', 'File');
			class_alias('\Joomla\Filesystem\Folder', 'Folder');
		}

		return true;
	}

	public function init()
	{
		static $first = true;
		if(!$first) {
			return;
		}

		$this->includeFileFolderClasses();

		$max_execution_time = ini_get ('max_execution_time');
		if ((int)$max_execution_time < 120) {
			@ini_set ('max_execution_time', '120');
		}

		$mL = ini_get('memory_limit');
		$mLimit = 0;
		if(!empty($mL)){
			$u = strtoupper(substr($mL,-1));
			$mLimit = (int)substr($mL,0,-1);
			if($mLimit>0){

				if($u == 'M'){
					//$mLimit = $mLimit * 1048576;
				} else if($u == 'G'){
					$mLimit = $mLimit * 1024;
				} else if($u == 'K'){
					$mLimit = $mLimit / 1024.0;
				} else {
					$mLimit = $mLimit / 1048576.0;
				}
				$mLimit = (int) $mLimit - 5; // 5 MB reserve
				if($mLimit<=0){
					$mLimit = 1;
					$m = 'Increase your php memory limit, which is must too low to run VM, your current memory limit is set as '.$mL.' ='.$mLimit.'MB';
					vmError($m,$m);
				}
			}
		}
		if ($mLimit < 128) {
			@ini_set ('memory_limit', '128M');
		}

		if (is_file(JPATH_ROOT .'/administrator/components/com_tcpdf/install.xml')) {
			File::delete(JPATH_ROOT .'/administrator/components/com_tcpdf/install.xml');
		}
		$first = false;

		return true;
	}

	public function install (InstallerAdapter $adapter) {

		$this->tcpdfInstall();

		return true;
	}

	public function update (InstallerAdapter $adapter) {

		$this->tcpdfInstall();

		return true;
	}

	public function tcpdfInstall () {

		$path =  dirname(__FILE__);

		$this->init();

		// libraries auto move
		$src = $path . "/libraries";
		$dst = JPATH_ROOT .'/libraries';
		$this->recurse_copy ($src, $dst);

		$html = '<a
				href="http://virtuemart.net"
				target="_blank"> <img
					border="0"
					align="left" style="margin-right: 20px"
					src="components/com_virtuemart/assets/images/vm_menulogo.png"
					alt="Cart" /> </a>';
		$html .= '<h3 style="clear: both;">TcPdf moved to the joomla libraries folder</h3>';
		$html .= "<h3>Installation Successful.</h3>";

		echo $html;

		if(class_exists('vRequest')) vRequest::setVar('tcpdf_html',$html);
		$_REQUEST['tcpdf_html'] = $html;
		return true;

	}

	/**
	 * copy all $src to $dst folder and remove it
	 *
	 * @author Max Milbers
	 * @param String $src path
	 * @param String $dst path
	 * @param String $type modulesBE, modules, plugins, languageBE, languageFE
	 */
	private function recurse_copy ($src, $dst) {

		$failed = false;
		$dir = opendir ($src);

		if (is_resource ($dir)) {
			while (FALSE !== ($file = readdir ($dir))) {
				if (($file != '.') && ($file != '..')) {
					if (is_dir ($src . '/' . $file)) {
						if(!is_dir($dst . '/' . $file) and !mkdir($dst . '/' . $file,0755, true)) {
							$app = Factory::getApplication ();
							$app->enqueueMessage ('Couldnt create folder ' . $dst . '/' . $file);
							$failed = true;
						}
						$this->recurse_copy ($src . '/' . $file, $dst . '/' . $file);
					} else {
						if (is_file($dst . '/' . $file)) {
							if (!unlink ($dst . '/' . $file)) {
								$app = Factory::getApplication ();
								$app->enqueueMessage ('Couldnt delete ' . $dst . '/' . $file);
								//return false;
							}
						}
						if (!rename ($src . '/' . $file, $dst . '/' . $file)) {
							$app = Factory::getApplication ();
							$app->enqueueMessage ('Couldnt move ' . $src . '/' . $file . ' to ' . $dst . '/' . $file);
							$failed = true;
							//return false;
						}
					}
				}
			}
			closedir ($dir);
			if (is_dir ($src) and !$failed) {
				rmdir ($src);
			}
		} else {
			$app = Factory::getApplication ();
			$app->enqueueMessage ('TcPdf Installer recurse_copy; Couldnt read source directory '.$dir);
			return false;
		}
		return !$failed;
	}


	public function uninstall (InstallerAdapter $adapter) {

		$this->includeFileFolderClasses();

		if(is_dir(JPATH_ROOT .'/libraries/vendor/tecnickcom/tcpdf')){
			rmdir(JPATH_ROOT .'/libraries/vendor/tecnickcom/tcpdf');
		}
		if(is_file(JPATH_ROOT .'/libraries/src/Document/PdfDocument.php')){
			unlink(JPATH_ROOT .'/libraries/src/Document/PdfDocument.php');
		}
		if(is_dir(JPATH_ROOT .'/administrator/components/com_tcpdf')){
			unlink(JPATH_ROOT .'/administrator/components/com_tcpdf');
		}

		$html = '<h3 style="clear: both;">TcPdf removed from joomla libraries folder</h3>';
		$html .= "<h3>Uninstall Successful.</h3>";
		echo $html;

		return true;
	}

	public function postflight (string $type, InstallerAdapter $adapter) {
		if(is_dir(JPATH_ROOT .'/administrator/components/com_tcpdf/libraries')){
			self::rrmdir(JPATH_ROOT .'/administrator/components/com_tcpdf/libraries');
		}

		if(is_dir(JPATH_ROOT .'/libraries/joomla/pdf')){
			self::rrmdir(JPATH_ROOT .'/libraries/joomla/pdf');
		}

		if(is_dir(JPATH_ROOT .'/libraries/tcpdf')){
			self::rrmdir(JPATH_ROOT .'/libraries/tcpdf');
		}

		return true;
	}

	/*
	 * @author holger1 at NOSPAMzentralplan dot de
	 */

	static function rrmdir($dir) {

		if (is_dir($dir)) {

			$objects = scandir($dir);

			foreach ($objects as $object) {

				if ($object != "." && $object != "..") {

					if (filetype($dir."/".$object) == "dir") self::rrmdir($dir."/".$object); else unlink($dir."/".$object);

				}

			}

			reset($objects);

			rmdir($dir);

		}

	}
}

// pure php no tag