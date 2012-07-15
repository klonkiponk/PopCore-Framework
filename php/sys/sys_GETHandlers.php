<?php
//      -- DEBUG --      //
// echo "DEBUG FROM sys_GETHandlers.php";con_preFormat($_GET);    //
if (!empty($_GET['action'])) {
    switch ($_GET['action']) {
        case "showMessage":
            $message = con_createMessage($_GET['showMessage'],$_GET['showMessageColor']);
            break;
    }
}