<?php

/**
 * con_createMessage function.
 * 
 * @access public
 * @param string $content (default: "Erfolg")
 * @param string $color (default: "green")
 * @return void
 */
function con_createMessage ($content = "Erfolg", $color = "green")
{
    $content = htmlentities($content);
    $return = "
        <div class='notification_wrapper'>
            <input id='closer' type='checkbox' />
            <label for='closer'>X</label>
            <article class=\"notification_content $color\">
                <p>$content</p>
            <div class=\"notificationProgress\"></div>
            </article>
        </div>
    ";
    return $return;
}

function con_createAdminAsideMenu ()
{
	$return  = "<div class='administrationMenu'><label>Administration</label>";
	$return .= "<a href='./phpMyAdmin/index.php' class='button phpMyAdminIcon'>PhpMyAdmin</a>";
	$return .= "<a href='./notes.php' class='button notesIcon'>Notes</a>";
	$return .= "<a href='./pages.php' class='button pagesIcon'>Pages</a>";
	$return .= "<a href='./user.php' class='button usersIcon'>User</a>";
	$return .= "<a href='./script.php' class='button scriptIcon'>Script</a></div>";
	return $return;
}


function con_createNewArticleButton ()
{
    return '<form class="margin" action="'.$_SERVER['REQUEST_URI'].'" method="post"><button type="submit" name="action" value="newArticle" class="button add">New Article</button></form>';
} 

/**
 * con_createFooter function.
 * 
 * @access public
 * @return void
 */
function con_createFooter ()
{
    echo "<p>ITSysAdminFwWebSK</p>\n";
}
    
/**
 * con_createPageTitle function.
 * 
 * @access public
 * @param mixed $id
 * @return void
 */
function con_createPageTitle ($id)
{
    $sql = "SELECT name,subtitle FROM pages WHERE pid = $id";
    $result = $GLOBALS['DB']->query($sql);
    $result = $result->fetch_array();
	$return = array('name' => $result["name"], 'subtitle' => $result["subtitle"]);
	return $return;
}

/**
 * con_createSyntaxHighlight function.
 * 
 * @access public
 * @param string $content (default: "")
 * @param string $mode (default: "text/html")
 * @param string $name (default: "code")
 * @return void
 */

function con_preFormat ($content) 
{
        echo "<pre class=\"print_r\">";
        print_r($content);
        echo "</pre>";        
}
function sys_createHead ($author = "Insert Author via HTML Template",$description = "Insert Description via HTML template")
{ 
    if (!isset($_GET['id'])){
        $_GET['id'] = 1;
    }
    db_getPageType($_GET);
    sys_securePage($_GET);
    $title = con_createPageTitle($_GET['id']);
    echo "<!doctype html>\n";
    echo "<head>\n";
    echo "<title>";
    echo SITETITLE;
    echo " :: {$title['name']}</title>\n";
    echo "<link rel=\"icon\" href=\"img/favicon.ico\" type=\"image/x-icon\" />\n";  
    echo "<meta charset=\"UTF-8\">\n";
    echo "<meta name=\"description\" content=\"$description\">\n";
    echo "<meta name='author' content='$author'>\n";
    echo sys_includeCss();
    echo "</head>\n";
}
function sys_includeAdditionalScripts ()
{ 
    echo '
        <script src="./js/libs/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="js/markitup/jquery.markitup.js"></script>
		<script type="text/javascript" src="js/markitup/markItUp.js"></script>
		<link rel="stylesheet" type="text/css" href="js/markitup/skins/markitup/style.css" />
		<link rel="stylesheet" type="text/css" href="js/markitup/sets/html/style.css" />
		<script type="text/javascript" >
		   $(document).ready(function() {
			  $(".markItUp").markItUp(mySettings);
		   });
		</script>
	';
	echo '	
		<script type="text/javascript" src="js/libs/jquery.form.js"></script>
		<script type="text/javascript" >';
		echo "
			$(document).ready(function() { 
				$('#photoimg').live('change', function()			{ 
			        $(\"#preview\").html('');
					$(\"#preview\").html('<img src=\"./img/loader.gif\" alt=\"Uploading....\"/>');
					$(\"#imageform\").ajaxForm({
						target: '#preview'
					}).submit();
				});
			});";
		echo '		
		</script>';
		
		//INCLUDE FANCYBOX
		echo '
		<link rel="stylesheet" href="./js/fancybox/jquery.fancybox.css?v=2.0.6" type="text/css" media="screen" />
		<script type="text/javascript" src="./js/fancybox/jquery.fancybox.pack.js?v=2.0.6"></script>
		<script type="text/javascript" src="./etc/fancyBox.js"></script>';
}
function sys_includeCss ()
{
    $return  = "<link rel='stylesheet' type='text/css' media='print' href='css/print.css' />\n";
   	$return .= "<link rel='stylesheet' type='text/css' href='css/style.css' />\n";
   	$return .= "<link rel='stylesheet' type='text/css' href='css/addition.css' />\n";
    return $return;
}

function sys_performLogin ($username,$password)
{
    $sql = "SELECT password,role FROM user WHERE username='$username'";    
    $result = $GLOBALS['DB']->query($sql);
    $row = $result->fetch_object();
    if (empty($row)){
        $message = con_createMessage ("LOGIN failed: Username does not exist",'red');
    } else {
        if (md5($password) == $row->password) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row->role;
            return con_createMessage ("LOGIN successfull!",'green');
        } else {
            return con_createMessage ("LOGIN failed: Password is not correct",'red');
        }
    }
}
function sys_performLogout ()
{
    session_destroy();
    echo "Logout erfolgreich, sie werden weitergeleitet";    
    //content bestimmt Zeit
    echo '<meta http-equiv="refresh" content="0; url=./">';
    //header("Location:{$_SERVER['HTTP_ORIGIN']}");
    exit;
}

function sys_deleteIntFromArray ($array)
{
    foreach ($array as $key=>$value) {
        if (is_int($key)) {
            unset($array[$key]);
        }
    }
    return $array;
}


function htmlEntityDecode($array) {
    foreach ($array as $key=>$value) {
        $array[$key] = html_entity_decode($value);
    }
    return $array;
}

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