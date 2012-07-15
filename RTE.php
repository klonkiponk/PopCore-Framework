<?php
    require_once 'php/include.php';
    sys_createHead("Kevin Siegerth","Development Framework");
$_GET['id'] = 1;		
?>
<body>
    <header>
        <?php $breadCrumb = con_createNavigation() ?> 
    </header>

 
  
    <section id="content" role="main">

<?php

$huhn = con_createRTE ('Putty','Name');

echo $huhn;


    ?>
    </section>
</body>
</html>