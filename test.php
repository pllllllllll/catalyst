<?php
//https://stackoverflow.com/questions/19159195/after-using-phps-getopt-how-can-i-tell-what-arguments-remain
echo "hello\n";
$arg_a = null; // -a=YOUR_OPTION_A_VALUE
$arg_b = null; // -b=YOUR_OPTION_A_VALUE
$arg_c = null; // -c=YOUR_OPTION_A_VALUE
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
        $arg_help = "true";
    }else if ( preg_match( '/^-h=(.*)$/', $arg, $matches ) ) {
        $arg_host = $matches[1];
    }else if ( preg_match( '/^-p=(.*)$/', $arg, $matches ) ) {
        $arg_password = $matches[1];
    }else if ( preg_match( '/^-u=(.*)$/', $arg, $matches ) ) {
        $arg_username = $matches[1];
    }else if ( preg_match( '/^-db=(.*)$/', $arg, $matches ) ) {
        $arg_db = $matches[1];
    }else if ( preg_match( '/^-file=(.*)$/', $arg, $matches ) ) {
        $arg_file = $matches[1];
    }else if ( preg_match( '/^-dry_run=(.*)$/', $arg, $matches ) ) {
        $arg_dry_run = $matches[1];
    }else if ( preg_match( '/^-create_table=(.*)$/', $arg, $matches ) ) {
        $arg_create_table = $matches[1];
    }else{
        //unrecognized
    }
}//foreach

if ( ($arg_create_table == 'y') || ($arg_dry_run == 'n') )    {  //db info required
	if ( $arg_host === null )    { 
		$arg_host = readline('1Host is required : ');
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
}
if ( $arg_dry_run == 'n' )    { 
	//read file
}
if ( ISSET($arg_help))    { /* missing a - do sth here */ 
	//display help options
	echo "--file [csv file name] – this is the name of the CSV to be parsed\n--create_table – this will cause the MySQL users table to be built (and no further
 action will be taken)\n--dry_run – this will be used with the --file directive in case we want to run the script but not
insert into the DB. All other functions will be executed, but the database won't be altered\n-u – MySQL username\n-p – MySQL password\n-h – MySQL host\n--help – which will output the above list of directives with details.";
}

?>