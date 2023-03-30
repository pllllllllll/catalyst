<?php
//C:\xampp\php\php.exe C:\xampp\htdocs\catalyst\catalyst\logictest.php  
for ($c=1; $c < 101; $c++) {
$three = $c/3;
echo "$c ";
if (is_float($three)) {
  //echo "$c/3 = $three is a decimal number\n";
} else {
  echo "foo";
}

$five = $c/5;
if (is_float($five)) {
  //echo "$c/5 = $five is a decimal number\n";
} else {
  echo "bar";
}


}
?>