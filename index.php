<?php
    require_once 'php/include.php';
    sys_createHead("Kevin Siegerth","Development Framework");       
?>
<body>
    <header>
        <?php $breadCrumb = con_createNavigation() ?> 
    </header>

    <section class="title">
        <h1><?php echo con_createPageTitle($_GET['id']) ?></h1>
        <aside>
            <?php con_createLogin() ?>
        </aside>
    </section>
    <footer>
        <p><?php echo $breadCrumb?> <span class="important"></span></p>
    </footer>
    <section id="content" role="main">
        <?php        
            if (!empty($editform)){
                echo $editform;
            } elseif (!empty($content)) {
				echo $content;
			} else {
            if (!empty($_GET)) {
                $sql = "SELECT name,content,uid,code,php_code,code_type,date,image";
                $sql .= " FROM page_content WHERE page = {$_GET['id']} ORDER BY uid";                       
                $pagecontent = $GLOBALS['DB']->query( $sql );
            }   
            while ($row = $pagecontent->fetch_array()){
                //foreach($row as $key=>$value){
                //    $value = html_entity_decode($value);
                //}
                echo "<article>";
                echo "<h2>{$row['name']}</h2>";
                echo "<time datetime=\"{$row['date']}\">{$row['date']}</time>";                

                if(!empty($row['image'])){
	                echo "<img style='width:220px;float:right;' src='./img/".$row['image']."'/>";
                }
                echo "{$row['content']}";

                if(!empty($row['php_code'])){echo "<label><h4>EXAMPLE:</h4></label>";}
                eval($row['php_code']);
                if(!empty($row['php_code'])){echo "<label><h4>CODE FOR THE EXAMPLE:</h4></label>";echo con_createSyntaxHighlight($row['php_code'],'text/x-php','php_code');}
                
                
                switch($row['code_type']) {
                    case "CSS":
                            $row['code_type'] = "css";
                        break;
					case "HTML":
							$row['code_type'] = "html5";
						break;
					case "APACHE":
							$row['code_type'] = "apache";
						break;
					case "PERL":
							$row['code_type'] = "perl";
						break;
                    default:
                            $row['code_type'] = "php";
                        break;
                }
                
                  
                if(!empty($row['code'])){
					echo con_CreateSyntax($row['code'],$row['code_type']);
				}
                if (isset($_SESSION['loggedin'])){
                    if ($_SESSION['role'] == 9) { //Funktionen nur fuer Admins freischalten
                        echo "<form action=\"\" method=\"post\" style=\"text-align:right;\">
                        <button type='submit' name='action' class='button edit' value='edit' >EDIT</button> 
                        <input class='sys' type='text' name='uid' value='{$row['uid']}'/>
                        <input class='sys' type='text' name='table' value='page_content'/>        
                        <button type='submit' name='action' class='button delete' value='delete' >DELETE</button>                       
                        </form>";
                    }
                }
                echo "</article>";
            }
            
            
            
            
            
            
            if (isset($_SESSION['loggedin'])){
                if ($_SESSION['role'] == 9) {
                    echo con_createNewArticleButton();

            }
            }
            }
        ?>
    </section><?php //CONTENT DIV end ?>
    <footer>
        <?php con_createFooter() ?>

    </footer>

	
	
	<?php sys_includeAdditionalScripts() //MEANT FOR jQUERY or ELSE ?>
    <?php if(!empty($message)){echo $message;}?>
</body>
</html></body>
</html>