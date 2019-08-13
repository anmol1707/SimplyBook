<?php

    session_start();

    function getRowChar($num) {
        $result = "";
        if($num == 0) {
            $result = "A";
        }
        while($num / 26 > 0) {
            $rem = $num % 26;
            $result = chr(($rem + 65)).$result;
            $num = floor ($num / 26);
        }

        return $result;
    }

    function Redirect($url, $dur) {
        header("refresh:$dur; url=$url");
    }

    $conn=mysqli_connect("sophia.cs.hku.hk", "agupta", "anmol17", "agupta") or die ("Error! ".mysqli_connect_error());


    if(isset($_SESSION['userId'])) {

        $query = 'select * from BroadcastTable,FilmTable,HouseTable where broadcastId='.$_POST['broadcast_selection'].' and BroadcastTable.filmId=FilmTable.filmId and BroadcastTable.houseId=HouseTable.houseId';
        $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));

        $html = "
            <html>
            <head>
                <meta charset=\"utf-8\" />
                <title>Purchase Summary</title>
                <link rel=\"stylesheet\" type=\"text/css\" href=\"mainphp.css\">
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
                
                <h1 class='heading'>Order Information</h1>
            </body>
            
            <style>
                table {
                    width: 100%;
                }
                table, tr,td {
                    border-bottom: 1px solid rgba(0,0,0, 0.2);
                    border-collapse: collapse;
                }
                td {
                    font-size: 26px;                
                }
                td ~ td {
                    font-size: 20px;
                }
                .main-div {
                    margin: auto;
                    width: 500px;
                    border: solid 2px black;
                    border-radius: 20px;
                    padding: 20px;
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
                .heading {
                    padding-top: 100px;
                    text-align: center;
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
                    .heading {
                        padding-top: 175px;
                    }
                    .main-div {
                        width: 80%;
                    }
                }
            
            </style>
            
            </html>
        ";

        print $html;

        $row = mysqli_fetch_array($result);

        $total = 0;

        print "<div class='main-div'>";

            foreach ($_POST['seat-select'] as $index => $x) {

                $exploded = explode(',', $x);

                $ticketType = $_POST['ticket-select'][$index] == 'Adult' ? "Adult" : "Student/ Senior";
                $ticketVal = $_POST['ticket-select'][$index] == 'Adult' ? 75: 50;
                $ticketFee = "$".$ticketVal."(".$ticketType.")";
                $total = $total + $ticketVal;

                $query1 = 'insert into TicketTable values(NULL,"'.getRowChar($exploded[0]).($exploded[1]+1).'","'.$_POST['broadcast_selection'].'",FALSE,"'.$_SESSION['userId'].'","'.$ticketType.'","'.$ticketVal.'")';
                $result1 = mysqli_query($conn, $query1) or die ('Failed to query '.mysqli_error($conn));

                $query2 = 'insert into BookedSeatsTable values("'.$row['houseId'].'","'.$exploded[0].'","'.$exploded[1].'","'.$_POST['broadcast_selection'].'")';
                $result2 = mysqli_query($conn, $query2) or die ('Failed to query '.mysqli_error($conn));

                print "
                    <table>
                        <tr>
                            <td>Cinema:</td>
                            <td>US</td>
                        </tr>
                        <tr>
                            <td>House:</td>
                            <td>".$row['houseId']."</td>
                        </tr>
                        <tr>
                            <td>SeatNo:</td>
                            <td>".getRowChar($exploded[0]).($exploded[1]+1)."</td>
                        </tr>
                        <tr>
                            <td>Film:</td>
                            <td>".$row['filmName']."</td>
                        </tr>
                        <tr>
                            <td>Category:</td>
                            <td>".$row['category']."</td>
                        </tr>
                        <tr>
                            <td>Show Time:</td>
                            <td>".$row['Dates']."</td>
                        </tr>
                        <tr>
                            <td>Ticket Fee:</td>
                            <td>".$ticketFee."</td>
                        </tr>
                    </table>
                    <br/>
                ";
            }

            print "<p style='text-align: end; font-size: 30px; margin: 0px'>Total fee: $ ".$total."</p>";
            print "<hr/>";
            print "<p style='font-size: 20px;'>Please present valid proof of age/status when purchasing Student or Senior tickets before entering the cinema house.</p>";
            print "<div style='display: flex; justify-content: center'>";
                print "<input class='header-button' style='height: 30px; width: 75px; font-size: 20px;' onclick='location.href=\"buywelcome.php\"' type='button' value='OK' />";
            print "<div>";
        print "</div>";

    }
    else {
        print "<h1>You have not logged in!</h1>";
        Redirect("index.html", 3);
    }


    mysqli_free_result($result);
    mysqli_close($conn);

?>