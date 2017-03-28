<html>
<?php
require 'functions.php';
require_once 'swift/lib/swift_required.php';
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');    // DB username
define('DB_PASSWORD', '');    // DB password
define('DB_DATABASE', 'spam');      // DB name
$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die( "Unable to connect");
$database = mysql_select_db(DB_DATABASE) or die( "Unable to select database");
$con = mysqli_connect("localhost","root","","spam");
$path_to_file='C:/Users/arun/Documents/Visual Studio 2010/Projects/SpamFilterSample/bin/Debug/Detail/';
$to="";  
//getting spam score
$searchthis1 = "new score1:";
$searchthis2 = "from:";
$searchthis3 = "to:";
$score = array();
$sender=array();

//$flag="false";
$handle = @fopen($path_to_file."info.txt", "r");

if ($handle)
{
  while (!feof($handle))
    {
      $buffer = fgets($handle);
        $buffer=strtolower($buffer);
       if(strpos($buffer, $searchthis3) !== FALSE)
            {
               /* $buffer=substr($buffer,4);
                $to =$buffer ;*/
                $tto=extract_email_address($buffer);

                $to =$tto;
            
        }
     
           if(strpos($buffer, $searchthis1) !== FALSE)
            {
                $buffer=substr($buffer,12);
            $score[] =$buffer ;
        }

        if(strpos($buffer, $searchthis2) !== FALSE)
            {

                $temp=extract_email_address($buffer);

                $sender[] =$temp ;
        }

       
    
   
    

}
fclose($handle);
}

for($i=0;$i<count($score);$i++) {
 
  $check = mysql_query("select *from `Contacts` where `friend`='".$sender[$i]."'");
  $ans = mysql_fetch_array($check);
 $check = mysql_num_rows($check); 
 
    if($score[$i]>0.5 && !empty($check) && $ans['trust']>0.01)
      { 

$query = "UPDATE `Contacts` set `trust`=(`trust`*0.5) where `friend`='".$sender[$i]."'";
mysql_query($query); 
      }
      else if($score[$i]<=0.5 && !empty($check) && $ans['trust']<=1.0)
      {
        $query = "UPDATE `Contacts` set `trust`=(`trust`+ 0.1) where `friend`='".$sender[$i]."'";
mysql_query($query); 

      }

  }
  
$to=rtrim($to);
$to=ltrim($to);

$query= "SELECT DISTINCT(`spammer`) FROM notify";
 $check = mysql_query($query);
$spamm=array();
$addresses=array();
  while ($answer = mysql_fetch_array($check)) 
{
  
 $spamm[]=$answer['spammer'];
 
$query="SELECT `friend` FROM `contacts` where gmailid='".$to."'" ;

$result = mysql_query($query,$connection);

if($result === FALSE) { 
    echo mysql_error(); // TODO: better error handling
}

while ($row = mysql_fetch_assoc($result)) 
  {

    //if(strcmp($row['friend'],$answer['spammer'])!=0)
       $addresses[] = $row['friend'];
   }

      //->setTo(array('kadam248@yahoo.in'))
}


$addresses=array_unique($addresses);

//mail
if(count($spamm) > 0)
{
//$spammtemp = array_unique($spamm);
  $spamm= implode(",", $spamm);

$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('shazykads@gmail.com')
  ->setPassword('sidharth');

try {
    $mailer = Swift_Mailer::newInstance($transport);
} catch(Swift_TransportException $exception) {
    var_dump($exception);
}
$body='mails from '.$spamm.' has been found to contain spam messages.Open mails from these email ids at your own risk. This is precautionary email. ';



$message = Swift_Message::newInstance('Spammer found!!')
  ->setFrom(array('shazykads@gmail.com' => 'Spam Filter'))
  ->setBcc($addresses)
  ->setBody($body);

/*
$message = Swift_Message::newInstance('Test Subject')
  ->setFrom(array('abc@example.com' => 'ABC'))
  ->setTo(array('kadsshital@outlook.com'))
  ->setBody($body);*/
$result = $mailer->send($message);
    


}

//display updated trust values
 $trial1="SELECT * from `contacts` where `gmailid`='".$to."'";
 
 $res=mysql_query($trial1);

$count = mysql_num_rows($res);

  if ($count > 0) {
    echo "<table><tr><th>Friend id</th><th>Trust</th></tr>";
    // output data of each row
while ($row = mysql_fetch_array($res)){ 
        echo "<tr><td>".$row["friend"]."</td><td>".$row["trust"]."</td></tr>";
    }
    echo "</table>";
} 
mysql_query("DELETE from `notify`");
 mysql_close($connection);
 
?>
</html>