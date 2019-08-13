<?php
    session_start();

    function Redirect($url, $dur) {
        header("refresh:$dur; url=$url");
    }

    $html = "
        <html>
        <head>
            <meta charset=\"utf-8\" />
            <title>Welcome</title>
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
            <div class='background-div'>
                <div class='description-div'>
                    <h1 class='description' style='font-size: 50px'>Enjoy the film in our best cinemas!</h1>
                    <h3 class='description' style='font-size: 30px'>Simply Book</h3>
                </div>
            </div>
        </body>
        
        <style>
            .description-div {
                width: 100%;
                justify-content: center;
                align-items: center;
                background-color: rgba(0,0,0,0.75);
            }
            .description {
                color: white;
                font-weight: 300;
                margin : 5px;
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
            .background-div {
                height: 93%;
                width: 100%;
                background: url(images/theater1.jpg) no-repeat center center fixed;
                background-size: cover; 
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
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
                .username-div {
                    padding-top: 150px;
                }
            }
            
        </style>
        
        </html>
    ";

    if(isset($_SESSION['userId'])) {
        print $html;
    }
    else {
        print "<h1>You have not logged in!</h1>";
        Redirect("index.html", 3);
    }
?>


