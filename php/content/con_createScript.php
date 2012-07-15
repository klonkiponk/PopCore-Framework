<?php

function con_createScript ()
{
	$script = con_createScriptHead();	
	$script .= con_createScriptCss();
	$script .= con_createScriptHeader();
	$script .= con_createScriptNavigation();
	$script .= con_createScriptContents();
	$script .= con_createScriptEnd();
	$return = con_createPDF($script);
	return ($return);
}

function con_createScriptEnd()
{
	$return = "</body></html>";
	return $return;
}

function con_createPDF($return)
{
	$file = fopen('./script.html','w');
	fwrite ($file, $return);
	
	$prince = new Prince('/usr/local/bin/prince');
	// -- DEBUG -- //
	$prince->setLog('./prince.log');
	$file = "./script.html";
	if ($prince->convert_file($file) == true) {
		$return = con_createMessage('Script erfolgreich erstellt','green');
		$return .= "<a style='display:block; color:white' href='./script.pdf' target='_blank'><div class='blackbox'>SCRIPT</div></a>";
	} else {
		$return = con_createMessage('Fehler bei der Erstellung des Scripts','red');
	}
	return $return;	   
}

function con_createScriptHead()
{
	$return = "<!doctype html>
<html>
<head>
	<title>ItSysAdminFwWebSK</title>
	<meta charset='utf-8'>
</head>
<body>";
	return $return;
}

function con_createScriptHeader() 
{
	$return = "
	
	<div class='titlepage'>
		<h1>Webdesign</h1>
		F&uuml;UstgSBw<br>
		First Edition
		<p>HF Weidinger<br/>L Siegerth</p>
	</div>
	
	";
	return $return;
}

function con_createScriptNavigation () 
{
	//DEFINITIONS
	
	#SQL for the Topics
	$sql = "SELECT pid,name FROM pages WHERE sub = 0 and deleted = 0 and permission = 0 AND type < 300";
	
	//DEFINITIONS END
	$return = '';		 
	$result = $GLOBALS['DB']->query($sql);
	
	// -- DEBUG -- //
	//con_preFormat($result);
	
	$return .= "<div class='toc' id='toc'><h1>Inhaltsverzeichnis</h1>\n";
	$return .= "			<ul class='toc'>\n";			
	
	while ($chapter = $result->fetch_array()) {							//CHAPTER
		$return .= "<li class='chapter'><a href='#chapter-{$chapter['pid']}' class='PageRef'>{$chapter['name']}</a></li>\n";
		
		$pid = $chapter['pid'];
		$sql2 = "SELECT name,pid FROM pages WHERE sub=$pid AND type < 300";
		$sections = $GLOBALS['DB']->query($sql2);
		if (!empty($sections)) {
			$return .= "	<ul>\n";									//SECTIONS
			while ($section = $sections->fetch_array()) { 
				$return .=	"<li class='section'><a href='#section-{$section['pid']}' class='PageRef'>{$section['name']}</a></li>\n";							 							
				
				
																		//SUBSECTIONS
				
				
				$sqlSubSections = "SELECT name,pid FROM pages WHERE sub={$section['pid']} AND type < 300";
				$subSections = $GLOBALS['DB']->query($sqlSubSections);			  
				if(!empty($subSections)) {
					$return .= "<ul>\n";
					while ($subSection = $subSections->fetch_array()) {
						$return .= "<li class='subSection'><a href='#subSection-{$subSection['pid']}' class='PageRef'>{$subSection['name']}</a></li>\n";
					}
					$return .= "</ul>";
				}//SUBSECTIONS
			}			 
			$return .= "	</ul>\n";
		}//SECTIONS

	}
	$return .= "</ul>\n</div>\n";
	//CHAPTERS

	
	
	return $return;	  
}

