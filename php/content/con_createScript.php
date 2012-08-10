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


/**
 * con_createScriptEnd function.
 * 
 * Function generates the end of the generated .html file
 * @access public
 * @return void
 */
function con_createScriptEnd()
{
	$return = "</body></html>";
	return $return;
}


/**
 * con_createPDF function.
 * 
 * Function first generates a simple .html File from the returns of the previous function.
 * Afterwards this html file is parsed via the external Software princexml to generate a .pdf
 *
 * If princexml suceeds in creating the PDF, PHP Page with 2 single links is shown: One to the .pdf and the other to the first generate .html for debugging purposes
 *
 * @access public
 * @param mixed $return
 * @return void
 */
function con_createPDF($return)
{
	$file = fopen('./script.html','w');
	fwrite ($file, $return);
	
	$prince = new Prince('/usr/local/bin/prince');
	
	// -- DEBUG -- //
	//$prince->setLog('./prince.log');
	
	$file = "./script.html";
	if ($prince->convert_file($file) == true) {
		$return = con_createMessage('Script erfolgreich erstellt','green');
		$return .= "<a style='display:block; color:white' href='./script.pdf' target='_blank'><div class='blackbox'>PDF</div></a>";
		$return .= "<a style='display:block; color:white' href='./script.html' target='_blank'><div class='blackbox'>HTML (Debug)</div></a>";
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
		<img style='margin-top:250px; margin-bottom:100px' class='floatCenter' src='./img/20120802_20120802_20120802_SysAdminFw_Logo.png' alt='' class='floatCenter'/>
		<strong style='font-size:2em;'>F&uuml;UstgSBw</strong><br><br>
		Lehrgruppe C / IX. Inspektion
		<br/><br>
		<strong>DRAFT</stong>
		<p>HF Weidinger<br/>L Siegerth</p>
	</div>
	
	";
	return $return;
}

function con_createScriptNavigation () 
{
	//DEFINITIONS
	
	#SQL for the Topics
	$sql = "SELECT pid,name FROM pages WHERE sub = 0 and deleted = 0 and permission = 0 AND type < 300 ORDER BY pageorder";
	
	//DEFINITIONS END
	$return = '';		 
	$result = $GLOBALS['DB']->query($sql);
	
	// -- DEBUG -- //
	//con_preFormat($result);
	
	$return .= "<div class='toc' id='toc'><h1>Inhaltsverzeichnis</h1>\n";
	$return .= "			<ul class='toc'>\n";			
	
	while ($chapter = $result->fetch_array()) {							//CHAPTER
		
		$chapter['name'] = con_replaceUmlaute($chapter['name']);

		
		$return .= "<li class='chapter'><a href='#chapter-{$chapter['pid']}' class='PageRef'>{$chapter['name']}</a></li>\n";
		
		$pid = $chapter['pid'];
		$sql2 = "SELECT name,pid FROM pages WHERE sub=$pid AND type < 300 ORDER BY pageorder";
		$sections = $GLOBALS['DB']->query($sql2);
		if (!empty($sections)) {
			$return .= "	<ul>\n";									//SECTIONS
			while ($section = $sections->fetch_array()) { 
				$section['name'] = con_replaceUmlaute($section['name']);
				$return .=	"<li class='section'><a href='#section-{$section['pid']}' class='PageRef'>{$section['name']}</a></li>\n";							 							
				
				
																		//SUBSECTIONS
				
				
				$sqlSubSections = "SELECT name,pid FROM pages WHERE sub={$section['pid']} AND type < 300 ORDER BY pageorder";
				$subSections = $GLOBALS['DB']->query($sqlSubSections);			  
				if(!empty($subSections)) {
					$return .= "<ul>\n";
					while ($subSection = $subSections->fetch_array()) {
						$subSection['name'] = con_replaceUmlaute($subSection['name']);
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
	$sql = "SELECT pid,name,subtitle FROM pages WHERE sub = 0 and deleted = 0 and permission = 0 and type < 300 ORDER BY pageorder";
	
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
		$chapter['name'] = con_replaceUmlaute($chapter['name']);
		$return .= "\n\n\n\n\n<section class='chapter' id='chapter-{$chapter['pid']}'><h1>{$chapter['name']}</h1>\n";
		//$return .= "<p class='sidenote'>{$topic['subtitle']}</p>"; //NOT YET IMPLEMENTED
		$pid = $chapter['pid'];
		
		

		
		//PRINT CONTENT CHAPTER PAGES
		$sqlChapterArticles = "SELECT * FROM page_content WHERE page=$pid ORDER BY contentorder";
		$chapterArticles = $GLOBALS['DB']->query($sqlChapterArticles);

		while ($chapterArticle = $chapterArticles->fetch_array()){
			$return .= con_createArticle($chapterArticle);
		}
		
/**************************************\
			  SECTIONS
\**************************************/
		$sql2 = "SELECT pid,name FROM pages WHERE sub=$pid ORDER BY pageorder";
		$sections = $GLOBALS['DB']->query($sql2);
		if (!empty($sections)) {
			$return .= "";																				
			while ($section = $sections->fetch_array()) { 
				$return .= "\n\n\n\n<section class='section' id='section-{$section['pid']}'><h2>{$section['name']}</h2>\n";
					
					//PRINT SECTION ARTICLES
					$return .= "";
					$sqlSectionArticles = "SELECT * FROM page_content WHERE page = {$section['pid']} ORDER BY contentorder";
					$sectionArticles = $GLOBALS['DB']->query($sqlSectionArticles);
					
					// -- DEBUG -- //
					//con_preFormat($content);
					
					while ($sectionArticle = $sectionArticles->fetch_array()) {
						 $return .= con_createArticle($sectionArticle);
					}
/**************************************\
			  SUBSECTIONS
\**************************************/					
					$sqlSubSections = "SELECT * FROM pages WHERE sub={$section['pid']} ORDER BY pageorder";
					$subSections = $GLOBALS['DB']->query($sqlSubSections);
					if ($subSections->num_rows != 0) {	
						while ($subSection = $subSections->fetch_array()) {
						$subSection['name'] = con_replaceUmlaute($subSection['name']);
							$return .= "\n\n\n<section class='subSection' id='subSection-{$subSection['pid']}'><h3>{$subSection['name']}</h3>\n";
							
							//PRINT SUBSECTION ARTICLES
							$sqlSubSectionArticles = "SELECT * FROM page_content WHERE page = {$subSection['pid']} ORDER BY contentorder";
							$articles = $GLOBALS['DB']->query($sqlSubSectionArticles);
							while ($article = $articles->fetch_array()) {
								$return .= con_createArticle($article);
							}
							
							
						}
						$return .= "</section>";
					}	// END OF SUBSECTION
				$return .= "</section>";
				//END OF SECTION
			}
			$return .= "</section>";
			//END OF CHAPTER
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

/**************************************\
			  ARTICLES
\**************************************/	

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
		 	case "XML":
		 		$article['code_type'] = "xml";
		 		break;
		 	case "JAVASCRIPT":
		 		$article['code_type'] = "javascript";
		 		break;	
		 	case "PERL":
		 		$article['code_type'] = "perl";
		 		break;		
		}

		$article['code'] = con_CreateSyntax($article['code'],$article['code_type']);
		$article['code'] = con_replaceUmlaute($article['code']);		
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