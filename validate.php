<?php
    include ('connect.php');
    
    //URL for validation
    $rooturl = 'http://localhost/q/';
    $url = $rooturl.'validate.php';

    $bodycontents = '';


    if (isset($_POST['submit'])){
        //print $_POST['submit'];
        if ($_POST['submit'] == 1){
            // Accept
            $sql = "UPDATE `vocher` SET `status` = '0' WHERE `vocher`.`vkey` = '".$_GET['vocherid']."';";
            $results = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));

        }elseif ($_POST['submit'] == 2){
            // Decline
            $sql = "UPDATE `vocher` SET `status` = '2' WHERE `vocher`.`vkey` = '".$_GET['vocherid']."';";
            $results = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));

        }elseif ($_POST['submit'] == 3){
            // Delete
            $sql = "DELETE FROM vocher WHERE `vocher`.`vkey` = '".$_GET['vocherid']."';";
            $results = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
            //header("Location: $calendar_url");

        }


    }else{
        //print "Not Set";
    }
    
    
    if (isset($_GET['vocherid'])){
        $vocherID = $_GET['vocherid'];

        $sqlEvents = "SELECT vkey FROM vocher WHERE `status` = 1;";
        $resultset = mysqli_query($conn, $sqlEvents) or die("database error:". mysqli_error($conn));

        $stat = false;

        while( $rows = mysqli_fetch_assoc($resultset) ) {
            if ($rows['vkey'] == $vocherID) {
                //Match
                $stat = true;
                $vkey = $rows['vkey'];
            }
        }

        if ($stat == true){
            
            $bodycontents = $vkey.'<br>
            <form method="POST" action="'.$url.'?vocherid='. $vocherID.'">
                <button type="submit" id="fas" name="submit" value="1">Accept</button>
                <button type="submit" id="fas" name="submit" value="2">Decline</button>
                <button type="submit" id="fas" name="submit" value="3">Delete</button>
            </form>';
            $bodycontents .= "Your vocher code is valid!";
        }else{
            $bodycontents .= "Your vocher code is invalid!";
        }

    }else{
        // NO Vocher ID
        $bodycontents .= 'No Vocher ID';
    }
    
?>
<div align="center">
    <h1>Vocher Validator</h1><br>
    <?php print $bodycontents; ?>
</div>