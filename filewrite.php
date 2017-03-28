//saving in text file

$path_to_file='users/';
    $filename="$fbid.txt";
	 $myfile = fopen($path_to_file.$filename, 'w') or die("can't open file");
	
	$txt1=implode(",",$category);
	$txt3=implode(",",$userarray);
  $txt7=implode(",",$likescategory);
  
  $txt9=implode(",",$edarray);
  $txt10=implode(",",$workarray);
/*	$txt4=implode(",",$musicarray);
	$txt5=implode(",",$gamesarray);
	$txt6=implode(",",$moviesarray);
	 $txt2=implode(",",$gamesarray);
	$txt8=implode(",",$booksarray);*/
	fwrite($myfile, $txt1);
	fwrite($myfile, $txt3);
  fwrite($myfile, $txt7);
  fwrite($myfile, $txt9);
  fwrite($myfile, $txt10);
	/*fwrite($myfile, $txt3);
	fwrite($myfile, $txt4);
	fwrite($myfile, $txt5);
	fwrite($myfile, $txt6);
	
	fwrite($myfile, $txt8);
	fclose($myfile);*/

//header("Location: finalindex.php");
	