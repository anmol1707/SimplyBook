<?php

    session_start();

    $conn=mysqli_connect("sophia.cs.hku.hk", "agupta", "anmol17", "agupta") or die ("Error! ".mysqli_connect_error());


    function createAlertAndRedirect($message, $url, $dur) {
            print "<script type='text/javascript'>alert('$message');</script>";
            header("refresh:$dur; url=$url");
        }

    if(isset($_SESSION['userId'])) {
        if(isset($_POST['comment-film']) && strlen($_POST['comment-message']) != 0) {
            $query = "insert into CommentTable values(NULL,'".$_POST['comment-film']."','".$_SESSION['userId']."','".$_POST['comment-message']."')";
            $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
            print "<h1>Your comment has been submitted.</h1>";
            sleep(3);
            $url = "comment.php";
            print '<script>window.location.href="'.$url.'"</script>';
        }
        else {
            createAlertAndRedirect("Failed to submit comment!", "comment.php", 3);
        }
    }
    else {
        print "<h1>You have not logged in!";
        $url = "index.html";
        header("refresh:3; url=$url");
    }

    mysqli_free_result($result);
    mysqli_close($conn);

?>