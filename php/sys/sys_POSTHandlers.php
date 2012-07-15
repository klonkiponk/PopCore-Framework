<?php
//      -- DEBUG --      //
// echo "DEBUG FROM sys_POSTHandlers.php";con_preFormat($_POST);con_preFormat($_FILES);#con_preFormat($_SERVER);    //
if (!empty($_POST['action'])){
    switch ($_POST['action']) {
        case "Login":
            $message = sys_performLogin($_POST['username'],$_POST['passwort']);
            break;
        case "Logout":
            $message = sys_performLogout();
            break;
        case "showMessage":
            $message = con_createMessage($_POST['showMessage'],$_POST['showMessageColor']);
            break;
        case "delete":
            $message = db_deleteFromDb($_POST);
            break;
		case "deletePage":
            $message = db_deletePageFromDb($_POST);
            break;
        case "edit":
            $editform = sys_createEditFormForPageContent($_POST);
            break;
        case "update":
            $message = db_updateDb ($_POST);
            break;
        case "writeToDb":
            $message = db_writeToDb ($_POST);
            break;
        case "newArticle":
            $content = db_createNewArticleForm();
            break;
    }
}                                                     