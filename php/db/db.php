<?php
include 'php/db/db_delete.php';
include 'php/db/db_write.php';	
include 'php/db/db_update.php';
include 'php/db/db_helper.php';
include 'php/db/db_read.php';

/**
 * performs a Database Query
 * is used for UPDATE or INSERT
 *
 * @author Kevin Siegerth
 */
function db_performWritingQuery ($sql)
{
    if ($GLOBALS['DB']->query($sql) == false) {
        return con_createMessage($GLOBALS['DB']->error,'red');
    } else {
        return con_createMessage($sql,'green');
    }
}
/**
 * connects to database, returns Message or Object
 *
 * @return void
 * @author Kevin Siegerth
 */
function db_connectToDb ()
{
    $mysqli = new mysqli(DBHOST, DBUSER , DBPASSWORD , DATABASE);
    if (mysqli_connect_errno()) {
        con_createMessage(mysqli_connect_error(),'red');
        exit();
    }
    return $mysqli;
}




function db_createNewArticleForm () {
    $mysqli = db_connectToDb();
    $sql = "SELECT * FROM page_content WHERE page={$_GET['id']}";
    $result = $GLOBALS['DB']->query( $sql );
    $result = $result->fetch_fields();


    $form = "<article><h2>Create a new Article</h2>";
    $form .=  "<form enctype=\"multipart/form-data\" action=\"\" method=\"post\" >";    

    foreach ($result as $entry) {
        if($entry->name == 'uid'){
        } elseif ($entry->name == 'page') {
            $form .= "<input class=\"sys\" type=\"text\" name=\"page\" value=\"{$_GET['id']}\"/>";
        } elseif ($entry->name == 'content') {
            $form .= "<label for=\"$entry->name\">$entry->name</label>";
			$form .= "<textarea class='markItUp' name=\"$entry->name\"></textarea>";
			//$form .= con_createSyntaxHighlight('','text/html','content');
        } elseif ($entry->name == 'php_code') {
            //$form .= "<label for=\"$entry->name\">$entry->name</label>";
            //$form .= con_createSyntaxHighlight('','text/x-php','php_code');
        } elseif ($entry->name == 'code') {
            $form .= "<label for=\"$entry->name\">$entry->name</label>";
			$form .= "<textarea class='markItUp' name=\"$entry->name\"></textarea>";
            //$form .= con_createSyntaxHighlight('','text/x-php','code');                                                   
        } elseif ($entry->name == 'code_type') {
            $form .= "<label for=\"$entry->name\">$entry->name</label>";
            $form .= "  <select name='$entry->name'>
                            <option>PHP</option>
                            <option>HTML</option>
                            <option>CSS</option>
                            <option>APACHE</option>                                        
                        </select>";
        } elseif ($entry->name == "date"){
            //$form .= "<label for=\"$entry->name\">$entry->name</label>";
            //$form .= "<input type=\"text\" name=\"$entry->name\" value=\"".date(DATE_ATOM)."\"/>";                        
        
        } elseif ($entry->name == "image"){
			
		} else {
            switch ($entry->type) {
                default:
                    $form .= "<label for=\"$entry->name\">$entry->name</label>";
                    $form .= "<input type=\"text\" name=\"$entry->name\" value=\"\"/>";
                    break;
            }//end Switch        
        }//end else
    }//end foreach
    $form .= "<input class=\"sys\" type=\"text\" name=\"table\" value=\"page_content\"/>";
    $form .= "<br/><button type=\"submit\" name=\"action\" class=\"button edit\" value=\"writeToDb\">write</button>";
    $form .= "</form></article>";

    return $form;         
}//end function