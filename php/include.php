<?php
    session_start();

    include 'php/sys/sys_helpers.php';	

    define ('DBHOST','localhost');
    define ('DBUSER','root');
    define ('DBPASSWORD','root');
    define ('DATABASE','Framework');
    
    define ('SITETITLE','ITSysAdminFwWebSK');
    
    include 'php/db/db.php';
    include 'php/sys/sys_db.php';    
    
    $DB = db_connectToDb();

    include 'php/sys/sys_POSTHandlers.php';
    include 'php/sys/sys_GETHandlers.php';

    include 'php/prince/prince.php';   
    //INCLUSION OF GeSHI
    include 'php/geshi/geshi.php';  
  
    
    function con_CreateSyntax($source,$language = 'php')
    {
	    $geshi = new GeSHi($source,$language);
	    $geshi->enable_classes();
	    $geshi->enable_keyword_links(false);
	    
		
		//NOTES -- delete the surrounding div, not neccessary--- try around with the HEADER AND PRE TABLE THING
	    $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
	    $geshi->set_header_type(GESHI_HEADER_PRE_TABLE); 
	    $return = "<div class='geshi'>";
	    $return .= $geshi->parse_code();
	    $return .= "</div>";					       
	    return $return;
    }
    //GeSHI end


    
    include 'php/content/con_cssSlider.php';	
    include 'php/content/con_forum.php';	
    include 'php/content/con_createNavigation.php';	
    include 'php/content/con_createScript.php';	
    
    include 'php/sys/sys_login.php';	  	  	



    