function con_createScriptContents ()
{
	//DEFINITIONS
	
	#SQL for the Topics
	$sql = "SELECT pid,name,subtitle FROM pages WHERE sub = 0 and deleted = 0 and permission = 0 and type < 300";
	
	//DEFINITIONS END
	$return = "";
		
	$mysqli = db_connectToDb();
	$result = $GLOBALS['DB']->query($sql);
	
	// -- DEBUG -- //
	//con_preFormat($result);
	
	$return .= "";																						
	
/**************************************\
			  CHAPTERS
\**************************************/
	while ($chapter = $result->fetch_array()) {
		$return .= "\n\n\n\n\n<section class='chapter' id='chapter-{$chapter['pid']}'><h1>{$chapter['name']}</h1>\n";
		//$return .= "<p class='sidenote'>{$topic['subtitle']}</p>"; //NOT YET IMPLEMENTED
		$pid = $chapter['pid'];
		
		

		
																		//PRINT CONTENT FROM THE CHAPTER PAGE
		$sqlChapterArticles = "SELECT * FROM page_content WHERE page=$pid";
		$chapterArticles = $GLOBALS['DB']->query($sqlChapterArticles);

		while ($chapterArticle = $chapterArticles->fetch_array()){
			$return .= con_createArticle($chapterArticle);
		}
		
/**************************************\
			  SECTIONS
\**************************************/
		$sql2 = "SELECT pid,name FROM pages WHERE sub=$pid";
		$sections = $GLOBALS['DB']->query($sql2);
		if (!empty($sections)) {
			$return .= "";																				
			while ($section = $sections->fetch_array()) { 
				$return .= "\n\n\n\n<section class='section' id='section-{$section['pid']}'><h2>{$section['name']}</h2>\n";
					
																										//ARTICLES
					$return .= "";
					$sql3 = "SELECT * FROM page_content WHERE page = {$section['pid']}";
					$articles = $GLOBALS['DB']->query($sql3);
					// -- DEBUG -- //
					//con_preFormat($content);
					while ($article = $articles->fetch_array()) {
						 $return .= con_createArticle($article);
					}
/**************************************\
			  SUBSECTIONS
\**************************************/					
					$sqlSubSections = "SELECT * FROM pages WHERE sub={$section['pid']}";
					$subSections = $GLOBALS['DB']->query($sqlSubSections);
					if ($subSections->num_rows != 0) {	
						while ($subSection = $subSections->fetch_array()) {
							$return .= "\n\n\n<section class='subSection' id='subSection-{$subSection['pid']}'><h3>{$subSection['name']}</h3>\n";
							
							
							$sqlSubSectionArticles = "SELECT * FROM page_content WHERE page = {$subSection['pid']}";
							$articles = $GLOBALS['DB']->query($sqlSubSectionArticles);
							while ($article = $articles->fetch_array()) {
								$return .= con_createArticle($article);
							}
							
							
						}
						$return .= "</section>";
					}	// END OF SUBSECTION
				$return .= "</section>";	//END OF SECTION
			}
			$return .= "</section>"; //END OF CHAPTER
		}
	}
	$return .= "";
	
	return $return;
}

function con_createScriptCss()
{
	$return = '<style type="text/css" media="print,screen"><!--';
	$return .= file_get_contents('./css/print.css');
	$return .= '--></style>';
	return $return;
}

function con_createArticle($article)
{
	$article['content'] = con_replaceUmlaute($article['content']);
	$article['name'] = con_replaceUmlaute($article['name']);
	$return = "
		<article id='article-{$article['uid']}'>
			<h4>{$article['name']}</h4>\n
			{$article['content']}";
	
	if (!empty($article['code'])){

		switch ($article['code_type']) {
		 	case "HTML":
		 		$article['code_type'] = "html5";
		 		break;
		 	case "CSS":
		 		$article['code_type'] = "css";
		 		break;
		 	case "PHP":
		 		$article['code_type'] = "php";
		 		break;
		 	case "APACHE":
		 		$article['code_type'] = "apache";
		 		break;	
		}

		$article['code'] = con_CreateSyntax($article['content'],$article['code_type']);
				
		$return .= "
				<caption>Listing</caption>
				{$article['code']}
			</article>
		";
	}
	return $return;								 
}

function con_replaceUmlaute ($content) {
	$umlaute = array ("/ä/","/ö/","/ü/","/Ä/","/Ö/","/Ü/","/ß/") ;
	$replaceumlaute = array ("&auml;","&ouml;","&uuml;","&Auml;","&Ouml;","&Uuml;","&szlig;") ;
	$content = preg_replace($umlaute , $replaceumlaute , $content);
	return $content;
}