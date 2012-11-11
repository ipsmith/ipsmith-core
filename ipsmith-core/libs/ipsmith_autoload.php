<?php
/**
 * Our class autoloader
 *
 * @author Rainer Bendig <rbe@ipsmith.org>
 * @version 1.0
 * @since 0.0.1
 */
 
function ipsmith_autoload($class) 
{
	
	if(file_exists(LIB_DIR.'/classes/' . $class . '.class.php'))
	{
    include(LIB_DIR.'/classes/' . $class . '.class.php');
  } 
  
  if ( file_exists(LIB_DIR.'/classes/dbos/' . $class . '.dbo.php') )
  {
  	file_exists(LIB_DIR.'/classes/dbos/' . $class . '.dbo.php');
  }
}
spl_autoload_register('ipsmith_autoload');