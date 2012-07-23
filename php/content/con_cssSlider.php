<?php
	function con_createCssSlider($number = 1,$path = ".", $width = 500){
        $dir = scandir($path);
        $files['count'] = 0;
        $i = 0;
        foreach ($dir as $entry) {
            if(strpos($entry,'.') !== (int) 0){ 
                    $files[$i] = $entry;
                    $files['count']++;
                    $i++;
            }
        }
        //DEBUG preFormat($files);
        
        //im Array $files sind nun alle Dateien und die Anzahl vorhanden
        $count = $files['count'];
      unset($files['count']);
      
      //DEBUG
      //echo $count;
      //DEBUG preFormat($files);  
      echo "<div class='cssSlider cssSlider-$number'>\n";
      echo "<input type='radio' name='controls-$number' checked id='control1-$number' />\n";
      $i = 2;
      while ($i <= $count) {
        echo "<input type='radio' name='controls-$number' id='control$i-$number' />\n";
        $i++;
      }
      echo "<div class='slides-$number'>\n";
      echo "<div class='center center-$number'>\n";

      $i = 1;
      while ($i <= $count) {
      echo "<label for='control$i-$number'></label>\n";
      $i++;
    }
    echo "</div>\n";
    echo "<div class='left'>\n";

      $i = 1;
      while ($i <= $count) {
      echo "<label for='control$i-$number'></label>\n";
      $i++;
    }
    echo "</div>\n";
    echo "<div class='right'>\n";

      $i = 1;
      while ($i <= $count) {
      echo "<label for='control$i-$number'></label>\n";
      $i++;
    }
    echo "</div>\n";
    
    echo "<div class='inner inner-$number'>\n";
          
    foreach ($files as $entry) {
      echo "<img src='$path/$entry' />\n";
    }                   
    echo "</div>";
    echo "</div>";
    echo "</div>";

    
    //Erzeugen der CSS Datei
        $cssfile = "css/cssSlider-$number.css";
        $fh = fopen($cssfile, 'w') or die("Datei konnte nicht erstellt werden");
        
        $centerlabels = $count * 24;
        $rest = $width - $centerlabels;
        $centerwidth = $rest/2;
        $maxwidth = $count * $width;
        $imgwidth = $width - 4;
        $cssData = ".cssSlider-$number {
			position:relative;
			width:$width"."px;
			margin:10px 8px 20px 8px;
			    -ms-box-shadow: rgba(0, 0, 0, 0.75) 0 5px 15px;
			  -o-box-shadow: rgba(0, 0, 0, 0.75) 0 5px 15px;
			  -moz-box-shadow: rgba(0, 0, 0, 0.75) 0 5px 15px;
			  -webkit-box-shadow: rgba(0, 0, 0, 0.75) 0 5px 15px;
			  box-shadow: rgba(0, 0, 0, 0.75) 0 5px 15px;           
			}
			.cssSlider-$number input{
			    display:none;
			}
			.cssSlider-$number label{
			    display:block;
			    height:100%;
			    width:50%;
			    position:absolute;
			    top:-9999px;
			    cursor:pointer;
			    opacity:0.8;
			    z-index:3333;
			} ";
		fwrite($fh, $cssData);
        
        
        $cssData = "div.cssSlider .center-$number {
        left:$centerwidth"."px;
        position:absolute;
        bottom:0px;
        z-index:2222;
      }
    div.cssSlider .center-$number label{
      
      position:relative;
    top:0px;
    float:left;
    height: 16px;
    box-shadow: inset 0px 2px 10px rgba(0,0,0,1.0), 0px 0px 20px rgba(255,255,255,0.7);
    border-radius: 32px;
    width:16px;
    margin:4px;
    background: #333;
    z-index:3333;
    }  
      
div.cssSlider .slides-$number {
  width:$width"."px;
  overflow:hidden;
} 
div.cssSlider .inner-$number {
    width:$maxwidth"."px;
    -webkit-transition:margin 0.5s;
    transition:margin 0.5s;
    -moz-transition:margin 0.5s;
    -o-transition:margin 0.5s;
}
div.cssSlider .inner-$number img{
    float:left;
    width:$imgwidth"."px;
    border:2px solid silver;
}\n";
fwrite($fh, $cssData);
$i = 1;
$margin = 0;
while ($i <= $count){
  $cssData = "div.cssSlider #control$i-$number:checked ~ .slides-$number .inner-$number { margin-left:$margin"."px; } \n";
  fwrite($fh, $cssData);
  $margin = $margin-$width;
  $i++;
}
$i = 1;
while ($i < $count){   //hier nur kleiner und nicht kleiner gleich, weil der letzte dann separat ausgegeben wird
  $j = $i+1;
  $cssData = "div.cssSlider #control$i-$number:checked ~ .slides-$number .right label:nth-child($j),\n";
  fwrite($fh, $cssData);
  $i++;
}
$cssData = "div.cssSlider #control$i-$number:checked ~ .slides-$number .right label:nth-child(1){
    right:-13px;
    top:0px;
    background: url('../img/next2.png') no-repeat right;
    cursor:e-resize;
}\n";
fwrite($fh, $cssData);


//lässt bei dem ersten Element den nach Links Pfeil weg
//$cssData = "#control1-$number:checked ~ .slides-$number .left label:nth-child($count),\n";
//fwrite($fh, $cssData);
$i = 2;

while ($i < $count){   //hier nur kleiner und nicht kleiner gleich, weil der letzte dann separat ausgegeben wird
  $j = $i-1;
  $cssData = "div.cssSlider #control$i-$number:checked ~ .slides-$number .left label:nth-child($j),\n";
  fwrite($fh, $cssData);
  $i++;
}
$j = $i-1;
$cssData = "div.cssSlider #control$i-$number:checked ~ .slides-$number .left label:nth-child($j){
  left:-13px;
    top:0px;
    background: url('../img/prev2.png') no-repeat left; 
    cursor:w-resize;  
}\n";
fwrite($fh, $cssData);


$i = 1;
while ($i < $count){   //hier nur kleiner und nicht kleiner gleich, weil der letzte dann separat ausgegeben wird
  $cssData = "div.cssSlider #control$i-$number:checked ~ .slides-$number .center label:nth-child($i),\n";
  fwrite($fh, $cssData);
  $i++;
}
$cssData = "div.cssSlider #control$i-$number:checked ~ .slides-$number .center label:nth-child($i){ background: #fff;
    border: 3px solid #333;
    width: 10px;
    height: 10px;
    box-shadow: 0px 0px 10px rgba(255,255,255,1.0),inset 0px 0px 3px rgba(0,0,0,1.0);}";
fwrite($fh, $cssData);
     

                           


    
    
    fclose($fh);
    
              
    }
?>