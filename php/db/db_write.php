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
                //$value = $GLOBALS['DB']->real_escape_string($value);
				$sql .= "'";
				$sql .= preg_replace("/'/", "''", $value);
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
    return db_performWritingQuery ($sql);
}