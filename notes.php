<?php 
	require_once 'php/include.php';
	sys_createHead("Kevin Siegerth","Development Framework"); 
?>
		
<style type="text/css"><!--

html {
	background: url('img/chalkboard.jpg') fixed;

}
#message {
	background:white;
}
section.development {
	display:block;
	width:800px;
	margin:20px auto 0px auto;
}
section.development form.newNote {
	margin:0px;
	padding:0px;
	height:70px;
	overflow:hidden;
}
section.development form input[type=text]{
	width:782px;
	background:rgba(0,0,0,0.2);
	color:#ddd;
	border-radius:5px;
	padding:8px;
	border:1px solid #333;
	margin:0px;
	box-shadow:inset #777 0px -1px 0px, inset 0px 1px 4px #000;
	margin-bottom: 5px;
}
section.development form input[type=text]:focus{
	outline: 0px;;
}
section.development form.doneCheck {
	width:20px;
	height:20px;
	/*overflow:hidden;*/
	display:inline;
	float:right;
	box-sizing:border-box;
	padding:0px;
	margin-right: 5px;
}
section.development button {
	border:1px solid #aaa;
	height:20px;
	width:20px;
	border-radius:5px;
	margin-top:5px;
	background:#fff;
	box-shadow:inset 0px 0px 5px #999;
}

section.development div.noteWrapper {
	border:1px solid #777;
	background: #ffffff; /* Old browsers */
	background: -moz-linear-gradient(top,  #ffffff 0%, #f3f3f3 50%, #ededed 51%, #ffffff 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(50%,#f3f3f3), color-stop(51%,#ededed), color-stop(100%,#ffffff)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* IE10+ */
	background: linear-gradient(to bottom,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ffffff',GradientType=0 ); /* IE6-9 */
	margin:10px 0px;
	border-radius:5px;
	height:30px;
}
section.development li {
	display:inline-block;
	width:760px;
	line-height: 30px;
	padding-left:5px;
	height:30px;
	overflow: hidden;
}

section.development ul.done div.noteWrapper {
	background:rgba(255,255,255,0.5);
}
h1.development {
	display:block;
	width:800px;
	margin:10px auto;
	color:white;
	text-shadow:1px 1px 2px #333;
	box-shadow:0px 0px 0px;
	font-size:1.2em;
	text-align:center;
	padding:0px;
	font-weight:bold;s
	text-transform:none;
}
h1.developmentDone {
	display:block;
	width:800px;
	margin:10px auto;
	color:rgba(255,255,255,0.5);
	text-shadow:1px 1px 2px #333;
	box-shadow:0px 0px 0px;
	font-size:1.2em;
	text-align:center;
	padding:0px;
	font-weight:bold;s
	text-transform:none;	
}

--></style>

<body>

<?php //con_preFormat($_POST);?>
<h1 class="development">Notes for the Framework</h1>
<section class="development">

<form action".<?php echo $_SERVER['REQUEST_URI']?>" method="post" class='newNote'>
	<input type="text" name="name" placeholder="Insert New Note"/>
	<input type="text" name="category" placeholder="Category" />
	<input type="submit" formnovalidate style="visibility:hidden" class="button" name="action" value="writeToDb"/>
	<input type="hidden" class="sys" name="table" value="development"/>
</form>
</section>
<section class="development">

<?php 

if (!empty($_POST)){
	if ($_POST['action'] == 'check') {
		$sqlCheckNote = "UPDATE development SET done=1 WHERE nid={$_POST['nid']}"; 
		$GLOBALS['DB']->query($sqlCheckNote);
	}
}
$sqlCategories = "SELECT category FROM development WHERE done=0 GROUP BY category ORDER BY category";
$categories = $GLOBALS['DB']->query($sqlCategories);
$return = "";
while ($category= $categories->fetch_array()) {
	$return .= "<h1 class='development'>{$category[0]}</h1><ul>";
	$sqlNotes = "SELECT name,nid FROM development WHERE done=0 AND category='".$category['0']."' ORDER BY nid";
	$notes = $GLOBALS['DB']->query($sqlNotes);
	while ($note = $notes->fetch_array()){
		$return .= "<div class='noteWrapper'><li>".$note['name']."</li>";
		$return .= "<form class='doneCheck' id='doneCheck' action='' method='post'>
		
						<input type='hidden' name='nid' value='{$note['nid']}' />
						<button type='submit' name='action' value='check'></button>					
					</form>
				</div>";
		}//END OF WHILE
	$return .= "</ul>";
}


$return .= "<br><br><br><br><br>";
$sqlCategories = "SELECT category FROM development WHERE done=1 GROUP BY category ORDER BY category";
$categories = $GLOBALS['DB']->query($sqlCategories);



while ($category= $categories->fetch_array()) {
	$return .= "<h1 class='developmentDone'>{$category[0]}</h1>";
	$return .= "<ul class='done'>";
	$sqlNotes = "SELECT name,nid FROM development WHERE done=1 AND category='".$category['0']."' ORDER BY nid";
	$notes = $GLOBALS['DB']->query($sqlNotes);
	while ($note = $notes->fetch_array()){
		$return .= "<div class='noteWrapper'><li><del>".$note['name']."</del></li></div>";
		}//END OF WHILE
	$return .= "</ul>";
}




echo $return;
?>

</section>


</body>
</html>