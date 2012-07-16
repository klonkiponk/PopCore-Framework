<?php

function sys_createEditFormForPageContent()
{
    $table = $_POST['table'];
    $uid = $_POST['uid'];
    $sql = "SELECT * FROM $table WHERE uid=$uid";
    $result = $GLOBALS['DB']->query ($sql);
    $result = $result->fetch_array();
    $result = sys_deleteIntFromArray($result);
    //con_preFormat($result);

    $return = '<article><h2>Edit an Page Entry</h2>';
	$return .= '<label>IMAGE</label><form id="imageform" method="post" enctype="multipart/form-data" action="./js/ajaximage.php">
				<input type="file" name="photoimg" id="photoimg" />
				</form><div id="preview">
</div>';
	
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

function sys_deleteIntFromArray ($array)
{
    foreach ($array as $key=>$value) {
        if (is_int($key)) {
            unset($array[$key]);
        }
    }
    return $array;
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

function htmlEntityDecode($array) {
    foreach ($array as $key=>$value) {
        $array[$key] = html_entity_decode($value);
    }
    return $array;
}        