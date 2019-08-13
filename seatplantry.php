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

    $conn=mysqli_connect("sophia.cs.hku.hk", "agupta", "anmol17", "agupta") or die ("Error! ".mysqli_connect_error());


    function Redirect($url, $dur) {
        header( "refresh:$dur; url=$url");
    }

    if(isset($_SESSION['userId'])) {

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
                        <h1>Ticketing</h1>
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
                        width: 550px;
                        margin: auto;
                        background-color: rgba(0,0,0,0.75);
                        color: white;
                        border-radius: 10px;
                        margin-top: 100px;
                    }
                    .seat-select-div {
                        display: flex;
                        flex-direction: column;
                        width: 700px;
                        margin: auto;
                        margin-bottom: 100px;
                        margin-top: 50px;
                        align-items: center;
                        justify-content: center;
                        padding: 20px;
                        background-color: rgba(0,0,0,0.75);
                        color: white;
                        border-radius: 10px;
                    }
                    .seat-row-div {
                        display: flex;
                        flex-direction: row;
                        width: 100%;
                        justify-content: center;
                    }
                    .button {
                        margin: 5px;
                        width: 100%;
                        height: 60px;
                        cursor: pointer;
                        font-size: 20px;
                    }
                    .seat {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        width: 80%;
                        height: 60px;
                        margin: 2px;
                        border-radius: 5px;
                        background: linear-gradient(rgba(0,100,0,1), rgba(0,100,0,0.5));
                    }
                    .selected-seat:hover {
                        background-color: rgba(0,175,0,1);
                        cursor: pointer;
                    }
                    .selected-seat:active {
                        background-color: rgba(0,255,0,1);
                        cursor: pointer;
                    }
                    p {
                        margin: 0px;
                        padding: 0px;
                    }
                    .screen {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 10px 0px;
                        font-size: 30px;
                        height: 50px;
                        width: 100%;
                        text-align: center;
                        background: linear-gradient(90turn, rgba(26,57,100, 1), rgba(26,57,100, 0.5));
                    }
                    html {
                        background: url(images/theater.jpg) no-repeat center center fixed;
                    }
                    
                    @media only screen and (max-width: 800px) {
                        .main-div {
                            width: 70%;
                        }
                        .seat-select-div {
                            width: 80%;
                        } 
                    }
                </style>
                
                <script>
                    function toggleSelectedSeat(clickedItem) {
                        var checkbox = clickedItem.childNodes;
                        checkbox[1].checked = checkbox[1].checked ? false : true;
                        
                        clickedItem.style.background = checkbox[1].checked ? 'linear-gradient(rgba(0,0,100,1), rgba(0,0,100,0.5))' : null;
                    }
                </script>
                
            </html>
        ";

        $query1 = 'select * from BookedSeatsTable where houseId="'.$row['houseId'].'" and broadcastId="'.$_POST['broadcast_selection'].'"';
        $result1 = mysqli_query($conn, $query1) or die ('Failed to query '.mysqli_error($conn));

        $array = array_fill(0, $row['houseRow'], array_fill(0, $row['houseCol'], 0));

        while($row1 = mysqli_fetch_array($result1)) {
            $array[$row1['rowNumber']][$row1['columnNumber']] = 1;
        }

        print $html;
        print "<form name='seat-selection-form' method='post' action='buyticket.php'>";
            print "<div class='seat-select-div'>";
                for($i = $row['houseRow'] - 1; $i >= 0; $i--) {
                    print "<div class='seat-row-div'>";

                    for($j = 0; $j < $row['houseCol']; $j++) {
                        if($array[$i][$j] == 0) {
                            print "<div onclick='toggleSelectedSeat(this)' class='seat selected-seat'>";
                                print "<p>" .getRowChar($i).($j+1)."</p>";
                                print "<input style='display: none' type='checkbox' name='seat-select[]' value='$i,$j'/>";
                            print "</div>";
                        }
                        else {
                            print "<div style='background: linear-gradient(rgba(255,0,0,1), rgba(255,0,0,0.5))' class='seat'>";
                                print "<p style='text-align: center'>".getRowChar($i).($j+1) ." </br>Sold</p>";
                            print "</div>";
                        }
                    }
                    print "</div>";
                }
                print "<p class='screen'>SCREEN</p>";
                print "<div style='display: flex; flex-direction: row; width: 100%;'>";
                    print "<input style=\"color: white; background-color: rgba(255, 102, 110, 1)\" class='button' type='submit' value='Submit' onclick='checkSeatSelect(event)'/>";
                    print "<input style=\"background-color: rgba(221,221,221, 1)\" class='button' type='button' onclick=\"location.href='buywelcome.php'\" value='Cancel'/>";
                print "</div>";
                print '<input type="hidden" name="broadcast_selection" value="'.$_POST['broadcast_selection'].'"/>';
            print "</div>";
        print "</form>";
    }
    else {
        print "<h1>You have not logged in!</h1>";
        Redirect("index.html", 3);
    }

?>

<script>
    function checkSeatSelect(e) {
        var checkboxes = document.getElementsByName('seat-select[]');
        for(var i = 0; i < checkboxes.length; i++) {
            if(checkboxes[i].checked) {
                return;
            }
        }
        alert('Please select at least one seat!');
        e.preventDefault();
    }
</script>
