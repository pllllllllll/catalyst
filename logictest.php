<?php
//C:\xampp\php\php.exe C:\xampp\htdocs\catalyst\catalyst\logictest.php  
for ($c=1; $c < 101; $c++) {
$three = $c/3;
if (is_float($three)) {
  echo '$three is a decimal number';
} else {
  echo '$three is an integer';
}

}
?>