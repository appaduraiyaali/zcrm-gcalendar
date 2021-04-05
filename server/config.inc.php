<?php
    chdir(dirname(__FILE__));	
   // echo 'Setting log director';
    $logfile_dir = __DIR__.DIRECTORY_SEPARATOR."/logs/";  
    $logfile = $logfile_dir . "php_" . date("y-m-d") . ".log";
    $logfile_delete_days = 10;

    function error_handler($errno, $errstr, $errfile, $errline)
    {
        global $logfile_dir, $logfile, $logfile_delete_days;

        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting, so let it fall
            // through to the standard PHP error handler
            return false;
        }

        $filename = basename($errfile);

        switch ($errno) {
            case E_USER_ERROR:
                file_put_contents($logfile, date("y-m-d H:i:s.").gettimeofday()["usec"] . " $filename ($errline): " . "ERROR >> message = [$errno] $errstr\n", FILE_APPEND | LOCK_EX);
                break;

            case E_USER_WARNING:
                file_put_contents($logfile, date("y-m-d H:i:s.").gettimeofday()["usec"] . " $filename ($errline): " . "WARNING >> message = $errstr\n", FILE_APPEND | LOCK_EX);
                break;

            case E_USER_NOTICE:
                file_put_contents($logfile, date("y-m-d H:i:s.").gettimeofday()["usec"] . " $filename ($errline): " . "NOTICE >> message = $errstr\n", FILE_APPEND | LOCK_EX);
                break;

            default:
                file_put_contents($logfile, date("y-m-d H:i:s.").gettimeofday()["usec"] . " $filename ($errline): " . "UNKNOWN >> message = $errstr\n", FILE_APPEND | LOCK_EX);
                break;
        }

        // delete any files older than 30 days
        $files = glob($logfile_dir . "*");
        $now   = time();

        foreach ($files as $file)
            if (is_file($file))
                if ($now - filemtime($file) >= 60 * 60 * 24 * $logfile_delete_days)
                //    unlink($file);

        return true;    // Don't execute PHP internal error handler
    }

    set_error_handler("error_handler");
   // trigger_error("testing 1,2,3", E_USER_NOTICE);

   define ('DATE_FORMAT', 'Y-m-d\TH:i:s\Z');
   define ('DEPLOYMENT', 'PRODUCTION');
   define('ZURL','https://www.zohoapis.com/crm/v2/');
   define('SESSIONFOLDER','C:\xampp\htdocs\lacure-calendar\app\sessionfolder');
  // define('ZURL','https://sandbox.zohoapis.com/crm/v2/');
	define ('CONFIGFILE', 'C:\lacureconfig\application.config');
	set_include_path(get_include_path() . PATH_SEPARATOR . '..');    
    set_include_path(get_include_path() . PATH_SEPARATOR . '.');    
   /************************************************************************ 
    * OPTIONAL ON SOME INSTALLATIONS
    *
    * Set include path to root of library, relative to Samples directory.
    * Only needed when running library from local directory.
    * If library is installed in PHP include path, this is not needed
    ***********************************************************************/       
     function __autoload($className){
        $filePath = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        $includePaths = explode(PATH_SEPARATOR, get_include_path());
        foreach($includePaths as $includePath){
		//echo('Include Path '. $includePath . $filePath . $className	);
            if(file_exists($includePath . DIRECTORY_SEPARATOR . $filePath)){
                require_once $filePath;
                return;
            }
        }
    }
  


