<?php
/**
 * writes new Entries to the Database
 *
 * @author Kevin Siegerth
 */
function db_writeToDb ()
{
    //define Variables
    $sql = "";
    
    //Cleanup $_POST from Sys Values
    $entries = sys_unsetSysVariables ($_POST);
                         
    //create SQL Query
    $sql .= "INSERT INTO {$_POST['table']} (";
    
    //COLUMNS
    
    
    //CASE: PAGE_CONTENT - also enter into contentorder for sorting (Column contentorder may be changed later)
    
    //if ($_POST['$table'] == 'page_content'){
    //    $entries['contentorder'] = $entries['uid'];
    //}
    
    //con_preFormat($_POST);
    
    
    foreach ($entries as $key=>$value) {
        $sql .= "$key,";
    }
    $sql = rtrim($sql,",");
    
    //complete SQL
    $sql .= ") VALUES ("; 
    
    //VALUES 
    foreach ($entries as $key=>$value) {
        switch ($key) {
            case "password":
                $value = md5($value);
                $sql .= "'$value',";
                break;
            case "php_code":
                $sql .= "'$value',";
                break;
            case "content":
				$sql .= "'";
				$sql .= mysql_real_escape_string($value);
				$sql .= "',";
                break;
            case "code":
				$sql .= "'";
				$sql .= preg_replace("/'/", "''", $value);
				$sql .= "',";				
				break;
			default:
                $value = html_entity_decode($value);
                $sql .= "'$value',";
        }//end of switch
    }//end of foreach
    $sql = rtrim($sql,",");
    $sql .= ")";

    //     -- DEBUG --     //
    //con_preFormat($sql); // 
    
    //Perform Query  
    $write1 = db_performWritingQuery ($sql);

    if ($_POST['table'] == 'page_content'){
		$sql = "UPDATE page_content SET contentorder='{$GLOBALS['DB']->insert_id}' WHERE uid = {$GLOBALS['DB']->insert_id}";   
		return db_performWritingQuery ($sql);
	} elseif ($_POST['table'] == 'pages'){
		$sql = "UPDATE pages SET pageorder='{$GLOBALS['DB']->insert_id}' WHERE pid = {$GLOBALS['DB']->insert_id}";   
		return db_performWritingQuery ($sql);
	} else {
		return $write1;
	}

    
}