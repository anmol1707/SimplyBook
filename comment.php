<?php
    session_start();

    $conn=mysqli_connect("sophia.cs.hku.hk", "agupta", "anmol17", "agupta") or die ("Error! ".mysqli_connect_error());

    function Redirect($url, $dur) {
        header("refresh:$dur; url=$url");
    }

$html = "
        <html>
        <head>
            <meta charset=\"utf-8\" />
            <title>Comments Page</title>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        </head>
    
        <body>
            <div class='header-div'>
                <input class='header-button header-left-button' onclick=\"location.href='buywelcome.php'\" type=\"button\" value=\"Buy a ticket\"/>
                <div class='header-right-div'>
                    <input class='header-button' onclick=\"location.href='comment.php'\" type=\"button\" value=\"Movie Review\"/>
                    <input class='header-button' onclick=\"location.href='history.php'\" type=\"button\" value=\"Purchase History\"/>
                    <input class='header-button' onclick=\"location.href='logout.php'\" type=\"button\" value=\"Logout\"/>      
                </div>      
            </div>
        </body>
        
        <style>

            .heading {
                text-align: center;
            }
            .header-right-div {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: flex-end;
            }
            .header-div {
                background-color: rgba(241, 241, 241, 1);
                height: 75px;
                display: flex;
                flex-direction: row;
                padding: 0px 1%;
                align-items: center;
                position: fixed;
                width: 100%;
            }
            body {
                margin: 0;
            }
            .header-button {
                background: linear-gradient(0.45turn, red, orange);
                width: 250px;
                height: 90%;
                font-size: 30px;
                white-space: normal;
            }
        
        
            .login-buttons {
                margin: 5px;
                width: 100%;
                height: 60px;
                cursor: pointer;
                font-size: 25px;
            }

            .login-buttons-div {
                width: 70%;
                display: flex;
                flex-direction: row;
                height: auto;
                align-self: center;
            }
            .main-div {
                display: flex;
                flex-direction: column;
                width: 800px;
                margin: auto;
                padding-top: 100px;
            }
            .film-select-div {
                display: flex;
                flex-direction: row;
                justify-content: center;
                margin : 10px;
                align-items: center;
            }
            .comments-div {
                width :800px;
                display: flex;
                align-items: center;
                margin-left: auto;
                margin-right: auto;
                flex-direction: column;
            }
            
            @media only screen and (max-width: 1000px) {
                .header-button {
                    width: 150px;
                    font-size: 25px;
                }
            }
            @media only screen and (max-width: 600px) {
                .header-div {
                    align-items: center;
                    justify-content: center;
                    flex-direction: column;
                    height: 150px;
                }
                .header-right-div {
                    justify-content: center;
                }
                .header-button {
                    font-size: 18px;
                    width: 33%;
                }
                .header-left-button {
                    width: 80%;
                    font-size: 30px;
                }
                .main-div {
                    padding-top: 175px;
                }
            }
            
        </style>
        
        </html>
    ";

    if(isset($_SESSION['userId'])) {
        print $html;

        $query = 'select * from FilmTable';
        $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));

        print "<form method='post' name='comment-form' action='comment_submit.php'>";
            print "<div class='main-div'>";
                print "<div class='film-select-div'>";
                    print "<label style='font-size: 30px; margin: 0px 10px;'>Film Name: </label>";
                    print "<select style='width: 400px; height: 50px; font-size: 25px;' id='comment-film' name='comment-film'>";
                    while($row = mysqli_fetch_array($result)) {
                        print '<option value="'.$row['filmId'].'">'.$row['filmName'].'</option>';
                    }
                    print "</select>";
                print "</div>";
                print "<textarea style='font-size: 20px; padding : 10px;' id='comment' rows='20' cols='80' name='comment-message' placeholder='Please input comment here'></textarea>";
                print "<div class='login-buttons-div'>";
                    print "<input style='color: white; background-color: rgba(255, 102, 110, 1)' class='login-buttons' type='button' value='View comment' onclick='getComments()' />";
                    print "<input style='background-color: rgba(221,221,221, 1)' class='login-buttons' type='submit' value='Submit comment' onclick='validateMessage(event)' />";
                print "</div>";
            print "</div>";
            print "<div class='comments-div' id='comments'>";
            print "</div>";
        print "</form>";
    }
    else {
        print "<h1>You have not logged in!</h1>";
        Redirect("index.html", 3);
    }

?>

<script>

    function validateMessage(e) {
        var message = document.getElementById('comment').value;
        if(message.length === 0) {
            alert('Enter a comment before submitting!');
            e.preventDefault();
        }
    }

    function getComments() {
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var mesgs = document.getElementById("comments");
                mesgs.innerHTML = xmlhttp.responseText;
            }
        };

        var selectedFilm = document.getElementById('comment-film').value;
        xmlhttp.open("GET", "comment_retrieve.php?filmId=" + selectedFilm.toString(), true);
        xmlhttp.send();
    }
</script>

