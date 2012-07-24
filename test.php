<?php
    require_once 'php/include.php';
    sys_createHead("Kevin Siegerth","Development Framework");       
?>
<body>
    <header>
        <?php $breadCrumb = con_createNavigation() ?> 
    </header>

    <section class="title">

			<?php $pageinfo = con_createPageTitle($_GET['id']);
		
			$title = $pageinfo['name'];
			$subtitle = $pageinfo['subtitle']
		
			?>
		<h1><?php echo $title;?></h1>
		<span class="subtitle"><?php echo $subtitle; ?></span>
        <aside>
            <?php con_createLogin();
				if (isset($_SESSION['loggedin'])){
					if ($_SESSION['role'] == 9) {
						echo con_createAdminAsideMenu();
					}
				}
			?>
        </aside>
    </section>
    <footer>
        <p><?php echo $breadCrumb?></p>
    </footer>
    <section id="content" role="main">
        <?php        
            if (!empty($editform)){
                echo $editform;
            } elseif (!empty($content)) {
				echo $content;
			} else {
            if (!empty($_GET)) {
                $sql = "SELECT name,content,uid,code,php_code,code_type,date,contentorder";
                $sql .= " FROM page_content WHERE page = {$_GET['id']} ORDER BY contentorder";                       
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
                //if(!empty($row['php_code'])){echo "<label><h4>CODE FOR THE EXAMPLE:</h4></label>";echo con_createSyntaxHighlight($row['php_code'],'text/x-php','php_code');}
                
                
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
                        <input type='hidden' name='uid' value='{$row['uid']}'/>
                        <input type='hidden' name='table' value='page_content'/>        
                        <button type='submit' name='action' class='button delete' value='delete' >DELETE</button>                       
                        </form>";
                        
                        if(isset($predecessor)){
	                        // - DEBUG - //
	                        //echo "<h2>Predecessor: ".$predecessor."</h2>"; //
	                        
	                        echo "<form action=\"\" method=\"post\" style=\"text-align:right;\">
                        <button type='submit' name='action' class='button edit' value='changeOrder' >MOVE UP</button> 
                        <input type='hidden' name='thisOrder' value='{$row['contentorder']}'/>
                        <input type='hidden' name='table' value='page_content'/> 
                        <input type='hidden' name='predecessor' value='$predecessor'/>
                        </form>";
	                        
	                        
                        }
                        
                    }
                }
                echo "</article>";
                $predecessor = $row['contentorder'];
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
</html>