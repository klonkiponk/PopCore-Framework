<?php
    require_once 'php/include.php';
    sys_createHead("Kevin Siegerth","Development Framework");
$_GET['id'] = 3;		
?>
<body>
    <header>
        <?php $breadCrumb = con_createNavigation() ?> 
    </header>

 
  
    <section id="content" role="main">

<?php


                $sql = "SELECT name,content,uid,code,php_code,code_type,date,image";
                $sql .= " FROM page_content WHERE page = {$_GET['id']} ORDER BY uid";                       
                $pagecontent = $GLOBALS['DB']->query( $sql );
              
            while ($row = $pagecontent->fetch_array()){
				//con_preFormat($row);
			  
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


    ?>
    </section>
</body>
</html>