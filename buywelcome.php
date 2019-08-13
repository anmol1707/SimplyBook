<?php
    session_start();

    $conn=mysqli_connect("sophia.cs.hku.hk", "agupta", "anmol17", "agupta") or die ("Error! ".mysqli_connect_error());

    function Redirect($url, $dur) {
        header("refresh:$dur; url=$url");
    }

    $headerHtml = "
        <html>
        <head>
            <meta charset=\"utf-8\" />
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Movie Options</title>
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
            <div class='background-div' />
        </body>
        
        <style>
            .movies-div {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                overflow-wrap: break-word;
                justify-content: center;
                width: 100%;
                padding-top: 100px;
            }
            .movie {
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 400px;
                padding: 30px;
                border: solid 2px black;
                border-radius: 15px;
                margin: 20px;
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
            .submit-button {
                background: linear-gradient(rgba(211,211,211, 0.5), rgba(211,211,211, 0.1));
                width: 350px;
                height: 30px;
                font-size: 16px;
                white-space: normal;
                border-radius: 10px;
                margin: 10px;
            }
            .header-button {
                background: linear-gradient(0.45turn, red, orange);
                width: 250px;
                height: 90%;
                font-size: 30px;
                white-space: normal;
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
                .movies-div {
                    padding-top: 150px;
                }
            }
            
        </style>
        
        </html>
    ";

    if(isset($_SESSION['userId'])) {

        print $headerHtml;

        $query = 'select * from FilmTable';
        $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));


        print "<div class='movies-div'>";
            while($row = mysqli_fetch_array($result)) {
                print "<div class='movie'>";
                    print "<h1>".$row['filmName']."</h1>";
                    print "<img src='./images/".$row['filmId']."' style='height: 500px; width: 100%' />";
                    print "<h3>".$row['description']."</h3>";
                    print "<h4>".$row['director']."</h4>";
                    print "<h4>".$row['duration']."</h4>";
                    print "<h4>".$row['category']."</h4>";
                    print "<h4>".$row['language']."</h4>";
                    print "<form style='display: flex; flex-direction: column' method='post' action='seatplantry.php'>";
                        print "<select class='submit-button' name='broadcast_selection'>";
                            $query1 = 'select * from BroadcastTable where filmId="'.$row['filmId'].'"';
                            $result1 = mysqli_query($conn, $query1) or die ('Failed to query '.mysqli_error($conn));
                            while($row1 = mysqli_fetch_array($result1)) {
                                print "<option value=".$row1['broadcastId'].">".date_format(date_create($row1['Dates']), "d/m/Y (D) H:i")." ".$row1['day']." ".$row1['houseId']."</option>";
                            }
                        print "</select>";
                        print "<input class='submit-button' type='submit' value='Submit'/>";
                    print "</form>";
                print "</div>";
            }
        print "</div>";

    }
    else {
        print "<h1>You have not logged in!</h1>";
        Redirect("index.html", 3);
    }
?>


