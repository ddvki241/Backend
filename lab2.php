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
  //Task 1
  echo "Task 1 </br>";
    $x=1.3;
    $y=null;
    if($x>0 && $x<M_PI/2){
        $y=tan($x);
    } else if($x<=0 || $x>=M_PI/2){
        $y=0;
    }
    echo ($y ?? 'no value')."<br>";

    //Task 2.1
    echo "Task 2.1 </br>";
    for($x=0;$x<=4;$x+=0.5){
        $y=(2*$x+1)/sqrt($x+5);
        echo $y."<br>";
    }

    //Task 2.2
    echo "Task 2.2 </br>";
    $n=5;
    $x=2;
    while($n>0){
      $y=(2*$x+1)/sqrt($x+5);
      echo $y."<br>";
      $n--;
      $x+=0.3;
    }

    //Task 3
    echo "Task 3 </br>";
    $S=0;
    for($l=3;$l<=12;$l++){
        $S+=pow(-1,$l + 1)*(2*$l+1)/($l*$l+2);
    }
    echo $S."<br>";
    $p=1;
    $n=8;
    for($k=2;$k<=$n;$k++){
        $p*=($k+5)/(($k+1)*($k+9));
    }
    echo $p."<br>";

    //Task 4
    echo "Task 4 </br>";
    $K=array(-1.1, 2.2, -3.3, 4.4, -5.5, 6.6, -7.7, 8.8);
    $SumOfPos=0;
    foreach ($K as $value) {
    if ($value > 0) {
        $SumOfPos += $value; 
        }
    }
    echo "Сума додатних елементів: " . $SumOfPos;
?>

</body>
</html>