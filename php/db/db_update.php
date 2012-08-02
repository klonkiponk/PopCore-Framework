<?php
/**
 * creates a Query from POST and sends it to perform function
 *
 * @author Kevin Siegerth
 */
function db_updateDb ()
{
	
	
	if(isset($_FILES['image']) AND ! $_FILES['image']['error'] AND ($_FILES['image']['size'] < 300000)) {
		$bildinfo = getimagesize($_FILES['image']['tmp_name']);
	    if ($bildinfo === false) {
	    	die ("kein Bild");
	    } else {
	    	$mime = $bildinfo['mime'];
	        $mimetypen = array(
	            "image/jpeg" => "jpg",
	            "image/gif" => "gif",
	            "image/png" => "png"
	            );
	        if (!isset($mimetypen[$mime])) {
	        	die ("nicht das richtige Format");
	        } else {
	        	$endung = $mimetypen[$mime];
	        }
	        $neuername = basename($_FILES['image']['name']);
	        $neuername = preg_replace("/\.(jpe?g|gif|png)$/i", "", $neuername);
	        $neuername = preg_replace("/[^a-zA-Z0-9_-]/", "", $neuername);
	        $neuername .= ".$endung";
	        
	        $ziel = "img/$neuername";
	        while (file_exists($ziel)) {
		        $neuername = "kopie_$neuername";
	    	    $ziel = "img/$neuername";
	        }
			
	        if (move_uploaded_file($_FILES['image']['tmp_name'], $ziel)) {
	        	echo "Erfolgreich";
	        	$fileupload = true;
	        } else {
	        	echo "Fehler";
	        	$fileupload = false;
	        }
	    }
    }
    
       
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
	        	//$value = html_entity_decode($value);
	        	break;
			case "content":
				//$value = $GLOBALS['DB']->real_escape_string($value);
//				$value = preg_replace("/'/", "''", $value);
				$value = mysql_real_escape_string ($value);
				
//				$value = addslashes($value);
				break;
			case "code":
//				$value = addslashes($value);
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