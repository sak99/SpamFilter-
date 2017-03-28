<?php
session_start();
// added in v4.0.0
require_once 'autoload.php';
require 'functions.php'; 
$app_id             = '';  //Facebook App ID
$app_secret         = ''; //Facebook App Secret
$required_scope     = 'public_profile,user_interests, email,read_stream,user_likes,user_education_history,user_hometown,user_hometown,user_work_history,user_birthday, user_friends,user_about_me, user_actions.books,read_friendlists '; //Permissions required
$redirect_url       = 'http://localhost/filter/final.php'; //FB redirects to this page with a code

//include autoload.php from SDK folder, just point to the file like this:
require_once __DIR__ . "/facebook-php-sdk-v4-4.0-dev/autoload.php";

//import required class to the current scope
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\GraphObject;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;


FacebookSession::setDefaultApplication($app_id , $app_secret);
$helper = new FacebookRedirectLoginHelper($redirect_url);


//try to get current user session
try {
  $session = $helper->getSessionFromRedirect();
} catch(FacebookRequestException $ex) {
    die(" Error : " . $ex->getMessage());
} catch(\Exception $ex) {
    die(" Error : " . $ex->getMessage());
}


if ( isset( $session ) ) {

  //$logoutUrl=$helper->getLogoutUrl($session,'logout.php');
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject();
  $fbid = $graphObject->getProperty('id');              // To Get Facebook ID
  $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
  $femail = $graphObject->getProperty('email');    // To Get Facebook email ID


 $check = mysql_query("select * from Users where Fuid='$fbid'");
 $check = mysql_num_rows($check);

 if (empty($check)) { // if new user . Insert a new record 
 $query = "INSERT INTO Users (Fuid,Ffname,Femail) VALUES ('$fbid','$fbfullname','$femail')";
 mysql_query($query); 
 }
else{
	echo "User already exists";
}
//books
/*$books = (new FacebookRequest($session, 'GET', '/me/books'))->execute()->getGraphObject()->asArray();
  $booksarray=array();
  for($i=0;$i<10 && (!empty($books['data'][$i]));$i++){

    $array = get_object_vars( $books['data'][$i]);
     $booksarray[]=$array['name'];
     }

     //movies
$movies = (new FacebookRequest($session, 'GET', '/me/movies'))->execute()->getGraphObject()->asArray();    
 $moviesarray=array();
  for($i=0;$i<10 && (!empty($movies['data'][$i]));$i++){

    $array = get_object_vars( $movies['data'][$i]);
     $moviesarray[]=$array['name'];
     }

     //music

$music = (new FacebookRequest($session, 'GET', '/me/music'))->execute()->getGraphObject()->asArray();

   $musicarray=array();
  for($i=0;$i<10 &&(!empty($music['data'][$i]));$i++){
  $array = get_object_vars( $music['data'][$i]);
     $musicarray[]=$array['name'];
   }

//games
$games = (new FacebookRequest($session, 'GET', '/me/games'))->execute()->getGraphObject()->asArray();
$gamesarray=array();
  for($i=0;$i<10 && (!empty($games['data'][$i]));$i++){
  $array = get_object_vars( $games['data'][$i]);
     $gamesarray[]=$array['name'];
   }
*/
 //user interests
 $count=0;

$user_interest = (new FacebookRequest($session, 'GET', '/me/interests'))->execute()->getGraphObject()->asArray();
    $userarray= array();
    $category=array();
    while(!empty($user_interest['data'][$count]))
      {$count++;}
    for($i=0;$i<$count;$i++){

    $array = get_object_vars( $user_interest['data'][$i]);
     $userarray[]=$array['name'];
     if(!in_array($array['category'], $category))
     $category[]=$array['category'];
    }
 
///likes
$count=0;     


$likes = (new FacebookRequest($session, 'GET', '/me/likes'))->execute()->getGraphObject()->asArray();
while(!empty($likes['data'][$count]))
      {$count++;}
 $likessarray=array();
 $likescategory=array();
    for($i=0;$i<$count;$i++){
    $array = get_object_vars( $likes['data'][$i]);
     $likessarray[]=$array['name'];
     if(!in_array($array['category'], $likescategory))
     $likescategory[]=$array['category'];
   if(strcmp($array['category'],"Retail and consumer merchandise")==0)
     $likescategory[]=$array['name'];
    }

 echo '<pre>';
        print_r($likescategory);
        echo '<pre>';
$count=0;
$ed = (new FacebookRequest($session, 'GET', '/me?fields=education'))->execute()->getGraphObject()->asArray();
while(!empty($ed['education'][$count]))
    {$count++;}
 $edarray=array();
    for($i=0;$i<$count;$i++){
    $array = get_object_vars( $ed['education'][$i]);
    if(!in_array($array['type'], $edarray))
     $edarray[]=$array['type'];
    if (isset($array['concentration'])) {
      $temp=array();
       $temp=$array['concentration'][0];
         $temp2=get_object_vars( $temp);
          $edarray[]=$temp2['name'];
     }
 }

$work = (new FacebookRequest($session, 'GET', '/me?fields=work'))->execute()->getGraphObject()->asArray();
 $count=0;
 while(!empty($work['work'][$count]))
    {$count++;}
 $workarray=array();
    for($i=0;$i<$count;$i++){
    $array = get_object_vars( $work['work'][$i]);
     if (isset($array['employer'])) {
      $temp1=array();
      $temp1=get_object_vars($array['employer']);
      $workarray[]=$temp1['name'];
     }
 }

 echo '<pre>';
 
        print_r($workarray);
        echo '<pre>';
/*$mutual = new FacebookRequest(new FacebookRequest(
  $session,
  'GET',
  '/{user-id}',
  array (
    'fields' => 'context.fields(mutual_friends)',
  )
)->execute()->getGraphObject()->asArray();*/


//session variables 
     $_SESSION['FBID'] = $fbid;           
    $_SESSION['FULLNAME'] = $fbfullname;
   
    $_SESSION['EMAIL'] =  $femail;
//function calls

  checkuser($fbid,$fbfullname,$femail);
  //header("Location: finalindex.php");
  
}

 else{ 

    //display login url 
    $login_url = $helper->getLoginUrl( array( 'scope' => $required_scope ) );
    header("Location: ".$login_url); 
} 