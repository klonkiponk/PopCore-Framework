<?php

	function write_to_file($user,$inhalt) {
		$datum  = date("d.m.Y H:i");
		$user   = 	htmlentities($user);
		$inhalt = htmlentities($inhalt);
		$inhalt = 	str_replace("\n", "<br>", $inhalt);
		$eintrag="$user|$datum|$inhalt";
		$datei  = fopen("gaestebuch/gaestebuch.txt", "a");
		fwrite($datei, "\n".$eintrag);
		fclose($datei);
	}
	
	function get_entries(){
		include("gaestebuch/admins.php");
		$forum = file("gaestebuch/gaestebuch.txt");
		krsort($forum);
		foreach($forum as $entry){
		$zerlegen = explode("|", $entry);
		echo '
			<article id="" class="comment">
			<footer class="comment-meta">
			<div class="comment-author">
			<div class="comment-thumb"><img alt="" src="images/hundi.jpg" class="avatar avatar-60 photo" height="60" width="60" /></div>';
		
		echo '<span class="comment-authorname">';
		if (in_array($zerlegen[0], $admins)){
		echo "<div class='admin'></div>$zerlegen[0] </span><time pubdate datetime='$zerlegen[1]'>$zerlegen[1]</time>";
		}else{
		echo "$zerlegen[0] </span><time pubdate datetime='$zerlegen[1]'>$zerlegen[1]</time>";
		}
		
		echo '</div></footer>';
		echo "<div class='comment-content'><p>
		$zerlegen[2]</p>
		</div>
		</article>
		";
		}
	}
	function create_entry_form($user,$inhalt) {
		echo '<section class="forum"><article class="forum">';
		echo "<form action='' method='POST'>
				<label for='Name'>Name:</label>";
		echo'<input name="Name" type="text" size="40" maxlength="35" value="'.$user.'">';
		echo '<label for="inhalt">Beitrag:</label>';
		echo'<textarea name="inhalt" cols="40" rows="4">'.$inhalt.'</textarea>
					<br>
			<input name="SUBMIT" class="button" type="submit" value="absenden">
		</form></article></section>';
	}
?>