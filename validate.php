<?php
    include 'connect.php';
    
    $bodycontents = '';

    function formatCode($n){
        //xxxxx-xxxxx-xxxxx-xxxxx-xxxxx
        return vsprintf('%s%s%s%s%s-%s%s%s%s%s-%s%s%s%s%s-%s%s%s%s%s-%s%s%s%s%s', str_split($n));
    }

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

        }elseif ($_POST['submit'] == 4){
            // Manual Code check
            $vocherID = $_POST['vocherid1'].$_POST['vocherid2'].$_POST['vocherid3'].$_POST['vocherid4'].$_POST['vocherid5'];
            //header("Location: $calendar_url");

        }

    }else{
        //print "Not Set";
    }    

    if (isset($_GET['vocherid'])){
        $vocherID = $_GET['vocherid'];
    }
    
    if (isset($vocherID)){
        

        $sqlEvents = "SELECT vkey, img_path, status FROM vocher";
        $resultset = mysqli_query($conn, $sqlEvents) or die("database error:". mysqli_error($conn));

        $stat = false;

        while( $rows = mysqli_fetch_assoc($resultset) ) {
            if ($rows['vkey'] == $vocherID) {
                //Match
                $vkey = $rows['vkey'];
                $voucher_status = $rows['status'];
                $image = $rows['img_path'];
                $stat == true;
                break;
            }else{
                $voucher_status='9';
            }
        }

        if ($voucher_status == 0){ // accepted
            $bodycontents = formatCode($vkey).'<br>';
            $bodycontents .= '<img src="'.$image.'" class="image" /><br/>';
            $bodycontents .= "Your vocher code is already used!";			
        }elseif ($voucher_status == 3){ // available / Downloadet
            $bodycontents = formatCode($vkey).'<br>';
            $bodycontents .= '<img src="'.$image.'" class="image" /><br/>';
            $bodycontents .= "Your vocher code is available! -- make appointment/contact <a href=\"".$homepage."\">Homepage</a>";	
        }elseif ($voucher_status == 2){ // Declined
            $bodycontents = formatCode($vkey).'<br>';
            $bodycontents .= '<img src="'.$image.'" class="image" /><br/>';
            $bodycontents .= "Your vocher was declined";
        }else{
            $bodycontents .= "Your vocher code is invalid!";
            header( "refresh:3;url=".$ValidateURL );
            $bodycontents .= '<div style="text-align:center;">You\'ll be redirected in about 3 secs. If not, click <a href="'.$ValidateURL.'">here</a>.</div>';
        }
/*
        if ($stat == true){            
            $bodycontents = formatCode($vkey).'<br>';
            $bodycontents .= '<img src="'.$image.'" class="image" /><br/>';
            $bodycontents .= "Your vocher code is valid!";
        }else{
            $bodycontents .= "Your vocher code is invalid!";
            //header( "refresh:3;url=".$ValidateURL );
            $bodycontents .= '<div style="text-align:center;">You\'ll be redirected in about 3 secs. If not, click <a href="'.$ValidateURL.'">here</a>.</div>';
        }
*/
    }else{
        // NO Vocher ID
        $bodycontents .= 'No Vocher ID<br>
                        Please scan the QR-Code on your voucher or enter your code here:<br>
                        <form method="POST" action="'.$ValidateURL.'">
                            <input type="text" name="vocherid1" placeholder="xxxxx" maxlength="5" style="max-width:50px;">
                            <input type="text" name="vocherid2" placeholder="xxxxx" maxlength="5" style="max-width:50px;">
                            <input type="text" name="vocherid3" placeholder="xxxxx" maxlength="5" style="max-width:50px;">
                            <input type="text" name="vocherid4" placeholder="xxxxx" maxlength="5" style="max-width:50px;">
                            <input type="text" name="vocherid5" placeholder="xxxxx" maxlength="5" style="max-width:50px;">
                            <button type="submit" id="fas" name="submit" value="4">Check</button>
                        </form>';
    }
    
?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>Voucher Validator</title>

		<?php print $headerLinks ?>
		
	</head>

	<body>
		<div align="center">
            <h1>Vocher Validator</h1><br>
			<?php print $bodycontents."<br/>";?>
		</div>
		<div id="dvModal">
			<div id="dvContent">
				<img src="#" alt="Image 03" />
				<p id="imageTitle">Title here</p>
			</div>
		</div>
	</body>

	<?php print $footerLinks ?>

</html>
<?php $conn->close(); ?>