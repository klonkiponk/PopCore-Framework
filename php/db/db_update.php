<?php
/**
 * creates a Query from POST and sends it to perform function
 *
 * @author Kevin Siegerth
 */
function db_updateDb ()
{
    //define Variables
    $sql = "";
    $table = $_POST['table'];
    $uid = $_POST['uid'];
    
    //Cleanup $_POST from Sys Values
    $entries = sys_unsetSysVariables ($_POST);

    if(isset($fileupload) AND ($fileupload == true)) {
	    $entries['image'] = $_FILES['image']['name'];
    }
    //create SQL Query
    $sql .= "UPDATE $table SET ";
    
    foreach ($entries as $key=>$value) {
        
        switch ($key) {
	        case "name":
	        	break;
			case "content":
				$value = mysql_real_escape_string($value);
				break;
			case "code":
				$value = preg_replace("/'/", "''", $value);
				break;
        }
        $sql .= "$key='$value',";
    }
    //cut off commata
    $sql = rtrim($sql,',');
    
    //End of QUERY
    $sql .= " WHERE uid = $uid";
    
    //     -- DEBUG --     //
    //con_preFormat($sql); //
    
    //Perform Query
	
    return db_performWritingQuery ($sql);
}