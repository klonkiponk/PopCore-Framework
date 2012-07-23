<?php
    require_once 'php/include.php';
    sys_createHead("Kevin Siegerth","Development Framework");       
?>
<body>
    <header>
        <?php con_createNavigation() ?> 
    </header>

    <section class="title">
        <h1></h1>
        <aside>
            <?php con_createLogin() ;
				if (isset($_SESSION['loggedin'])){
					if ($_SESSION['role'] == 9) {
						echo con_createAdminAsideMenu();
					}
				}
			?>
        </aside>
    </section>
    <footer>
        <p>Breadcrumb <span class="important">:: TO BE DONE</span></p>
    </footer>
    <section id="content" role="main">
        <?php
            if (!empty($editform)){
                echo $editform;
            } else { 
	                $sql = "SELECT *";
	                $sql .= " FROM user";                       
	                $users = $GLOBALS['DB']->query( $sql );
	                $content = "";
	            while ($row = $users->fetch_array()){
	            	$content .= "<article>";
	            	$content .= "<h2>{$row['username']}</h2>";
					$content .= "<p>Rolle: {$row['role']}</p>";
	                if (isset($_SESSION['loggedin'])){
	                    if ($_SESSION['role'] == 9) { //Funktionen nur fuer Admins freischalten
	                        $content .= "<form action=\"\" method=\"post\" style=\"text-align:right;\">
	                        <button type='submit' name='action' class='button edit' value='edit' >EDIT</button> 
	                        <input class='sys' type='text' name='uid' value='{$row['uid']}'/>
	                        <input class='sys' type='text' name='table' value='user'/>        
	                        <button type='submit' name='action' class='button delete' value='delete' >DELETE</button>                       
	                        </form>";
	                    }
	                }
	                $content.= "</article>";
	            }
	            if (isset($_SESSION['loggedin'])){
	                    if ($_SESSION['role'] == 9) { //Funktionen nur fuer Admins freischalten
	                        $content .= "<article><h2>New User</h2><form action=\"\" method=\"post\">
	                        <label for=\"username\">Username:</label>
	                        <input type=\"text\" name=\"username\">
                 	        <label for=\"password\">Password:</label>
	                        <input type=\"password\" name=\"password\">
							<label for=\"role\">Role:</label>
	                        <input type=\"text\" name=\"role\"><br>
	                        <input class='sys' type='text' name='table' value='user'/>        
	                        <button type='submit' name='action' class='button' value='writeToDb' >writeToDb</button>                       
	                        </form></article>";
	                    }
	            }
            }
            if (!empty($content)) {
            	echo $content;
            }
        ?>
    </section><?php //CONTENT DIV end ?>
    <footer>
        <?php con_createFooter() ?>
    </footer>
    <?php sys_includeAdditionalScripts() //MEANT FOR jQUERY or ELSE ?>
    <?php if(!empty($message)){echo $message;}?>
</body>
</html>