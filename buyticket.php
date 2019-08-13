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

    if(isset($_SESSION['userId'])) {

        $conn=mysqli_connect("sophia.cs.hku.hk", "agupta", "anmol17", "agupta") or die ("Error! ".mysqli_connect_error());

        $query = 'select * from BroadcastTable,FilmTable,HouseTable where broadcastId='.$_POST['broadcast_selection'].' and BroadcastTable.filmId=FilmTable.filmId and BroadcastTable.houseId=HouseTable.houseId';
        $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));

        $row = mysqli_fetch_array($result);

        $html = "

            <html>
            <head>
                <meta charset=\"utf-8\" />
                <title>Comments Page</title>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            </head>
            <body>
                <div class='main-div'>
                   <h1>Cart</h1>
                    <table style='color: white'>
                        <tr>
                            <td>Cinema:</td>
                            <td>US</td>
                        </tr>
                        <tr>
                            <td>House:</td>
                            <td>".$row['houseId']."</td>
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
                            <td>".date_format(date_create($row['Dates']), "d/m/Y (D) H:i")."</td>
                        </tr>
                    </table>
                </div>              
            </body>
            
            <style>
                table {
                    width: 100%;
                }
                table, tr,td {
                    border-bottom: 1px solid rgba(255,255,255, 0.2);
                    border-collapse: collapse;
                }
                td {
                    font-size: 26px;                
                }
                td ~ td {
                    font-size: 20px;
                }
                .main-div {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    padding: 20px;
                    width: 500px;
                    margin: auto;
                    background-color: rgba(0,0,0,0.75);
                    color: white;
                    border-top-right-radius: 10px;
                    border-top-left-radius: 10px;
                    margin-top: 100px;
                }
                .submit-div {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    padding: 20px;
                    width: 500px;
                    margin: auto;
                    background-color: rgba(0,0,0,0.75);
                    color: white;
                    border-bottom-right-radius: 10px;
                    border-bottom-left-radius: 10px;   
                    margin-bottom: 100px;
                }
                .button {
                    margin: 5px;
                    width: 100%;
                    height: 60px;
                    cursor: pointer;
                    font-size: 20px;
                }
                .buttons-div {
                    display: flex; 
                    flex-direction: row; 
                    width: 100%
                }
                .select-div {
                   display: flex;
                   flex-direction: column;
                   width: 500px;
                   justify-content: center;
                    align-items: center;
                }
                select {
                    background: rgba(211,211,211, 1);
                    width: 300px;
                    height: 30px;
                    font-size: 16px;
                    white-space: normal;
                    border-radius: 10px;
                    margin: 10px;
                }
                html {
                    background: url(images/theater.jpg) no-repeat center center fixed;
                }
                @media only screen and (max-width: 600px) {
                    .main-div {
                        width: 80%;
                    }
                    .submit-div {
                        width: 80%;
                    }
                    .select-div {
                        width: 250px;
                    }
                    select {
                        width: 175px;
                    }
                }
            </style>
            
            </html>
        ";

        print $html;


        print "<div class='submit-div'>";
            print "<form action='confirm.php' style='width: 100%; display: flex; align-items: center; flex-direction: column' method='post'>";
                print "<div class='select-div'>";
                    foreach ($_POST['seat-select'] as $x) {
                        $exploded = explode(',', $x);
                        print "<div>";
                            print '<label style="font-size: 24px" for="ticket-select[]">'.getRowChar($exploded[0]).($exploded[1]+1).'</label>';
                            print "<select name='ticket-select[]'>
                                    <option value='Adult'>Adult($75)</option>
                                    <option value='Student/Senior'>Student/Senior($50)</option>
                                </select>";
                        print "</div>";
                        print "<input type='hidden' name='seat-select[]' value='$x' />";
                    }
                print "</div>";
                print "<div style='display: flex; flex-direction: row; width: 100%'>";
                    print "<input style=\"color: white; background-color: rgba(255, 102, 110, 1)\" class='button' type='submit' value='Confirm' />";
                    print "<input style=\"background-color: rgba(221,221,221, 1)\" class='button' type='button' onclick=\"location.href='buywelcome.php'\" value='Cancel' />";
                print "</div>";
                print '<input type="hidden" name="broadcast_selection" value="'.$_POST['broadcast_selection'].'"/>';
            print "</form>";
        print "</div>";
    }

    else {
        print "<h1>You have not logged in!</h1>";
        Redirect("index.html", 3);
    }

?>