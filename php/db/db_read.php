<?php
function db_readFromDB ()
{
    $sql = "SELECT permission,type FROM pages WHERE pid = {$_GET['id']}";
    $result = db_performReadingQuery($sql);    
    return $result;
}
function db_performReadingQuery ($sql)
{
    $return = $GLOBALS['DB']->query($sql);
    return $return;
}
function sys_securePage ()
{
    $result = db_readFromDB ();
    $return = $result->fetch_array(); 
    $redirect = '<meta http-equiv="refresh" content="0; url=./index.php?id=404&action=showMessage&showMessageColor=red&showMessage=';
    
    if ($return['permission'] != 0) {
        if (!$_SESSION){
            $redirect .= 'You%20have%20to%20login%20to%20see%20the%20page%20you%20requested">';
            echo $redirect;
            exit;
        } elseif($_SESSION['role'] < $return['permission']) {
            $redirect .= 'You%20do%20not%20have%20enough%20rights%20to%20see%20the%20requested%20page">';
            echo $redirect;
            exit;
        }
    }
    
}
function db_getPageType ()
{
    $result = db_readFromDB ();
    $return = $result->fetch_array();
    define ('PAGETYPE',$return['type']);
}