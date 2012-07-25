<?php
    session_start();


    include 'etc/constants.php';
    
    
    include 'php/sys/sys_helpers.php';	
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



    
