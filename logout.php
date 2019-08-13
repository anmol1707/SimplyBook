<?php

    session_start();

    function Redirect($url, $dur) {
        header("refresh:$dur; url=$url");
    }

    if(isset($_SESSION['userId'])) {
        session_unset();
        session_destroy();
        print "<h2>Logging out</h2>";
    }
    else {
        print "<h2>You are not logged in!</h2>";
    }
    Redirect("index.html", 3);
?>