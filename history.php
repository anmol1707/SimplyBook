<?php

    session_start();

    function Redirect($url, $dur) {
        header("refresh:$dur; url=$url");
    }

    $conn=mysqli_connect("sophia.cs.hku.hk", "agupta", "anmol17", "agupta") or die ("Error! ".mysqli_connect_error());


$html = "
        <html>
        <head>
            <meta charset=\"utf-8\" />
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Purchase History</title>
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
                
            <div class='username-div'>
                <h1 class='heading'>Purchase History</h1>
                <h3 class='heading'>".$_SESSION['userId']."</h3>
            </div>           

            
        </body>
        
        <style>
            .ticket-table {
                margin: 10px;
                padding: 10px;
                width: 400px;
                border: solid 2px black;
                border-radius: 5px;
            }
            .ticket-div {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                overflow-wrap: break-word;
                justify-content: center;
                width: 100%;
            }
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
            .username-div {
                padding-top: 100px;
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

        $query = 'select * from TicketTable, BroadcastTable, FilmTable where TicketTable.userId="'.$_SESSION['userId'].'" and TicketTable.broadcastId=BroadcastTable.broadcastId and BroadcastTable.filmId=FilmTable.filmId';
        $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));

        print "<div class='ticket-div'>";
            while($row = mysqli_fetch_array($result)) {
                    print "<table frame='box' class='ticket-table'>";
                        print "<tr><td>TicketId: </td><td>".$row['ticketId']." $".$row['ticketFee']."(".$row['ticketType'].")</td></tr>";
                        print "<tr><td>House: </td><td>".substr($row['houseId'], 6)."</td></tr>";
                        print "<tr><td>Seat: </td><td>".$row['seatNo']."</td></tr>";
                        print "<tr><td>FilmName: </td><td>".$row['filmName']." ".$row['duration']."</td></tr>";
                        print "<tr><td>Language: </td><td>".$row['language']."</td></tr>";
                        print "<tr><td>Date: </td><td>".date_format(date_create($row['Dates']), "d/m/Y (D) H:i")."</td></tr>";
                    print "<table>";
            }
        print "</div>";
    }
    else {
        print "<h1>You have not logged in!</h1>";
        Redirect("index.html", 3);
    }

    mysqli_close($conn);
    mysqli_free_result($result);

?>