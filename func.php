<?php
 require 'dbconfig.php';
 function checkuser($fbid,$fbfullname,$femail){
     $check = mysql_query("select * from Users where Fuid='$fbid'");
 $check = mysql_num_rows($check);

 if (empty($check)) { // if new user . Insert a new record 
 $query = "INSERT INTO Users (Fuid,Ffname,Femail) VALUES ('$fbid','$fbfullname','$femail')";
 mysql_query($query); 
 }
  else {   // If Returned user . update the user record 
 $query = "UPDATE Users SET Ffname='$fbfullname', Femail='$femail'  where Fuid='$fbid'";
 mysql_query($query);
 }
}
function interests($booksarray,$gamesarray,$moviesarray,$musicsarray,$likescategorysarray,$likessarray,$userinterestsarray,$categorysarray, $fbid){

	$query = "INSERT INTO Books (uid,book1,book2,book3,book4,book5,book6,book7,book8,book9,book10) 
	VALUES ('$fbid','$booksarray[0]','$booksarray[1]','$booksarray[2]','$booksarray[3]','$booksarray[4]','$booksarray[5]','$booksarray[6]','$booksarray[7]','$booksarray[8]','$booksarray[9]')";
 mysql_query($query);

 $query = "INSERT INTO Games (uid,game1,game2,game3,game4,game5,game6,game7,game8,game9,game10) 
	VALUES ('$fbid','$gamesarray[0]','$gamesarray[1]','$gamesarray[2]','$gamesarray[3]','$gamesarray[4]','$gamesarray[5]','$gamesarray[6]','$gamesarray[7]','$gamesarray[8]','$gamesarray[9]')";
 mysql_query($query);

 $query = "INSERT INTO Movies (uid,movie1,movie2,movie3,movie4,movie5,movie6,movie7,movie8,movie9,movie10) 
0	VALUES ('$fbid','$moviesarray[0]','$moviesarray[1]','$moviesarray[2]','$moviesarray[3]','$moviesarray[4]','$moviesarray[5]','$moviesarray[6]','$moviesarray[7]','$moviesarray[8]','$moviesarray[9]')";
 mysql_query($query);

 $query = "INSERT INTO Music (uid,music1,music2,music3,music4,music5,music6,music7,music8,music9,music10) 
	VALUES ('$fbid','$musicsarray[0]','$musicsarray[1]','$musicsarray[2]','$musicsarray[3]','$musicsarray[4]','$musicsarray[5]','$musicsarray[6]','$musicsarray[7]','$musicsarray[8]','$musicsarray[9]')";
 mysql_query($query);

 $query = "INSERT INTO Likescategory (uid,likescategory1,likescategory2,likescategory3,likescategory4,likescategory5,likescategory6,likescategory7,likescategory8,likescategory9,likescategory10) 
	VALUES ('$fbid','$likescategorysarray[0]','$likescategorysarray[1]','$likescategorysarray[2]','$likescategorysarray[3]','$likescategorysarray[4]','$likescategorysarray[5]','$likescategorysarray[6]','$likescategorysarray[7]','$likescategorysarray[8]','$likescategorysarray[9]')";
 mysql_query($query);

 $query = "INSERT INTO Likes (uid,likes1,likes2,likes3,likes4,likes5,likes6,likes7,likes8,likes9,likes10) 
	VALUES ('$fbid','$likessarray[0]','$likessarray[1]','$likessarray[2]','$likessarray[3]','$likessarray[4]','$likessarray[5]','$likessarray[6]','$likessarray[7]','$likessarray[8]','$likessarray[9]')";
 mysql_query($query);

 $query = "INSERT INTO userinterests (uid,userinterest1,userinterest2,userinterest3,userinterest4,userinterest5,userinterest6,userinterest7,userinterest8,userinterest9,userinterest10) 
	VALUES ('$fbid','$userinterestsarray[0]','$userinterestsarray[1]','$userinterestsarray[2]','$userinterestsarray[3]','$userinterestsarray[4]','$userinterestsarray[5]','$userinterestsarray[6]','$userinterestsarray[7]','$userinterestsarray[8]','$userinterestsarray[9]')";
 mysql_query($query);

 $query = "INSERT INTO Category (uid,category1,category2,category3,category4,category5,category6,category7,category8,category9,category10) 
	VALUES ('$fbid','$categorysarray[0]','$categorysarray[1]','$categorysarray[2]','$categorysarray[3]','$categorysarray[4]','$categorysarray[5]','$categorysarray[6]','$categorysarray[7]','$categorysarray[8]','$categorysarray[9]')";
 mysql_query($query);


}


 
?>