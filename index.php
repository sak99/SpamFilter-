<?php
session_start(); 
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');    // DB username
define('DB_PASSWORD', '');    // DB password
define('DB_DATABASE', 'spam');      // DB name

$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die( "Unable to connect");
$database = mysql_select_db(DB_DATABASE) or die( "Unable to select database");
$app_id             = //Facebook App ID
$app_secret         = //Facebook App Secret
$required_scope     = 'public_profile,user_interests, email,read_stream,user_likes,user_education_history,user_hometown,user_hometown,user_work_history,user_birthday, user_friends,user_about_me, user_actions.books,read_friendlists '; //Permissions required
$redirect_url       = 'http://localhost/filter/final/index.php'; //FB redirects to this page with a code

//include autoload.php from SDK folder, just point to the file like this:
require_once __DIR__ . "/facebook-php-sdk-v4-4.0-dev/autoload.php";
//require 'dbconfig.php';
require 'func.php';
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

   // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject();
  $fbid = $graphObject->getProperty('id');              // To Get Facebook ID
  $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
  $femail = $graphObject->getProperty('email');    // To Get Facebook email ID

checkuser($fbid,$fbfullname,$femail);

 

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
//qualification
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

//work history
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

// Optional data

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
 
/*$mutual = new FacebookRequest(new FacebookRequest(
  $session,
  'GET',
  '/{user-id}',
  array (
    'fields' => 'context.fields(mutual_friends)',
  )
)->execute()->getGraphObject()->asArray();
*/
//writing to files
$path_to_file='users/';
    $filename="$fbid.txt";
   $myfile = fopen($path_to_file.$filename, 'w') or die("can't open file");
  
  $txt1=implode(",",$category);
  $txt3=implode(",",$userarray);
  $txt7=implode(",",$likescategory);  
  $txt9=implode(",",$edarray);
  $txt10=implode(",",$workarray);

/*  $txt4=implode(",",$musicarray);
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
//session variables 
     $_SESSION['FBID'] = $fbid;           
    $_SESSION['FULLNAME'] = $fbfullname;
   
    $_SESSION['EMAIL'] =  $femail;
//saving in text file
}
 
else{ 

    //display login url 
    $login_url = $helper->getLoginUrl( array( 'scope' => $required_scope ) );
    header("Location: ".$login_url); 
}

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
     
    <title>Login with Facebook</title>
<link href="http://www.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet"> 
 </head>
  <body>
  <?php if (isset($_SESSION['FBID'])): ?>      <!--  After user login  -->
<div class="container">
<div class="hero-unit">
  <h1>Hello <?php echo $_SESSION['FULLNAME']; ?></h1>
  <p>You have successfully registered for our personalised spam filter!!</p>
  </div>
<div class="span4">
 <ul class="nav nav-list">
<li class="nav-header"></li>
  <li><img src="https://graph.facebook.com/<?php echo $_SESSION['FBID']; ?>/picture"></li>

<li class="nav-header">Facebook Email</li>
<li><?php echo $_SESSION['EMAIL']; ?></li>
<?php
if ( isset( $session ) ) {
  // user is logged in, display logout link
  echo '<a href="' . $helper->getLogoutUrl( $session,'http://localhost/filter/final/index.php' ) . '">Logout</a>';
}
?>
</ul></div></div>
    <?php else: ?>     <!-- Before login --> 
<div class="container">
<h1>Login with Facebook</h1>

           
<div>
      <a href="index.php">Login </a></div>
</div>
    <?php endif ?>
  </body>
</html>


