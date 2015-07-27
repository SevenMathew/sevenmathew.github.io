<?php
/**
 * _pre()
 * display variable in a easy-to-read way  
 * variable number of parameters of any type 
 * 
 * @return void
 */
function _pre() 
{
	if (is_dev()) // display these debug messages only to developers
	{
		$argumets = func_get_args(); // get the arguments passed to this function
		foreach ($argumets as $key=>$var) 
		{
			echo "\n".'<pre style="border:1px dotted red; color:#000;background:#fff;font:12px monospace;text-align:left">'."\n";
			print_r($var); // display any type of variable
			echo "\n".'</pre>'."\n";
		}
	}
} // end _pre();

/**
 * _var_dump()
 * display var_dump info in a easy to read way
 * similar to _pre();
 * 
 * @param mixed $var
 * @return void
 */
function _var_dump($var) 
{
	if (is_dev()) // display info only to developers
	{
		echo "\n".'<pre style="border:1px dotted blue; color:#000;background:#fff;text-align:left">'."\n";
		var_dump($var);
		echo "\n".'</pre>';
	}
} // end _var_dump

/**
 * is_dev()
 * checks if the request is made by a developer
 * 
 * @return bool
 */
function is_dev()
{
    global $dev_ips;
	return in_array($_SERVER['REMOTE_ADDR'], $dev_ips); // check if the ip address is in the list defined in the settings file 
}//end is_dev


/**
 * rewrite()
 * rebuild $_GET the superglobal variable using the mod rewrite rules
 * @return void
 */
function rewrite()
{
    // variable from the main index.php file; used to save the name of the current module
	global $_module;
    
    // .htaccess file sent everything that's after WWW_ROOT to $_GET['demand']
    if(isset($_GET['demand']) && $_GET['demand']!="") 
    {
        // url segments are separated by /  
        $_action_vars = explode('/', $_GET['demand']);

        // the first url segment is used to tell the module
        $_module = $_GET['module'] = trim($_action_vars[0]);

        // if there are mode than one element it means that we have at least one parameter in $_GET
        if(count($_action_vars) > 1) 
        {
            // the $_GET is rewritten to use key/value pairs after the module name
            // example.com/some_module/some_var/some_value will result in $_GET['some_var'] = $some_value;
            for ($counter = 1; $counter < count($_action_vars); $counter += 2) 
            {
                if($_action_vars[$counter] != '') // non-empty key
                {
                    if (isset($_action_vars[$counter+1])) 
                    {
                        $_GET[trim($_action_vars[$counter])] = trim($_action_vars[$counter+1]); // use current segment as key and the next one as value
                    } 
                    else 
                    {
                        $_GET[trim($_action_vars[$counter])] = ''; // assign an empty value for incomplete pairs
                    }
                }
            }
        }
    } 
    else 
    {
        // Setting default values for null request
        $_module = $_GET['module'] = 'index';
    }
    unset($_GET['demand']); // clear the demand key as it is not used in the application

    // clear any empty keys from the new $_GET superglobal
    foreach($_GET as $key => $val)
    {
        if($key == '' || $key == null)
        {
            unset($_GET[$key]);
        }
    }
} // end rewrite();


/**
 * check_request()
 * checks if this is a valid request, made in a regular way, so all the configuration files were included 
 * @return void
 */
function check_request()
{
    // even though this is a valid check the first level of security will be provided by the fact that the call will raise this error:
    // Fatal error: Call to undefined function check_request()
    if (!defined('WWW_ROOT'))
    {
        die('Direct Script Access Disabled');
    }
}// end check_request();

/**
 * redirect()
 * wrapper for Utils:redirect();
 * @return void
 */
function redirect($url, $sec=0)
{
    Utils::redirect($url, $sec);
}// end redirect();

/**
 * format_date_time()
 * returns a human readable date and time from a the provided $timestamp; uses a format defined in site configuration
 * @param integer $timestamp
 * @return string 
 */
function format_date_time($timestamp=0)
{
	if (0==$timestamp)
	{
		$timestamp = time();
	} // use current timestamp if none is provided 
	
	return date(PHP_DATETIME_FORMAT, $timestamp); // constant defined in etc/settings.php
} // end function format_date_time($timestamp=0)

//--------------MISC FUNCTIONS----------------------------------

/**
 * load_helper()
 * loads the needed helper class when an unknown function is called 
 * @param string $helper
 * @return void 
 */
function load_helper($helper)
{
	if (file_exists(SITE_LIB . 'helpers/' . $helper . '.php'))// check if such a helper exists
	{
		require_once(SITE_LIB . 'helpers/' . $helper . '.php'); // include class
	}
}// end load_helper();
