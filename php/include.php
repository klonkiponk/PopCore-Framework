<?php
    session_start();

    include 'php/sys/sys_helpers.php';	

    define ('DBHOST','localhost');
    define ('DBUSER','root');
    define ('DBPASSWORD','root');
    define ('DATABASE','Framework');
    
    define ('SITETITLE','ITSysAdminFwWebSK');
    
    include 'php/db/db.php';
    
    $DB = db_connectToDb();

    include 'php/sys/sys_POSTHandlers.php';
    include 'php/sys/sys_GETHandlers.php';


    include 'php/prince/prince.php';   
    include 'php/geshi/geshi.php';  
  
    include 'php/content/con_cssSlider.php';	
    include 'php/content/con_forum.php';	
    include 'php/content/con_createNavigation.php';	
    include 'php/content/con_createScript.php';	
    
    include 'php/sys/sys_login.php';	  	  	



    
