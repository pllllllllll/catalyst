<?php
//C:\xampp\php\php.exe C:\xampp\htdocs\catalyst\catalyst\user_upload.php  --file=C:\xampp\htdocs\catalyst\catalyst\users.csv

//https://www.geeksforgeeks.org/how-to-read-user-or-console-input-in-php/
//command line prompt user for table name


//https://stackoverflow.com/questions/19159195/after-using-phps-getopt-how-can-i-tell-what-arguments-remain
$arg_help = null; 
$arg_host = null; 
$arg_password = null; 
$arg_username = null; 
$arg_db = null; 
$arg_dry_run = null; 
$arg_create_table = null; 
$arg_file = null;  

//$argv contains an array of all the arguments passed to the script 
foreach ( $argv as $arg )
{
    unset( $matches );

    if ( preg_match( '/^--help$/', $arg, $matches ) ) {
        $arg_help = "y";
    }else if ( preg_match( '/^-h (.*)$/', $arg, $matches ) ) {
        $arg_host = $matches[1];
    }else if ( preg_match( '/^-p (.*)$/', $arg, $matches ) ) {
        $arg_password = $matches[1];
    }else if ( preg_match( '/^-u (.*)$/', $arg, $matches ) ) {
        $arg_username = $matches[1];
    }else if ( preg_match( '/^-db (.*)$/', $arg, $matches ) ) {
        $arg_db = $matches[1];
    }else if ( preg_match( '/^--file (.*)$/', $arg, $matches ) ) {
        $arg_file = $matches[1];
    }else if ( preg_match( '/^--dry_run$/', $arg, $matches ) ) {
        $arg_dry_run = "n";
    }else if ( preg_match( '/^--create_table$/', $arg, $matches ) ) {
        $arg_create_table = "y";
    }else{
        //unrecognized
    }
}//foreach

if ( ISSET($arg_help))    { 
	//display help options
	echo "--file [csv file name] – this is the name of the CSV to be parsed\n--create_table – this will cause the MySQL users table to be built (and no further
 action will be taken)\n--dry_run – this will be used with the --file directive in case we want to run the script but not
insert into the DB. All other functions will be executed, but the database won't be altered\n-u – MySQL username\n-p – MySQL password\n-h – MySQL host\n--help – which will output the above list of directives with details.";
}
if ( ($arg_create_table=='y') || (ISSET($arg_dry_run)) || ISSET($arg_file))    {  //db info required
	if ( $arg_host === null )    { 
		$arg_host = readline('Host is required : ');
	}
	if ( $arg_password === null )    { 
		$arg_password = readline('Password is required : ');
	}
	if ( $arg_username === null )    { 
		$arg_username = readline('Username is required : ');
	}
	if ( $arg_db === null )    { 
		$arg_db = readline('Database is required : ');
	}

	ftnCreateTable($arg_create_table,$arg_username,$arg_password,$arg_host,$arg_db);

	if ($arg_dry_run='n') { //file required
		if ( $arg_file === null )    { 
			$arg_file = readline('File is required : ');
		}		
		ftnReadFile($arg_file,$arg_dry_run,$arg_create_table,$arg_username,$arg_password,$arg_host,$arg_db);
	}
}









///////////functions///////////

function ftnCreateTable($ftnTable,$ftnUsername,$ftnPassword,$ftnHost,$ftnDB){
$connection = mysqli_connect($ftnHost, $ftnUsername, $ftnPassword, $ftnDB); 
if (!$connection) { 
  die("Failed ". mysqli_connect_error()); 
} else{
	echo "DB server connection good \n";
}
$query="DROP TABLE IF EXISTS ".$ftnDB.".users";
mysqli_query($connection,$query) or die(mysqli_error()); 

$query = "CREATE TABLE users (name varchar(100) NOT NULL,surname varchar(100) NOT NULL,email varchar(100) NOT NULL, UNIQUE KEY email(email))"; 

if (mysqli_query($connection, $query)) { 
  echo "Table successfully created\n"; 
} else { 
  echo "Error:" . mysqli_error($connection); 
} 

//close the connection 
mysqli_close($connection); 

}//function ftnCreateTable


function ftnReadFile($ftnUsersFile,$ftnDry_Run,$ftnTable,$ftnUsername,$ftnPassword,$ftnHost,$ftnDB){
//read file or read and insert data
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
		    	//clean data: 
		    	//remove extra spaces
		        $data[$c] = trim($data[$c]);
		        //upper case name and surname
		        if ($c==0 || $c==1) {
		        	$data[$c] = ucwords(strtolower($data[$c]));
		        }elseif($c==2){	//email lower case  
					$data[$c] = strtolower($data[$c]);
			    	//validate email data, do not insert into mysql otherwise
					if (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $data[$c])){
					  //echo 'This is a valid email. '.$data[$c];
					} else {
					  $ValidEmail='no';
					}
		        }    
		        //field of data
		    }
		    if ($ValidEmail=='yes'){
		    	echo "Successful read: ". $data[0] ." ". $data[1] ." ". $data[2] . "\n";
		    	if ($ftnDry_Run=='n'){ //inserting data
		    		ftnInsertRow($data[0],$data[1],$data[2],$ftnTable,$ftnUsername,$ftnPassword,$ftnHost,$ftnDB);
		    	}
			}else{
				echo "Successful read, invalid email: ". $data[0] ." ". $data[1] ." ". $data[2] . "\n";
			}
		}
	  }
	  fclose($handle);
	}
}//ftnReadFile($strUsersFile){

function ftnInsertRow($ftnFirstname,$ftnLastname,$ftnEmail,$ftnTable,$ftnUsername,$ftnPassword,$ftnHost,$ftnDB){
$connection = mysqli_connect($ftnHost, $ftnUsername, $ftnPassword, $ftnDB); 
if (!$connection) { 
  die("Failed ". mysqli_connect_error()); 
} 

$ftnFirstname=mysqli_real_escape_string($connection,$ftnFirstname);
$ftnLastname=mysqli_real_escape_string($connection,$ftnLastname);
$ftnEmail=mysqli_real_escape_string($connection,$ftnEmail);
$query = "INSERT INTO users (name, surname, email) VALUES ('$ftnFirstname', '$ftnLastname', '$ftnEmail')";

if (mysqli_query($connection, $query)) {
  echo "Successfully insert: $ftnFirstname $ftnLastname $ftnEmail \n";
} else {
  echo "Error: " . $query . "\n" . mysqli_error($connection). "\n";
}
//close the connection 
mysqli_close($connection); 
}//function ftnInsertRow()




?>

