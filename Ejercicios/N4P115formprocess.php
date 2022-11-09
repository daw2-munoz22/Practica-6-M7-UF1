<?php
    if($_POST){
        $num1 =  $_POST['name1'];
        $num2 =  $_POST['name2'];
        $num3 =  $_POST['name3'];
    }
    $suma = $num1 + $num2 + $num3;
    echo "La suma de: ".$num1. " + ".$num2. " + ". $num3. " es:  ". $suma;
?>