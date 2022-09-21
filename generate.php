<?php
include 'connect.php';
include 'qrcode.php';

$bodycontents = '<h1>Voucher Generator</h1>';
$doGenerateCodes = false;
$generatedCodes = '';

//URL for validation
$rooturl = $mainURL.'generate.php';
$url = $ValidateURL.'?vocherid=';	

if (isset($_POST['submit'])) {
	$vochercount = $_POST['vochercount'];
	$vocherurl = $_POST['vocherurl'];
	$appointmentOption = $_POST['ap_option'];
	if (isset($_POST['del_db'])){
		$del_DB = true;
	}else{
		$del_DB = false;
	}
	$doGenerateCodes = true;
}

if ($doGenerateCodes) {
	if ($del_DB) {
		cleanDataBase($conn);
	}	
	$generatedCodes = generateCodes($conn, $vocherurl, $vochercount, $appointmentOption);
	header( "refresh:3;url=".$mainURL );
  	echo '<div style="text-align:center;"><h2>Codes successfully generated</h2><br>You\'ll be redirected in about 3 secs. If not, click <a href="'.$mainURL.'">here</a>.</div>';
	exit;
}else{
    $sqlEvents = 'SELECT * FROM vocher WHERE `status` = 1;'; 
	$resultset = mysqli_query($conn, $sqlEvents) or die("database error:". mysqli_error($conn));
    $countRows = mysqli_num_rows($resultset);
	$bodycontents = '<h1>Voucher Generator - ('.$countRows.') Unused Vouchers in database</h1><br>
    <a href="'.$mainURL.'index.php" >Voucher Dashboard</a> | <a href="'.$mainURL.'validate.php" >Validate Voucher</a><br>
	<form method="POST" action="'.$rooturl.'">
		<label>Vocher Count</label>
		<input type="number" name="vochercount" value="10" min="1" max="100" step="1" ><br>
		<label>Appointment Option</label>
		<select name="ap_option" id="ap_option">
			<option value="15">15 min</option>
			<option value="30">30 min</option>
		</select><br>
		<label>validation URL</label>
		<input type="text" name="vocherurl" value="'.$url.'"><br>
		<input type="checkbox" id="del_db" name="del_db" value="del_db"><label> Delete Database</label> <br>
		<button type="submit" id="fas" name="submit" value="fa">Submit</button>
	</form>';

}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function cleanDataBase($conn){
	$sql = "TRUNCATE TABLE vocher";
	if ($conn->query($sql) === TRUE) {
	//echo "New record created successfully";
	}
	// TODO: Delete all vouchers from directory
    //array_map( 'unlink', array_filter((array) glob($baseDirectory."*")));
}

function formatCode($n){
	//xxxxx-xxxxx-xxxxx-xxxxx-xxxxx
	return vsprintf('%s%s%s%s%s-%s%s%s%s%s-%s%s%s%s%s-%s%s%s%s%s-%s%s%s%s%s', str_split($n));
}

function generateCodes($conn, $url, $vochercount, $appointmentOption){

	global $baseDirectory;

	$datenow = date("Y-m-d H:i:s", time());
	$content = '';
	$options = [
		"s" => "qr-h",
		"sf" => "5",
	];

	for ($i = 0; $i < $vochercount; $i++){
		//Generate randomString
		$currentCode = generateRandomString(25);
		//xxxxx-xxxxx-xxxxx-xxxxx-xxxxx

        $sqlEvents = "SELECT MAX(`id`) FROM `vocher`;";
        $resultset = mysqli_query($conn, $sqlEvents) or die("database error:". mysqli_error($conn));

        while( $rows = mysqli_fetch_assoc($resultset) ) {
            $maxID = $rows['MAX(`id`)'];
        }

        $newID = $maxID +$i;        

		$sql = "INSERT INTO vocher (vkey, status, date, url, ap_option, img_path)
		VALUES ('".$currentCode."', '1', '".$datenow."', '".$url.$currentCode."', '".$appointmentOption."', '".$baseDirectory.'whc_gc_'.$newID.'_'.$appointmentOption.'.jpg'."')";

		if ($conn->query($sql) === TRUE) {
			//echo "New record created successfully";
		}

		$generator = new QRCode($url.$currentCode, $options);

		/* Output directly to standard output. */
		//$generator->output_image();

		/* Create bitmap image. */
		$image = $generator->render_image();
		//imagepng($image);
		$fileName = $baseDirectory."qr_".$i.".png";
		imagepng($image, $fileName, 9);
		saveQRCodeToGiftcard($image, $fileName, formatCode($currentCode), $i, $appointmentOption, $newID);
		imagedestroy($image);

	}

	return $content;

}


function saveQRCodeToGiftcard($qrImage, $fileName, $qrCode, $id, $ap_option, $DB_id){

	global $baseDirectory;
	global $font_path;
	global $voucher15min;
	global $voucher30min;
	
	if ($ap_option == "15"){
		$dest = imagecreatefromjpeg($voucher15min);
	}else{
		$dest = imagecreatefromjpeg($voucher30min);
	}
	
	$src = imagecreatefrompng($fileName);
	
	imagecopymerge($dest, $src, 850, 250, 0, 0, 285, 285, 100); 
	
	// Allocate A Color For The Text
	$white = imagecolorallocate($dest, 198, 143, 102);
	// Set Text to Be Printed On Image
	$text = "Code: ".$qrCode;
	$text2 = "Scan with your Phone";
	
	// Print Text On Image | size, angle, x, y,
	imagettftext($dest, 20, 0, 350, 720, $white, $font_path, $text); // Print Code
	imagettftext($dest, 15, 0, 890, 560, $white, $font_path, $text2); // Print Scan with your Phone
	
	// TODO reload dashboard after images are generated
	//header('Content-Type: image/jpeg');
	imagejpeg($dest, $baseDirectory.'whc_gc_'.$DB_id.'_'.$ap_option.'.jpg', 100);
	
	imagedestroy($dest);
	imagedestroy($src);
	if (unlink($baseDirectory."qr_".$id.".png")){
		// file deleted
	}
}

?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>Voucher Generator</title>

		<?php print $headerLinks ?>

	</head>

	<body>
		<div align="center" class="voucher-admin">
			<?php 
			print $bodycontents."<br/>";
			?>
		</div>
		
		<?php
		if ($doGenerateCodes){
			print '		<div id="container">
			</div>';
		}
		?>
	
	</body>

	<?php print $footerLinks ?>

</html>

<?php $conn->close(); ?>