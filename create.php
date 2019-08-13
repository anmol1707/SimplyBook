<?php
    session_start();
    function Redirect($url, $dur) {
        header("refresh:$dur; url=$url");
    }

    $conn=mysqli_connect("sophia.cs.hku.hk", "agupta", "anmol17", "agupta") or die ("Error! ".mysqli_connect_error());

    $query = 'select * from LoginTable where userId="'.$_POST['username'].'"';
    $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));

    $found = mysqli_num_rows($result);

    if($found != 0) {
        print "<h1>Account already exists!</h1>";
        Redirect("createaccount.html", 3);
    }

    else {
        print "<h1>Account created, Welcome!</h1>";
        Redirect("index.html", 3);
        $query1 = "insert into LoginTable values('".$_POST['username']."','".$_POST['password']."')";
        $result1 = mysqli_query($conn, $query1) or die ('Failed to query '.mysqli_error($conn));
        mysqli_free_result($result1);
    }
    mysqli_free_result($result);
    mysqli_close($conn);

?>