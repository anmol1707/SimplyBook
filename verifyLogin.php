<?php
    session_start();

    function Redirect($url, $dur) {
        header("refresh:$dur; url=$url");
    }

    $conn=mysqli_connect("sophia.cs.hku.hk", "agupta", "anmol17", "agupta") or die ("Error! ".mysqli_connect_error());

    $query = 'select * from LoginTable where userId="'.$_POST['username'].'" and pass="'.$_POST['password'].'"';
    $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));

    $found = mysqli_num_rows($result);

    if($found != 0) {
        $_SESSION['userId'] = $_POST['username'];
        $url = "main.php";
        print '<script>window.location.href="'.$url.'"</script>';
    }
    else {
        print "<h1>Invalid login, please login again.</h1>";
        Redirect("index.html", 3);
    }

    mysqli_free_result($result);
    mysqli_close($conn);

?>