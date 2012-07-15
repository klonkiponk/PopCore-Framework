<?php
/**
 * Delete a whole Entry from a Table
 *
 * @author Kevin Siegerth
 */
function db_deleteFromDB ()
{
    $table = $_POST['table'];
    $uid = $_POST['uid'];
    $sql = "DELETE FROM $table WHERE uid = $uid";

    //     -- DEBUG --     //
    //con_preFormat($sql); //
    
    //Perform Query
    return db_performWritingQuery ($sql);     
}
function db_deletePageFromDB ()
{
    $table = $_POST['table'];
    $pid = $_POST['pid'];
    $sql = "DELETE FROM $table WHERE pid = $pid";

    //     -- DEBUG --     //
    //con_preFormat($sql); //
    
    //Perform Query
    return db_performWritingQuery ($sql);     
}