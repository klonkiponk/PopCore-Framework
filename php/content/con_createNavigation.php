<?php
  function con_createNavigation() {
        if(!isset($_GET['id'])){
           $_GET['id'] = 1; 
        }
        
        
        if (isset($_SESSION['loggedin'])){
            switch ($_SESSION['role']) {
                case 9: //admin
                $sql = "SELECT sub,name,pid FROM pages WHERE deleted != 1 AND type <= 300";
                    break;
                case 1: //LoggedIn
                    $sql = "SELECT sub,name,pid FROM pages WHERE deleted != 1 AND permission <= 1 AND type < 300";
                    break;
            }
        } else {
            $sql = "SELECT sub,name,pid FROM pages WHERE deleted != 1 AND permission = 0 AND type < 300";
        }
        $result = $GLOBALS['DB']->query( $sql );

		$breadCrumb['section'] = "";                
		$breadCrumb['subSection'] = "";


// -- DEBUG -- //
//	  con_preFormat($GLOBALS);
//	  con_preFormat($result);

//AUSGABE der CHAPTER

        $rootPagesCount = 0;        
        $menu = '';
        while ($chapter = $result->fetch_array()) {
        
        //FÜR jedes CHAPTER
            if ($chapter['sub'] == NULL or $chapter['sub'] == 0) {
                $rootPagesCount++;
                $menu .= "<li><a class=\"root ";
                
                
            //Create and reset the Variables
                   
                //SECTION Variable
                $sectionActive = 0;
                //SUBSECTION Variable
                $subSectionActive = 0;
					
                //CHECK if current page is a active section
                $sqlSectionCheckActive = "SELECT pid,name FROM pages WHERE sub={$chapter['pid']}";
                $sectionCheckActive = $GLOBALS['DB']->query( $sqlSectionCheckActive );
                if ( $sectionCheckActive->num_rows != 0) {
                    while ($section = $sectionCheckActive->fetch_array()) {
                        if ($section['pid'] == $_GET['id']){
                            $sectionActive = 1;
							$breadCrumb['section'] = " :: <a href='index.php?id={$section['pid']}'>".$section['name']."</a>";
                        }
                        
                        //CHECK if current page is even a subsection
                        $sqlSubSectionCheckActive = "SELECT pid,name FROM pages WHERE sub={$section['pid']}";
                        $subSectionCheckActive = $GLOBALS['DB']->query( $sqlSubSectionCheckActive );
                        if ( $subSectionCheckActive->num_rows != 0) {
		                    while ($subSection = $subSectionCheckActive->fetch_array()) {
		                    	if ($subSection['pid'] == $_GET['id']){
			                    	$subSectionActive = 1;
									$breadCrumb['subSection'] = " :: <a href='index.php?id={$subSection['pid']}'>".$subSection['name']."</a>";
			                    }
                            }	                        
                        }
                    }
                }


				//DEFINE THE CLASS FOR THE ROOT ITEM
                if ($chapter['pid'] == $_GET['id']){
                    $menu .= 'active';
					$breadCrumb['chapter'] = "<a href='index.php?id={$chapter['pid']}'>".$chapter['name']."</a>";
					$GLOBALS['chapter'] = $chapter['pid'];					
                } elseif ( $sectionActive == 1 ) {
                    $menu .= 'sectionActive';
					$breadCrumb['chapter'] = "<a href='index.php?id={$chapter['pid']}'>".$chapter['name']."</a>";
					$GLOBALS['chapter'] = $chapter['pid'];					
                } elseif ( $subSectionActive == 1) {
	                $menu .= 'subSectionActive';
					$breadCrumb['chapter'] = "<a href='index.php?id={$chapter['pid']}'>".$chapter['name']."</a>";
	                $GLOBALS['chapter'] = $chapter['pid'];
                }
				

                           
                $menu .=    "\" href='index.php?id={$chapter['pid']}'><span>{$chapter['name']}</span></a>";
                
                
                
                // CREATE SECTION MENU
                $sqlSections = "SELECT pid,name FROM pages WHERE sub={$chapter['pid']}";
                $sections = $GLOBALS['DB']->query( $sqlSections );
                    
                    if ( $sections->num_rows != 0 ) {
	                    $menu .= "\n"."<ul class='section'>";
	                    while ($section = $sections->fetch_array()) {
	                        $menu .=  "\n".'<li><a class=" ';
	                        if ($section['pid'] == $_GET['id']){
	                            $menu .= 'active';
	                        }
	                        $sqlSubSectionCheckActive = "SELECT pid FROM pages WHERE sub={$section['pid']}";
	                        $subSectionCheckActive = $GLOBALS['DB']->query( $sqlSubSectionCheckActive );
	                        if ( $subSectionCheckActive->num_rows != 0) {
		                    	while ($subSection = $subSectionCheckActive->fetch_array()) {
		                    		if ($subSection['pid'] == $_GET['id']){
			                    		$menu .= ' subSectionActive';
										$breadCrumb['section'] = " :: <a href='index.php?id={$section['pid']}'>".$section['name']."</a>";
			                   		}
			                	}	                        
			                }
	                        
	                        
	                        $menu .= '" href="index.php?id='.$section['pid'].'"><span>'.$section['name'].'</span></a>';
	                        
	                        
	                        
	                        //CREATE SUBSECTION MENU
	                        $sqlSubSections = "SELECT pid,name FROM pages WHERE sub={$section['pid']}";
	                        $subSections = $GLOBALS['DB']->query($sqlSubSections);
	                        
	                        if ( $subSections->num_rows != 0 ) {
		                        $menu .= "\n".'<ul class="subSection">';
		                        while ($subSection = $subSections->fetch_array()) {
			                        $menu .= "\n".'<li><a class=" ';
           	                        if ($subSection['pid'] == $_GET['id']){
	           	                        $menu .= 'active';
	           	                    }
	           	                    $menu .= '" href="index.php?id='.$subSection['pid'].'"><span>'.$subSection['name'].'</span></a></li>'."\n";
		                        }
   		                        $menu .= "</ul>\n";
	                        }
	                        //END OF SUBSECTION MENU
	                        
	                        
	                        
	                        $menu .= "</li>\n";
	                    }
	                    $menu .= "</ul>\n";
	                    //END OF SECTION MENU	                    
                    }//END of if EMPTY Sections

                    
                $menu .= "</li>\n";
                //END OF CHAPTER ITEM
            }
        }
        //close the menu
        $menu .= '<li class="search"><form action="'.$_SERVER['REQUEST_URI'].'" method="post"><input type="search" name="searchString" placeholder=Search...><button type="submit" class="sys" name="action" value="search">search</button></form></li>';
        $menu .= "</ul></nav>\n";
        //define the width - important for centering the menu above the content;
        $width = $rootPagesCount * 108;
        $width = $width -1;
        $width = $width + 190;
        $opening = "<nav id='globalheader' style='width:{$width}px'><ul id='globalnav'>\n";
        $opening .= $menu;    
        echo $opening; #change it to a returning function instead of self-echoing
		
		$breadCrumb = $breadCrumb['chapter'].$breadCrumb['section'].$breadCrumb['subSection'];
		
		return $breadCrumb;
  }

