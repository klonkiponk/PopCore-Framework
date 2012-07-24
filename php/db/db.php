<?php
include 'php/db/db_delete.php';
include 'php/db/db_write.php';	
include 'php/db/db_update.php';
include 'php/db/db_helper.php';
include 'php/db/db_read.php';
include 'php/db/db_changeOrder.php';

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
        return con_createMessage("AffectedRows: ".$GLOBALS['DB']->affected_rows,'green');
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

function db_search ()
{
	$sql = "SELECT page,name FROM page_content WHERE MATCH content AGAINST ('{$_POST['searchString']}')";
	$results = $GLOBALS['DB']->query($sql);

	if (!empty($results)) {
		echo "<ul>";
		while ($result = $results->fetch_array()){
			echo "<li><a href='./index.php?id={$result['page']}'>{$result['name']}</a></li>";
		}
		echo "</ul>";
	} else {
		echo "nothing found";
	}
}


function db_createNewArticleForm ()
{
    $mysqli = db_connectToDb();
    $sql = "SELECT * FROM page_content WHERE page={$_GET['id']}";
    $result = $GLOBALS['DB']->query( $sql );
    $result = $result->fetch_fields();


    $form = "<article><h2>Create a new Article</h2>";
	
	$form .= '<label>IMAGE</label><form id="imageform" method="post" enctype="multipart/form-data" action="./js/ajaximage.php">
				<input type="file" name="photoimg" id="photoimg" />
				</form><div id="preview">
				</div>';
	
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
							<option>PERL</option>
                        </select>";
        } elseif ($entry->name == "date"){
            //$form .= "<label for=\"$entry->name\">$entry->name</label>";
            //$form .= "<input type=\"text\" name=\"$entry->name\" value=\"".date(DATE_ATOM)."\"/>";                        
        
        } elseif ($entry->name == "image"){  
        } elseif ($entry->name == "contentorder"){    
			
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


function sys_createEditFormForPageContent()
{
    $table = $_POST['table'];
    $uid = $_POST['uid'];
    $sql = "SELECT * FROM $table WHERE uid=$uid";
    $result = $GLOBALS['DB']->query ($sql);
    $result = $result->fetch_array();
    $result = sys_deleteIntFromArray($result);
    //con_preFormat($result);

    $return = '<article><h2>Edit a Page Entry</h2>';
	$return .= '<label>IMAGE</label><a onClick="return popup(this)" href="./php/gallery/gallery.php"><button class="button" type="button">Durchsuchen</button></a><div id="preview"></div>';
	
	$return .= '<form action="" method="post" enctype="multipart/form-data">';    
    foreach($result as $key=>$value) { 
        switch ($key) {
            case "uid":
                $return .= "<input class=\"sys\"type=\"text\" name=\"$key\" value=\"$value\" />";
                break;
            case "php_code":
                //$return .= "<label for=\"$key\">$key <span class='uppercase important'>PHP Code wird ausgef&uuml;hrt auf der Seite und Code angezeigt</span></label>";
                //$return .= con_createSyntaxHighlight($value,'text/x-php',$key);
                break;                
            case "description":
                $return .= "<label for=\"$key\">$key</label>";
                $return .= "<textarea cols='85' rows='10' name=\"$key\">$value</textarea>";
                break;
            case "content":
                $return .= "<label for=\"$key\">$key</label>";
                //$return .= con_createSyntaxHighlight($value,'text/html','content');
				//$return .= con_createRTE ($value, $key);
				$value = preg_replace("/''/", "'", $value);
				$return .= "<textarea class='markItUp' name=\"$key\">$value</textarea>";
				break;
            case "code":
                $return .= "<label for=\"$key\">$key</label>";
                //$return .= con_createSyntaxHighlight($value,'text/html','content');
				//$return .= con_createRTE ($value, $key);
				$value = preg_replace("/''/", "'", $value);
				$return .= "<textarea class='markItUp' name=\"$key\">$value</textarea>";
				//$return .= "<label for=\"$key\">Code</label>";
                //$return .= con_createSyntaxHighlight($value,'text/html','code');		
                break;
            case "code_type":
                $return .= "<label for=\"$key\">Code</label>";
                $return .= "<select name='$key'><option ";
                if ($value == "PHP") {$return .= "selected";}
                $return .= " >PHP</option><option ";
                if ($value == "HTML") {$return .= "selected";}
                $return .= " >HTML</option><option ";
				if ($value == "PERL") {$return .= "selected";}
                $return .= " >PERL</option><option "; 				
                if ($value == "CSS") {$return .= "selected";}
                $return .= " >CSS</option><option ";
                if ($value == "APACHE") {$return .= "selected";}
                $return .= " >APACHE</option></select>";
                break;
            case "image":
            	break;
			case "page":
            	break;
			case "date":
            	break;
            case "contentorder":
            	break;		
            default:
                $return .= "<label for=\"$key\">$key</label>";
                $return .= "<input type=\"text\" name=\"$key\" value=\"$value\"/>";
                break;
        }
    }     
    $return .= "  
            <button type='submit' name='action' value='update' class='button save' >update</button>
            <button type='submit' name='action' value='delete' class='button delete' >delete</button>
            <button type='submit' name='action' value='cancel' class='button spark' >cancel</button>";
    $return .= "<input class='sys' type='text' name='uid' value='$uid'/>
                    <input class='sys' type='text' name='table' value='$table'/>                
          </form></article>";
    return $return;
    
    
    
}



function con_createEditForm ()
{
    $mysqli = db_connectToDb();
    $uid = $_POST['uid'];
    $table = $_POST['table'];
    $sql = "SELECT * FROM $table WHERE uid=$uid";
    
    $result = $GLOBALS['DB']->query( $sql );

	$return = "<h2>edit a function</h2>";
    
    $row = $result->fetch_array();
    foreach($row as $key=>$value){
        if (is_int($key)){ 
            unset($row[$key]);
        }
    }
    $return .= '<form action="" method="post">';    
    foreach($row as $key=>$value) { 
        switch ($key) {
            case "uid":
                $return .= "<input class=\"sys\"type=\"text\" name=\"$key\" value=\"$value\" />";
                break;
            case "code":
                $return .= "<label for=\"$key\">$key</label>";
                $return .= con_createSyntaxHighlight($value);
                break;                
            case "description":
                $return .= "<label for=\"$key\">$key</label>";
                $return .= "<textarea cols='85' rows='10' name=\"$key\">$value</textarea>";
                break;
            default:
                $return .= "<label for=\"$key\">$key</label>";
                $return .= "<input type=\"text\" name=\"$key\" value=\"$value\"/>";
                break;
        }
    }     
    $return .= "  
            </br>
            <button type='submit' name='update' class='button save' >update</button>
            <button type='submit' name='delete' class='button delete' >delete</button>
            <button type='submit' name='cancel' class='button spark' >cancel</button>";
    $return .= "<input class='sys' type='text' name='uid' value='$uid'/>
                    <input class='sys' type='text' name='table' value='$table'/>                
          </form>";    
    return $return;
}

function con_createPageEditForm (){
    $mysqli = db_connectToDb();
    $sql = "SELECT * FROM pages";
    $mysqli = $GLOBALS['DB']->query($sql);
    $row = $GLOBALS['DB']->fetch_array();
    
    $row = sys_deleteIntFromArray($row);
    
    
    $return = "";
    foreach ($row as $key=>$value) {
        switch ($key) {
            case "uid":    
                $return .= "<input class=\"sys\"type=\"text\" name=\"$key\" value=\"$value\" />";
                break;
            default:
                $return .= "<label for=\"$key\">$key</label>";
                $return .= "<input type=\"text\" name=\"$key\" value=\"$value\"/>";
                break;            
        }
    }
    $return .= "  
            </br>
            <button type='submit' name='update' class='button save' >update</button>
            <button type='submit' name='delete' class='button delete' >delete</button>
            <button type='submit' name='cancel' class='button spark' >cancel</button>
            </form>";
    return $return;
}

function con_createGeneralForm ($table)
{
    $mysqli = db_connectToDb();   
    $sql = "SELECT * FROM $table";
    //echo $sql;
    $result = $GLOBALS['DB']->query( $sql );
    $result = $result->fetch_fields();
//    con_preFormat($result);
//    unset($result[0]);
//    con_preFormat($result);
    $fields = array();
    foreach($result as $field){
        $fields[] = $field->name;
    }
    
    if(isset($_POST['edit'])){
        $return['content'] = con_createEditForm();
        $return['fields'] = $fields;
        $return['table'] = $table;
    } else {
    //con_preFormat($fields);

  	$return['content'] = "<h2>Create a new Entry</h2>";
    $return['content'] .=  "<form action=\"\" method=\"post\" >";    
    //con_preFormat($result);
    foreach ($result as $entry) {
        //con_preFormat ($entry);
        //echo $entry->type;
        //echo $entry->name;
            
        if($entry->name == 'uid'){ 
            
        } else {
            switch ($entry->type) {
                case 252:
                    $return['content'] .= "<label for=\"$entry->name\">$entry->name</label>";
                    $return['content'] .= "<textarea cols='85' rows='10' name=\"$entry->name\"></textarea>";
                    break;
                default:
                    $return['content'] .= "<label for=\"$entry->name\">$entry->name</label>";
                    $return['content'] .= "<input type=\"text\" name=\"$entry->name\" value=\"\"/>";
                    break;
            }        
        }}//end of uid check
        $return['content'] .= "<input class=\"sys\" type=\"text\" name=\"table\" value=\"$table\"/>";
        $return['content'] .= "<br/><button type=\"submit\" name=\"submit\" class=\"button edit\">write</button>";
        $return['content'] .= "</form>";
    }




        $return['fields'] = $fields;
        $return['table'] = $table;
    return $return;
}

function sys_getElementsFromDb ($table,$fields = "*")
{
    $mysqli = db_connectToDb();           
    $sql = "SELECT ";
    
    if (is_array($fields)){
    foreach ($fields as $field) {
        $sql .= $field.',';
    }
        
    $sql = rtrim($sql,',');
    } else {
        $sql .= $fields;
    }       
    $sql .= " FROM $table";                       
    $result = $GLOBALS['DB']->query( $sql );
    return $result;
}

function con_listRows($result)
{    
    $return = "<h1>Entries</h1>"; 
    $return .= "<ul>";
    while ($row = $result->fetch_array()) {
        $return .= "<li><a href=\"#{$row[1]}\">{$row[1]}</a></li>";
    }
    $return .= "</ul>";
    return $return;
}

function con_echoEachEntry($result,$table)
{   
   $return = ""; 
    while ($row = $result->fetch_array()) { //HERE BEGINNS THE FOR EACH PART
        foreach ($row as $key=>$value){
            if (is_int($key)){
                unset($row[$key]);
            }
        }
        $return .= "<article>";  
        foreach ($row as $key=>$value){  
            switch ($key) {
                case "uid":
                    $return .= "<input class=\"sys\" type=\"text\" name=\"$key\" value=\"$value\" />";
                    break;
                case "name":
                    $return .= "<a name=\"$value\"/></a>";
                    $return .= "<h2>$value</h2>";
                    break;
                case "code":
                    $return .= "<label for=\"$key\">$key</label>";                    
                    $return .= con_createSyntaxHighlight($value);
                    break;
                default:
                    $return .= "<h3>$key:</h3>";
                    $return .= "<p>$value</p>";
                    break;
            }
        }
        if (isset($_SESSION['loggedin'])){
            if ($_SESSION['role'] == 1) {  //Funktionen nur fuer Admins freischalten
                $return .= "
                <form action=\"\" method=\"post\" style=\"text-align:right;\"><button type='submit' name='edit' class='button edit' value='edit' >EDIT</button> 
                <input class='sys' type='text' name='uid' value='{$row['uid']}'/>
                <input class='sys' type='text' name='table' value='$table'/>        
                <button type='submit' name='delete' class='button delete' value='loeschen' >DELETE</button>                       
                </form>
                ";
            }
        }
        $return .= "</article>";
    }
    return $return;
}
