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
          <section> <?php con_ListFolder('php');?>  </section>  </article><?php //CONTENT DIV end ?>
    <footer>
        <?php con_createFooter() ?>

    </footer>

	<?php sys_includeAdditionalScripts() //MEANT FOR jQUERY or ELSE ?>
    <?php if(!empty($message)){echo $message;}?>
	</div>
</body>
</html>