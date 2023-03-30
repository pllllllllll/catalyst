<?php
//C:\xampp\php\php.exe C:\xampp\htdocs\catalyst\catalyst\user_upload.php  --file=C:\xampp\htdocs\catalyst\catalyst\users.csv

//https://www.geeksforgeeks.org/how-to-read-user-or-console-input-in-php/
//command line prompt user for table name

$Dry_Run = $MySQL_Username = $MySQL_Password = $MySQL_Host = $Create_Table = $Table_Name = $MySQL_DB = '';
$options = ['file:'];
$values = getopt(null, $options);
$UsersFile = $values['file'];

$Create_Table = readline('Create table in database only (y/n) : ');

if ($Create_Table=='y'){ //create table only, no insert or file reading
	$Table_Name = readline('Enter a table name: ');
	$MySQL_Host = readline('Enter a mysql host: ');
	$MySQL_DB = readline('Enter database to use: ');
	$MySQL_Username = readline('Enter a mysql username: ');
	$MySQL_Password = readline('Enter a mysql password: ');
	ftnCreateTable($Table_Name,$MySQL_Username,$MySQL_Password,$MySQL_Host,$MySQL_DB);
}elseif($Create_Table=='n'){//proceed to next question
	$Dry_Run = readline('Is this a dry run (y/n) : '); //create table and read file data
	$Table_Name = readline('Enter a table name: ');
	$MySQL_Host = readline('Enter a mysql host: ');
	$MySQL_DB = readline('Enter database to use: ');
	$MySQL_Username = readline('Enter a mysql username: ');
	$MySQL_Password = readline('Enter a mysql password: ');	
	ftnCreateTable($Table_Name,$MySQL_Username,$MySQL_Password,$MySQL_Host,$MySQL_DB);
	ftnReadFile($UsersFile);
	if ($Dry_Run=='n'){ //proceed with create/read/insert

	}elseif($Dry_Run=='y'){//no db insert, create table and read data
		$Table_Name = readline('Enter a table name: ');
	}else{
		echo "Answer must be 'y' or 'n' , please try again.\n";	
		exit();
	}
}else{
	echo "Answer must be 'y' or 'n' , please try again.\n";	
	exit();
}




//create table in all cases
if (ISSET($ftnTableName)){//create table
	//ftnCreateTable($ftnTableName,$ftnUsername,$ftnPassword);
}else{
	exit(); 
}

/////////////////////////////////
function ftnCreateTable($ftnTable,$ftnUsername,$ftnPassword,$ftnHost,$ftnDB){
$connection = mysqli_connect($ftnHost, $ftnUsername, $ftnPassword, $ftnDB); 
if (!$connection) { 
  die("Failed ". mysqli_connect_error()); 
} else{
	echo "DB server connection good \n";
}
$query="DROP TABLE IF EXISTS $ftnDB.$ftnTable";
mysqli_query($connection,$query) or die(mysqli_error()); 

$query = "CREATE TABLE  users (name varchar(100) NOT NULL,surname varchar(100) NOT NULL,email varchar(100) NOT NULL, UNIQUE KEY email(email))"; 

if (mysqli_query($connection, $query)) { 
  echo "Table successfully created\n"; 
} else { 
  echo "Error:" . mysqli_error($connection); 
} 

//close the connection 
mysqli_close($connection); 

}//function ftnCreateTable

//dry run, read data
ftnReadFile($strUsersFile){
$row = 1;
	if (($handle = fopen($ftnUsersFile, "r")) !== FALSE) {
		fgetcsv($handle, 10000, ","); //reads first line header, so its skipped in the while loop
	  while (($data = fgetcsv($handle, 200, ",")) !== FALSE) {
	    $num = count($data);
	    //echo "\n $num fields in line $row: \n";
	    $row++;
	    $ValidEmail='yes';
	   	$ValidNumberOfFields='yes';

	    //validate number of fields is 3
	    if ($num<>3) {
	    	//format incorrect for line
	    	echo "\n Number of fields ($num) not 3 for line $row , skipping this line for processing. ".$data[0];
	    }else{
		    for ($c=0; $c < $num; $c++) {
		    	//echo "c=$c num=$num /n";
		    	//clean data: 
		    	//remove extra spaces
		        $data[$c] = trim($data[$c]);
		        //upper case name and surname
		        if ($c==0 || $c==1) {
		        	$data[$c] = ucwords(strtolower($data[$c]));
		        }elseif($c==2){	//email lower case  
					$data[$c] = strtolower($data[$c]);
			    	//validate email data, do not insert into mysql otherwise
					if (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $data[$c])) {
					  //echo 'This is a valid email. '.$data[$c];
					} else {
					  echo "\n Invalid email. Not processing this record ".$data[$c];
					  $EmailValid='no';
					}
		        }    
		        //field of data
		        //echo $data[$c] . "\n";
		    }
		}
	  }
	  fclose($handle);
	}
}//ftnReadFile($strUsersFile){













//create table, read data, insert data
//ftnInsertData($UsersFile, $Table_Name, $MySQL_Username, $MySQL_Password);




function ftnInsertData($ftnUsersFile,$ftnTableName,$ftnUsername,$ftnPassword){

	//$lines = file($values['file'], FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
	// Rest of the script...

	$row = 1;
	if (($handle = fopen($ftnUsersFile, "r")) !== FALSE) {
		fgetcsv($handle, 10000, ","); //reads first line header, so its skipped in the while loop
	  while (($data = fgetcsv($handle, 200, ",")) !== FALSE) {
	    $num = count($data);
	    //echo "\n $num fields in line $row: \n";
	    $row++;
	    $ValidEmail='yes';
	   	$ValidNumberOfFields='yes';

	    //validate number of fields is 3
	    if ($num<>3) {
	    	//format incorrect for line
	    	echo "\n Number of fields ($num) not 3 for line $row , skipping this line for processing. ".$data[0];
	    }else{
		    for ($c=0; $c < $num; $c++) {
		    	//echo "c=$c num=$num /n";
		    	//clean data: 
		    	//remove extra spaces
		        $data[$c] = trim($data[$c]);
		        //upper case name and surname
		        if ($c==0 || $c==1) {
		        	$data[$c] = ucwords(strtolower($data[$c]));
		        }elseif($c==2){	//email lower case  
					$data[$c] = strtolower($data[$c]);
			    	//validate email data, do not insert into mysql otherwise
					if (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $data[$c])) {
					  //echo 'This is a valid email. '.$data[$c];
					} else {
					  echo "\n Invalid email. Not processing this record ".$data[$c];
					  $EmailValid='no';
					}
		        }    
		        //field of data
		        //echo $data[$c] . "\n";
		    }
		}
	  }
	  fclose($handle);
	}
}// function ftnReadData($ftnOptions){
?>

