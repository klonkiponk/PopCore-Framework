<?php
    require_once 'php/include.php';
    sys_createHead("Kevin Siegerth","Development Framework");       
?>
<body>
    <header>
        <?php $breadCrumb = con_createNavigation() ?> 
    </header>

    <section class="title">

			<?php $pageinfo = con_createPageTitle($_GET['id']);
		
			$title = $pageinfo['name'];
			$subtitle = $pageinfo['subtitle']
		
			?>
		<h1><?php echo $title;?></h1>
		<span class="subtitle"><?php echo $subtitle; ?></span>
        	<aside class="subMenu"><?php	echo con_createSubNavigation(); ?></aside>

        <aside class="user">
            <?php con_createLogin();
				if (isset($_SESSION['loggedin'])){
					if ($_SESSION['role'] == 9) {
						echo con_createAdminAsideMenu();
					}
				}
			?>
        </aside>
    </section>
    <footer>
        <p><?php echo $breadCrumb?></p>
    </footer>
    <section id="content" role="main">
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
		
		$return .= '<input type="hidden" name="table" value="pages">';
		$return .= '<input type="hidden" name="pid" value="'.$pid.'">';

		$return .= '<button type="submit" class="button edit" name="action" value="editPage">edit</button>';		
		$return .= '<button type="submit" class="button delete" name="action" value="deletePage">delete</button>';
		
		$return .= '</form>';
		return $return;
	}
	
	
	function sys_createNewPageForm(){
		$return = '<h2>create New Page</h2>
		<form action="" method="post">
			<input type="hidden" name="table" value="pages">
			<label for="name">Name</label>
			<input type="text" name="name">
			<label for="subtitle">Subtitle</label>
			<input type="text" name="subtitle">
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
	
	function sys_createEditPageForm()
	{
		$sqlPage = "SELECT pid,name,subtitle,sub,type,permission FROM pages WHERE pid={$_POST['pid']}";
        $page = $GLOBALS['DB']->query($sqlPage);
        $page = $page->fetch_array();
        
        $return = "<form action='".$_SERVER['REQUEST_URI']."' method='post'>";
        
        $return .= '<input type="hidden" name="table" value="pages">
			       <input type="hidden" name="pid" value="'.$page['pid'].'">
				   <label for="name">Name</label>
				   <input type="text" name="name" value="'.$page['name'].'">
   				   <label for="subtitle">Subtitle</label>
				   <input type="text" name="subtitle" value="'.$page['subtitle'].'">
				   <label for="sub">Unterseite von</label>
				   <input type="text" name="sub" value="'.$page['sub'].'">
				   <label for="type">Seitentyp</label>
				   <input type="text" name="type" value="'.$page['type'].'">
				   <label for="permission">Permission</label>
		   	        <select name="permission">
	                	<option value="">ALL</option>
	                	<option value="1">Registered</option>
	                	<option value="9">Administrator</option>
	                </select>				   
				   <input type="text" name="permission" value="'.$page['permission'].'">
				   <button type="submit" class="button" name="action" value="updatePage">Update</button>';
        
        $return .= "</form>";
        
        return $return;
	}
	
	function db_updatePage()
	{
    $sql = "UPDATE {$_POST['table']} SET ";
    $sql .= "name='{$_POST['name']}',subtitle='{$_POST['subtitle']}',sub='{$_POST['sub']}',type='{$_POST['type']}',permission='{$_POST['permission']}'";
    $sql .= " WHERE pid ={$_POST['pid']}";
    
    //     -- DEBUG --     //
    //con_preFormat($sql); //
    
    //Perform Query
	
    return db_performWritingQuery ($sql);
	}
	
    ?>
    <article class="pagesAdmin">
	<?php 
		if (!empty($editForm)) {
			echo $editForm;
		} else {
			echo sys_createNewPageForm();
			echo con_listAllPages(); 
		}
	?>    </section><?php //CONTENT DIV end ?>
    <footer>
        <?php con_createFooter() ?>

    </footer>

	
	
	<?php sys_includeAdditionalScripts() //MEANT FOR jQUERY or ELSE ?>
    <?php if(!empty($message)){echo $message;}?>
</body>
</html>