function con_createSubNavigation()
{
  	if(!isset($_GET['id'])){
       $_GET['id'] = 1; 
    }
	
	
	
	$subMenu = "SUBMENU";
	
	
    if (isset($_SESSION['loggedin'])){
       switch ($_SESSION['role']) {
           case 9: //admin
	           $sql = "SELECT name,pid FROM pages WHERE sub={$GLOBALS['chapter']} AND deleted != 1 AND type <= 300";
	           break;
           case 1: //LoggedIn
               $sql = "SELECT name,pid FROM pages WHERE sub={$GLOBALS['chapter']} AND deleted != 1 AND permission <= 1 AND type < 300";
               break;
        }
    } else {
            $sql = "SELECT name,pid FROM pages WHERE sub={$GLOBALS['chapter']} AND deleted != 1 AND permission = 0 AND type < 300";
    }
    $result = $GLOBALS['DB']->query( $sql );
	$submenu = "<nav id='submenu'><ul>";	
	while ($section = $result->fetch_array()) {
		$submenu .= "<li class='section'><a href='index.php?id=".$section['pid']."'>{$section['name']}</a>";
		
		
		$submenu .= "<ul>";
		$sqlSubSections = "SELECT pid,name FROM pages WHERE sub={$section['pid']}";
		$subSections = $GLOBALS['DB']->query($sqlSubSections);
		while ($subSection = $subSections->fetch_array()) {
			$submenu .= "<li class='subSection'><a class='button' href='index.php?id=".$subSection['pid']."'>{$subSection['name']}</a>";
		}
		$submenu .= "</ul>";
		$submenu .= "</li>";
	}
	$submenu .= "</ul></nav>";
	return $submenu;
}