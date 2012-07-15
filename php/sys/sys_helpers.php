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

function con_createNewArticleButton ()
{
    return '<form class="margin" action=" " method="post"><button type="submit" name="action" value="newArticle" class="button star">New Article</button></form>';
} 

/**
 * con_createFooter function.
 * 
 * @access public
 * @return void
 */
function con_createFooter ()
{
    echo "<p>PHP Workshop</p>\n";
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
    $sql = "SELECT name FROM pages WHERE pid = $id";
    $result = $GLOBALS['DB']->query($sql);
    $result = $result->fetch_array();
    return $result['name'];
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
function con_createSyntaxHighlight ($content = "",$mode = "text/html",$name = "code")
{
    if (!isset($GLOBALS['CodeCount'])){
        $GLOBALS['CodeCount'] = 1;
    }
    $return = "<textarea id='code-".$GLOBALS['CodeCount']."' cols='85' rows='10' name='$name'>$content</textarea>";

    $return .= '<script>
        var editor = CodeMirror.fromTextArea(document.getElementById("code-'.$GLOBALS['CodeCount'].'"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "'.$mode.'",
        indentUnit: 4,
        indentWithTabs: false,
        enterMode: "keep",
        lineWrapping: true,
        tabMode: "shift"
        });
        </script>';
        $GLOBALS['CodeCount'] ++;
    return $return;    
}

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
    echo " :: $title</title>\n";
    echo "<link rel=\"icon\" href=\"img/favicon.ico\" type=\"image/x-icon\" />\n";  
    echo "<meta charset=\"UTF-8\">\n";
    echo "<meta name=\"description\" content=\"$description\">\n";
    echo "<meta name='author' content='$author'>\n";
    sys_includeCss('css');
    echo '<script src="js/CodeMirror/lib/codemirror.js"></script>';
    echo '<script src="js/CodeMirror/mode/xml/xml.js"></script>';
    echo '<script src="js/CodeMirror/mode/javascript/javascript.js"></script>';
    echo '<script src="js/CodeMirror/mode/css/css.js"></script>';
    echo '<script src="js/CodeMirror/mode/clike/clike.js"></script>';
    echo '<script src="js/CodeMirror/mode/php/php.js"></script>';
    echo '<script src="js/CodeMirror/mode/shell/shell.js"></script>';
    echo "</head>\n";
}
function sys_includeAdditionalScripts ()
{ 
    echo '
        <script src="./js/libs/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="markitup/jquery.markitup.js"></script>
		<script type="text/javascript" src="etc/markItUp.js"></script>
		<link rel="stylesheet" type="text/css" href="markitup/skins/markitup/style.css" />
		<link rel="stylesheet" type="text/css" href="markitup/sets/html/style.css" />
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
		</script>
		
		
		
    ';
}

function sys_includeCss ($path = "css")
{
    $dir = opendir($path);
    while (false !== ($file = readdir($dir))){
        if ($file != "." && $file != ".."){
            $type = explode(".",$file);
            if ($type[1] == "css"){
                if ($type[0] == "print"){
                    echo "<link rel='stylesheet' type='text/css' media='print' href='$path/$file' />\n";
                } else {
                    echo "<link rel='stylesheet' type='text/css' href='$path/$file' />\n";
                }
            }   
        }           
    }
    closedir($dir);
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

function con_createRTE ($content, $name)
{
	include 'fckeditor/fckeditor.php';
	$oFCKeditor = new FCKeditor($name) ;
	$oFCKeditor->BasePath = '/fckeditor/' ;
	$oFCKeditor->Value = $content;
	return $oFCKeditor->create() ;
}