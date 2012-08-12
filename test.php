<?php
    require_once 'php/include.php';
    sys_createHead("Kevin Siegerth","Development Framework");       
?>


<body>
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
          <section> <?php con_ListFolder('img');?>  </section>  </article><?php //CONTENT DIV end ?>
    <footer>
        <?php con_createFooter() ?>

    </footer>

	<?php sys_includeAdditionalScripts() //MEANT FOR jQUERY or ELSE ?>
    <?php if(!empty($message)){echo $message;}?>
	</div>
</body>
</html>