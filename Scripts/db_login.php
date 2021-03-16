<?php


//  echo "<br/> This is db_login.php <br/>";

  $db_host = "127.0.0.1";//"151.97.16.247";//
//  echo "$db_host <br/>";

  $db_database = "ESA-SSA-DB";
//  echo "$db_database <br/>";

  $db_username = "esa-ssa";//"root";//
//  echo "$db_username <br/>";

  $db_password = "esa\$oact";// "ESA-SSA";// # password is esa$oact, need to escape $ char
//  echo "$db_password <br/>";
//  echo "db_login.php ends here<br/>";

//  echo "Trying to connect to database <br/>";


  //$connection = mysql_connect('localhost', 'root', 'ESA-SSA');
  $connection = mysqli_connect($db_host, $db_username, $db_password, $db_database);

  if(!$connection){
    echo "Connection failed! <br/>";
    echo mysql_connect_errno();
    die('Could not connect to the database, Connect Error:'. mysql_connect_errno());

  }
  else{
//    echo "Connection ok! <br/>";
  }

?>
