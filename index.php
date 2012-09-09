<?php
    require_once 'php/include.php';
    sys_createHead("Kevin Siegerth","Development Framework");       
?>
<body>
		<div class="cssSwitcher">
		<ul id="css">
			<li><a href="#" rel="css/style1.css">CSS1</a></li>
			<li><a href="#" rel="css/style2.css">CSS2</a></li>
			<li><a href="#" rel="css/style3.css">CSS3</a></li>
			<li><a href="#" rel="css/style4.css">CSS4</a></li>
			<li><a href="#" rel="css/style5.css">CSS5</a></li>
		</ul></div>
	<div class="mainWrapper">
	<header>
        <?php $breadCrumb = con_createNavigation() ?> 
    </header>
    <?php	echo con_createSubNavigation(); ?>
	<aside class="user">
            <?php con_createLogin();
				if (isset($_SESSION['loggedin'])){
					if ($_SESSION['role'] == 9) {
						echo con_createAdminAsideMenu();
					}
				}
			?>
    </aside>	
    <section class="title">

		<?php 
		
		$pageinfo = con_createPageTitle($_GET['id']);
		$title = $pageinfo['name'];
		$subtitle = $pageinfo['subtitle']
		
		?>
		<h1><?php echo $title;?></h1>
		<span class="subtitle"><?php echo $subtitle; ?></span>
		<span class='breadCrumb'>Sie sind hier: <?php echo $breadCrumb?></span>
    
    </section>
    <article id="content" role="main">
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
                echo "<section>\n\n\n";
                echo "<h2>{$row['name']}</h2>\n";
                echo "<time datetime=\"{$row['date']}\">{$row['date']}</time>\n";                

                if(!empty($row['image'])){
	                echo "<img style='width:220px;float:right;' src='./img/".$row['image']."'/>\n";
                }
                //echo stripslashes($row['content']);
                echo $row['content'];
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
					case "XML":
							$row['code_type'] = "xml";
						break;	
					case "JAVASCRIPT":
							$row['code_type'] = "javascript";
						break;
                    default:
                            $row['code_type'] = "php";
                        break;
                }
                
                  
                if(!empty($row['code'])){
					echo con_CreateSyntax($row['code'],$row['code_type']);
				}
				echo "<div class='belowArticleButtons'>";
                if (isset($_SESSION['loggedin'])){
                    if ($_SESSION['role'] == 9) { //Funktionen nur fuer Admins freischalten
                        echo "\n\n<form action=\"".$_SERVER['REQUEST_URI']."\" method=\"post\" style=\"text-align:right; display:inline; float:right;\">
                        <button type='submit' name='action' class='button edit' value='edit' >EDIT</button> 
                        <input type='hidden' name='uid' value='{$row['uid']}'/>
                        <input type='hidden' name='table' value='page_content'/>        
                        <button type='submit' name='action' class='button delete' value='delete' >DELETE</button>                       
                        </form>\n\n";
                        
                        if(isset($predecessor)){
	                        // - DEBUG - //
	                        //echo "<h2>Predecessor: ".$predecessor."</h2>"; //
	                        
	                        echo "\n\n<form style='display:inline; float:right; text-align:right; margin-right:4px;' action=\".".$_SERVER['REQUEST_URI']."\" method=\"post\">
                        <button type='submit' name='action' class='button order' value='changeOrder' >MOVE UP</button> 
                        <input type='hidden' name='thisOrder' value='{$row['contentorder']}'/>
                        <input type='hidden' name='table' value='page_content'/> 
                        <input type='hidden' name='predecessor' value='$predecessor'/>
                        </form>\n\n";
	                        
	                        
                        }
                        
                    }
                }
                echo "<a href='#globalheader' style='float:left; margin-top:15px;' class='button toTop'>TOP</a>\n</div></section>\n\n";
                $predecessor = $row['contentorder'];
            }           
				if (isset($_SESSION['loggedin'])){
					if ($_SESSION['role'] == 9) {
						echo con_createNewArticleButton();
					}
				}
				echo "<hr class='clear'>";
            }
        ?>
    </article><?php //CONTENT DIV end ?>
    <footer>
        <?php con_createFooter() ?>

    </footer>

	<?php sys_includeAdditionalScripts() //MEANT FOR jQUERY or ELSE ?>
    <?php if(!empty($message)){echo $message;}?>
	</div>
</body>
</html>