<?php 
	require_once 'php/include.php';
	sys_createHead("Kevin Siegerth","Development Framework"); 
?>

<style type="text/css"><!--

html {
	/*background: url('img/bg_main.jpg');*/
	background: url('img/chalkboard.jpg');

}
section.development {
	display:block;
	width:800px;
	margin:20px auto 0px auto;
}
section.development form {
	margin:0px;
	padding:0px;
}
section.development form input[type=text]{
	width:782px;
	background:rgba(0,0,0,0.2);
	color:#ddd;
	border-radius:5px;
	padding:8px;
	border:1px solid #333;
	margin:0px;
	box-shadow:inset #777 0px -1px 0px, inset 0px 1px 0px #000;
}
section.development form input[type=text]:focus{
	outline: 0px;;

}
section.development li {
	border:1px solid #777;
	background: #ffffff; /* Old browsers */
background: -moz-linear-gradient(top,  #ffffff 0%, #f3f3f3 50%, #ededed 51%, #ffffff 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(50%,#f3f3f3), color-stop(51%,#ededed), color-stop(100%,#ffffff)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* IE10+ */
background: linear-gradient(to bottom,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ffffff',GradientType=0 ); /* IE6-9 */

	margin: 10px 0px;
	padding:7px;
	border-radius:5px;
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
	font-weight:bold;
	text-transform:none;
}

--></style>

<body>

<?php //con_preFormat($_POST);?>
<h1 class="development">Notes for the Framework</h1>
<section class="development">

<form action".<?php echo $_SERVER['REQUEST_URI']?>" method="post">
	<input type="text" name="name" placeholder="Insert New Note"/>
		<input type="submit" formnovalidate style="display:none" class="button" name="action" value="writeToDb"/>
		<input type="hidden" class="sys" name="table" value="development"/>
</form>
</section>
<section class="development">

<?php 
$sqlNotes = "SELECT name,nid,content FROM development ORDER BY nid";
$notes = $GLOBALS['DB']->query($sqlNotes);
$return = "<ul>";
while ($note = $notes->fetch_array()){
	$return .= "<li>".$note['name']."</li>";
}
$return .= "</ul>";

echo $return;
?>

</section>


</body>
</html>