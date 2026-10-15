<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html> 
<html>
 <head>
    <title> lab 1 </title>
</head>
<body>

<?php

 //Task1.1
 echo "Task 1.1 </br>";
 $k=8;
 $x=2;
 $l=1/(exp(-$k*$x+0.3))*((log10(abs($k+$x))-sqrt(pow(sin($x),4)))/((atan(($x+1)/($x-$k)))+(M_PI/8)*log(M_PI))+3);
 echo $l. "</br>";

//Task1.2
 echo "Task 1.2 </br>";
 $a=4;
 $b=2;
 $result=($a/$b<=$a+$b)&&($a+$b<$a*$b);
 var_dump($result);
 echo "</br>";

 //Task2.1
 echo "Task 2.1 </br>";
 $lambda=0.63;
 $n=exp(-$lambda)+pow($lambda, 2);
 $b=asin(1/($n+2))+ log(abs($n));
 echo $b;
?>

</body>
</html>