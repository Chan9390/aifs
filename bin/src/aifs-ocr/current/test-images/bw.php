<?php 
 // The file you are grayscaling 
 $file = 'test.png'; 

 // This sets it to a .jpg, but you can change this to png or gif if that is what you are working with
 header('Content-type: image/png'); 

 // Get the dimensions
 list($width, $height) = getimagesize($file); 

 // Define our source image 
 $source = imagecreatefrompng($file); 

 // Creating the Canvas 
 $bwimage= imagecreate($width, $height); 

 //Creates the 256 color palette
 for ($c=0;$c<256;$c++) 
 {
 $palette[$c] = imagecolorallocate($bwimage,$c,$c,$c);
 }

 //Creates yiq function
 function yiq($r,$g,$b) 
 {
 return (($r*0.299)+($g*0.587)+($b*0.114));
 } 
