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
    define('DBNAME','zcrmgcalendar'); //iwbzkgcy_zohocalendar zcrmgcalendar
   define('DBSERVER','localhost'); //hostingssd74.netsons.net 'ls-8152237294e346a897a605a661c22defef916438.c29cwtszkczm.us-east-1.rds.amazonaws.com'
   define('DBUSER','root');// root iwbzkgcy 'dbmasteruser'
   define('DBPWD','');//'' ZohoBytek2021 'Yaa!!2016'
   define('SUBJECTEMAIL','appadurai@yaalidatrixproj.com');//'paolo@bytekmarketing.it');
   define('PROJECTPORTAL','demo2bizappln');
   define('REFRESH_TOKEN',"1000.2939b1b4f5eb03960d3fee1b8767f930.1bd7460e1e65f5211a3840367df86790");
   define('CLIENT_ID', "1000.QEPC4FW5TM2JI9T4VS009JGWOYAG7Y");
   define('CLIENT_SECRET', "4dcf0768c64d1486190dc59131a0c88978c5eb3726");
   define('COMMON_TASKLIST_NAME','Gsuite Calendar Task Group');
   define('UNMATCHEDRULEPROJECT','1600500000001484009');
   define('UNMATCHEDTASKLISTID','1600500000001484027');
   define('DOMAIN','yaalidatrixproj.com');


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
  


