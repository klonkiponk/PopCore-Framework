<?php
    require_once 'php/include.php';
    sys_createHead("Kevin Siegerth","Development Framework");       
?>
<body>
    <header>
        <?php con_createNavigation() ?> 
    </header>

    <section class="title">
        <h1><?php echo con_createPageTitle($_GET['id']) ?></h1>
        <aside>
            <?php con_createLogin() ?>
        </aside>
    </section>
    <section id="content" role="main">
    
    <style type="text/css">
	    ul.chapters {
		    background:#FFA500;
		}
		ul.chapters li.chapter ul.sections {
			background: #FFBC40;
		}
		ul.chapters li.chapter ul.sections ul.subSections {
			background: #FFCE73;
		}
    </style>
    <?php
    	
	
	function con_listAllPages ()
	{
    	$sqlChapters = "SELECT * FROM pages WHERE type = 101";
        $chapters = $GLOBALS['DB']->query( $sqlChapters );

        $return = "";
        // - DEBUG - //
        #con_preFormat($pages);
        $return .= '<ul class="chapters">';
        while ($chapter = $chapters->fetch_array()){
	        $return .= '<li class="chapter">'.$chapter['name'].' <span style="font-weight:bold">ID:</span>'.$chapter['pid'];
	        $return .= sys_pagesDeleteForm($chapter['pid']);
	        //START OF SECTION
	        $sqlSections = "SELECT * FROM pages WHERE type = 102 AND sub = {$chapter['pid']}";
	        $sections = $GLOBALS['DB']->query($sqlSections);
	        if ($sections->num_rows != 0) {
		        $return .= '<ul class="sections">';
		        while ($section = $sections->fetch_array()) {
			    	$return .= '<li class="section">'.$section['name'].' <span style="font-weight:bold">ID:</span>'.$section['pid'];
			    	$return .= sys_pagesDeleteForm($section['pid']);
			    	
			    	//START OF SUBSECTIONS
			    	$sqlSubSections = "SELECT * FROM pages WHERE type = 103 AND sub = {$section['pid']}";
			    	$subSections = $GLOBALS['DB']->query($sqlSubSections);
			    	if ($sections->num_rows != 0) {
				    	$return .= '<ul class="subSections">';
				    	while ($subSection = $subSections->fetch_array()){
					    	$return .= '<li class="subSection">'.$subSection['name'];
					    	$return .= sys_pagesDeleteForm($subSection['pid']);
					    	$return .= '</li>';
				    	}//END OF $subSection WHILE
			    		$return .= '</ul>';
			    	}//END OF $subSection IF
			    	$return .= '</li>';//END OF SECTION LI    
		        }//END OF $section WHILE
		        $return .= '</ul>';
	        }//END of $sections if
	        $return .= '</li>'; //END OF CHAPTER LI
        }//END OF $chapter WHILE
        $return .= '</ul>';
        
        return $return;
    }
	function sys_pagesDeleteForm ($pid) {
		$return = '<form action="" method="post" style="display:inline;margin-left:25px;">';
		
		$return .= '<input type="text" class="sys" name="table" value="pages">';
		$return .= '<input type="text" class="sys" name="pid" value="'.$pid.'">';
		
		$return .= '<button type="submit" class="button delete" name="action" value="deletePage">delete</button>';
		
		$return .= '</form>';
		return $return;
	}
	
	echo sys_createNewPageForm();
	function sys_createNewPageForm(){
		$return = '
		<form action="" method="post">
			<input type="text" class="sys" name="table" value="pages">
			<label for="name">Name</label>
			<input type="text" name="name">
			<label for="sub">Unterseite von</label>
			<input type="text" name="sub">
			<label for="type">Seitentyp</label>
			<input type="text" name="type">
			<label for="permission">Permission</label>
			<input type="text" name="permission">
			<button type="submit" class="button" name="action" value="writeToDb">Write</button>
		</form>	';
		return $return;
	}
    ?>
    <article class="pagesAdmin">
	<?php echo con_listAllPages(); ?>
    </article>
    </section><?php //CONTENT DIV end ?>
    <footer>
        <?php con_createFooter() ?>
    </footer>
    <?php sys_includeAdditionalScripts() //MEANT FOR jQUERY or ELSE ?>
    <?php if(!empty($message)){echo $message;}?>
</body>
</html>