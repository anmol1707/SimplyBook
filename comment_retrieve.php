<?php

    session_start();

    $conn=mysqli_connect("sophia.cs.hku.hk", "agupta", "anmol17", "agupta") or die ("Error! ".mysqli_connect_error());


    function Redirect($url, $dur) {
        header("refresh:$dur; url=$url");
    }

    if(isset($_SESSION['userId'])) {
        $query = 'select * from CommentTable where filmId="'.$_GET['filmId'].'"';
        $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));

        while($row = mysqli_fetch_array($result)) {
            print '<div style="width: 100%;" id="'.$row['commentId'].'">';
                print "<h2 style='text-align: center'>Viewer: ".$row['userId']."</h2>";
                print "<h3 style='text-align: center'>Comment: ".$row['userComment']."</h3>";
                print "<hr/>";
            print "</div>";
        }
    }
    else {
        print "<h1>You have not logged in!</h1>";
        Redirect("index.html", 3);
    }

    mysqli_free_result($result);
    mysqli_close($conn);

?>