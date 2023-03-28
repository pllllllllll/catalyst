browser http://localhost/cat/cat/hello.php

C:\xampp\htdocs\cat\hello.html
https://github.com/pllllllllll/cat.git




<?php
//https://www.geeksforgeeks.org/how-to-read-user-or-console-input-in-php/
//command line prompt user for table name
$a = readline('Is this a dry run? (y/n)');
if ($a=='n'){ //proceed as normal
	$b = readline('Enter a table name: ');
	$c = readline('Enter a mysql username: ');
	$d = readline('Enter a mysql password: ');
}elseif($a=='y'){//no db insert
	$b = readline('Enter a table name: ');
}else{
	echo "Answer must be 'y' or 'n' , please try again.\n";	
	exit();
}


echo "this is the table name  $a\n";
echo "this is the username  $b\n";
echo "this is the password  $c\n";

$options = ['file:'];
$values = getopt(null, $options);
//$lines = file($values['file'], FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
// Rest of the script...

$row = 1;
if (($handle = fopen($values['file'], "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    echo "\n $num fields in line $row: \n";
    $row++;

    //validate number of fields is 3
    if ($num<>3) {
    	//format incorrect for line
    	echo "\nNumber of fields ($num) not 3 for line $row";
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
				  echo 'Invalid email.'.$data[$c];
				}

	        }    
	        //email format
	        echo $data[$c] . "\n";
	    }
	}
  }
  fclose($handle);
}

?>

================================================
//https://www.php.net/manual/en/function.file.php
//$lines = file('http://www.example.com/');
foreach ($lines as $line_num => $line) {
    echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "\n";
    //process each line
    $str_arr = explode (",", $line); 
}
// Using the optional flags parameter
$trimmed = file($values['file'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);



XAMPP does not have a pre build console to run php or mysql commands, so, you have to add to windows PATH environment variables, these 2: ;C:\xampp\mysql\bin;C:\xampp\php;

Then you should be able to execute php and mysql commands from the CMD.
Control Panel\System and Security\System > Advanced system settings > Environment Variables

php <path to file location>
example:
C:\xampp\php\php.exe C:\xampp\htdocs\cat\cat\hello.php  --file=C:\xampp\htdocs\cat\cat\users.csv 

test 2
https://stackoverflow.com/questions/64552719/how-to-pass-csv-file-as-input-through-command-line
Here you have to pass to your script the name of your csv file. In this case the name is 'users.csv' so you would execute your script like this php script.php --file=users.csv.


Just use the function for parsing a CSV file

http://php.net/manual/en/function.fgetcsv.php

$row = 1;
if (($handle = fopen("test.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    echo "<p> $num fields in line $row: <br /></p>\n";
    $row++;
    for ($c=0; $c < $num; $c++) {
        echo $data[$c] . "<br />\n";
    }
  }
  fclose($handle);
}
?>
    $csvFile = file('../somefile.csv');
    $data = [];
    foreach ($csvFile as $line) {
        $data[] = str_getcsv($line);
    }
