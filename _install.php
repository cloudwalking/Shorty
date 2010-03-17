<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

if(isset($_GET['step'])){
	$step = $_GET['step'];
} else {
	$step = '';
}

echo '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
       <meta name="generator" content="HTML Tidy for Mac OS X (vers 1st December 2004), see www.w3.org" />
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
       <title>Shorty Installer</title>
       <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
       <link type="text/css" rel="stylesheet" href="skin/originhal/style.css" />
    </head>

    <body>

       <div id="brand">
          <h1>Shorty</h1>
       </div>
       
       <div id="menu">
             <ul>
             </ul>
          </div>
       
       <div id="board">
           <div id="install">
                 <h2>Shorty 0.7 Beta
                 <span>Fresh Installation</span>
                 <span>Copyright 2006 by Khoi Vinh and Reed Morse</span>
                 </h2>
                 <br />
                 <dl id="versionHistory">';
if($step !== "2"){
                 echo '
                 <form id="install-form" action="_install.php?step=2" method="post">
                   <dt>1.</dt>
                   <dd>
                     <p>
                       <span class="releaseTitle">Hello</span><br />
                       Thanks for downloading Shorty! Let\'s get it working.<br />First of all, please modify the configuration.php file with the correct values for your server. You can use a current database, as long as the table names don\'t conflict with Shorty\'s tables.<br /><br />These are the only values you should need to change in configuration.php:
                       <ul>
                         <li>$db[\'server\']</li>
                         <li>$db[\'username\']</li>
                   	     <li>$db[\'password\']</li>
                   	     <li>$db[\'database\']</li>
                       </ul>
                     </p>
                   </dd>

                   <dt>2.</dt>
                   <dd>
                     <p>
                       <span class="releaseTitle">Upload</span><br />
                       Please ensure that you have uploaded all of the files and folders from the Shorty package. Many people neglect to upload the .htaccess file, as it is often hidden from Finder.
                     </p>
                   </dd>
                   
                   <dt>3.</dt>
                      <dd>
                        <p>
                          <span class="releaseTitle">Create your user account</span><br />
                          Set up your username and password for logging in, and your email address for password recovery.<br /><br />
                        </p>
                        <label>Username</label>
                      	<input type="text" name="username" value="" class="inputBox" />
                      	<label>Password</label>
                      	<input type="password" name="password" value="" class="inputBox" />
                      	<label>Again</label>
                      	<input type="password" name="again" value="" class="inputBox" />
                      	<label>Email</label>
                      	<input type="text" name="email" value="" class="inputBox" />
                      </dd>
                      
                      <dt>4.</dt>
                            <dd>
                              <p>
                                <input type="submit" value="Install!" />
                              </p>
                            </dd>
                        </form>
                      </dl>
                 </div>';
}

if($step == "2"){
	echo '
	    <dt> </dt>
           <dd>
             <p>
	';
	
	$i = 0;
	$x = 0;
	
	if(file_exists(".htaccess")){
		$i++;
	} else {
		echo '.htaccess doesn\'t seem to exist.<br />';
	}
	
	if(file_exists("configuration.php")){
		$i++;
	} else {
		echo 'configuration.php doesn\'t seem to exist.<br />';
	}
	
	if(file_exists("functions.php")){
	    $i++;
    } else {
        echo 'functions.php doesn\'t seem to exist.<br />';
    }
	
	if($i == 3){
	    require("configuration.php");
	    require("functions.php");
	    
	    if(mysql_connect($db['server'],$db['username'],$db['password'])){
	        $i++;
        } else {
            echo 'Could not establish a connection to the database. Check configuration.php for the correct values!';
        }
    }
    
    if($i == 4){
        if(mysql_select_db($db['database'])){
            $i++;
        } else {
            echo 'Could not select the database. Make sure it exists! Check configuration.php for the correct value!';
        }
    }
    
    if($i == 5){
        $password = $_POST['password'];
        $password_again = $_POST['again'];
        if($password == $password_again){
            $i++;
        } else {
            echo 'Your passwords don\'t match. Go back and try it again. Good thing we checked :)';
        }
    }
    
    if($i == 6){
	    $query_users = "
    		CREATE TABLE `".$user_table."` (
    			 `id` int(11) NOT NULL auto_increment,
    			 `username` text NOT NULL,
    			 `password` text NOT NULL,
    			 `name` text NOT NULL,
    			 `email` text NOT NULL,
    			 `theme` text NOT NULL,
    			 `perpage` text NOT NULL,
    			 `mode` text NOT NULL,
    			 `longURL` tinyint(1) NOT NULL,
    			 `level` int(1) NOT NULL default '2',
    			 PRIMARY KEY  (`id`)
    		) AUTO_INCREMENT=1 ;
    	";
    	
    	if(mysql_query($query_users)){
    	    $i++;
	    } else {
	        echo 'Couldn\'t create the user table \''.$user_table.'\'. It probably already exists.';
        }
    }
    
    if($i == 7){
	    $query_shorties = "
    		CREATE TABLE `".$main_table."` (
    			`id` int(11) NOT NULL auto_increment,
    			`day` int(2) NOT NULL,
    			`month` int(2) NOT NULL,
    			`year` int(4) NOT NULL,
    			`target` text NOT NULL,
    			`clicks` int(11) NOT NULL default '0',
    			`key1` text NOT NULL,
    			`key2` text NOT NULL,
    			`key3` text NOT NULL,
    			`key4` text NOT NULL,
    			`key5` text NOT NULL,
    			PRIMARY KEY  (`id`)
    		) AUTO_INCREMENT=1 ;
    	";
    	
    	if(mysql_query($query_shorties)){
    	    $i++;
	    } else {
	        echo 'Couldn\'t create the Shorty table \''.$main_table.'\'. It probably already exists.';
        }
    }
    
    if($i == 8){
        $md5 = md5($password);
        $username = strtolower($_POST['username']);
        $query_insert_user = "INSERT INTO `".$user_table."` (`username`, `password`, `email`, `level`, `theme`, `longURL`, `perpage`) VALUES ('".$username."', '".$md5."', '".$_POST['email']."', '2', 'originhal', '1', 'all')";
        
        if(mysql_query($query_insert_user)){
            $i++;
        } else {
            echo 'Couldn\'t insert you as a user. This error message should never been seen. Something strange is afoot.<br />';
            $x++;
        }
    }
		
    if($i == 9){
        $query_shorty = "INSERT INTO `".$main_table."` (`target`, `day`, `month`, `year`, `key1`) VALUES ('http://get-shorty.com', '".date('j')."', '".date('n')."', '".date('Y')."', 'shorty')";
        
        if(mysql_query($query_shorty)){
            $i++;
        } else {
            echo 'Couldn\'t insert a test Shorty. This error message should never been seen. Something strange is afoot.<br />';
            $x++;
        }
    }
    
    if($i == 10){
		// Check once more that the stuff was inserted without a problem
		$query_check_user = "SELECT * FROM `".$user_table."`";
	    $query_check_shorty = "SELECT * FROM `".$main_table."`";
	    $result_check_user = mysql_query($query_check_user);
	    $result_check_shorty = mysql_query($query_check_shorty);
	    
	    if(mysql_num_rows($result_check_user)){
		    $i++;
	    } else {
	        echo 'Couldn\'t insert the user into the database. Something\'s wrong.';
        }
        
        if(mysql_num_rows($result_check_shorty)){
            $i++;
        } else {
            echo 'Couldn\'t insert the test Shorty into the database. Something\'s wrong.';
        }
    }
    
    if($i == 12){
        //if(!($dir == "")) { $dir = '/'.$dir; }
		
		// Figure out where on the server the urls are
		$where = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
		$where = substr_replace($where, "", -13); // Remove /_install.php
		
		echo '<span class="releaseTitle">Shorty has been installed!</span><br />You should remove _install.php from the server.<br /><br />
		You can start using Shorty now (you\'ll have to login), here: <a href="'.$where.'/index.php">'.$where.'/</a>.';
    } else {
        echo '<br /><br />Shorty wasn\'t installed successfully.';
        
        //$query_flush_VERY_BAD = "DROP TABLE `".$main_table."`, `".$user_table."`";
    }
    
    echo '</p>
        </dd>';
}		

echo '
            </dl>
        </div>

    </body>
</html>
';
 
 ?>