<?php
function con_createLogin()
{  
    echo "<section class='login_form'>";
    $sys_referring_page = $_SERVER['PHP_SELF'];    
    if (!isset($_SESSION['loggedin'])){
        echo "
        <form action='".$_SERVER['REQUEST_URI']."' method='post'>
            <label for='username'>Username:</label><input type='text' id='username' name='username' value='' placeholder='Username' /><br />
            <label for='password'>Passwort:</label><input type='password' id='password' name='passwort' placeholder='Passwort' /><br />
            <input type='submit' name='action' class='button' value='Login' />
            <input class='sys' type='text' name='sys_referring_page' value='$sys_referring_page'/>
        </form>
        ";
    } else {
        $username = $_SESSION['username'];
        echo "
            <label>Sie sind angemeldet als:</label>$username<br/>
            <form action='".$_SERVER['REQUEST_URI']."' method='post'>
                <input type='submit' name='action' class='button delete' value='Logout' />
                <input class='sys' type='text' name='sys_referring_page' value='$sys_referring_page'/>
            </form>
        ";
    }
    echo "</section>"; 
}