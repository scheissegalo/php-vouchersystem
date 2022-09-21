<?php
include ('connect.php');

//URL for validation
$url = $mainURL.'validate.php?vocherid=';	

$bodycontents = '<h1>Voucher Dashboard</h1>
<a href="'.$mainURL.'generate.php" >Generate Voucher</a> | <a href="'.$mainURL.'validate.php" >Validate Voucher</a>
<h4 class="warning">Warning you can only download a voucher once!</h4><br>';

if (isset($_POST['submit'])) {
	$vocherID = $_GET['vocherid'];
	$bodycontents .= '<h4>Voucher ID: '.$vocherID.' download!</h4>';

	$sql = "SELECT * FROM `vocher` WHERE `vocher`.`vkey` = '".$vocherID."';";
	$results = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));

	while( $rows = mysqli_fetch_assoc($results) ) {
		$DLimage = $rows['img_path'];
	}

	header("Content-Type: text/html");
	header("Content-Length: ".filesize($DLimage)."\n\n");
	header("Content-Disposition: attachment; filename=$DLimage");
	echo file_get_contents($DLimage);

	$sql = "UPDATE `vocher` SET `status` = '3' WHERE `vocher`.`vkey` = '".$vocherID."';";
	$results = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));

	$bodycontents .= redirect($mainURL);
}

function redirect($url)
{
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url . '"';
    $string .= '</script>';

    return $string;
}

function cleanDataBase($conn){
	$sql = "TRUNCATE TABLE vocher";
	if ($conn->query($sql) === TRUE) {
	//echo "New record created successfully";
	}
	fileWriter(true, "ID | Option | Code\n");
}

function fileWriter($delFile, $txtToWrite){
	
	global $baseDirectory;
	global $codeListTxt;
	$codeListFilePath = $baseDirectory.$codeListTxt;

	if ($delFile){
		if (unlink($codeListFilePath)){
			// file deleted
		}
	}

    $myfile = fopen($codeListFilePath, "a+") or die("Unable to open file!");
    fwrite($myfile, $txtToWrite);
	fclose($myfile);
}

function fileReader(){
	
	global $baseDirectory;
	global $codeListTxt;
    $codeListFilePath = $baseDirectory.$codeListTxt;

    $myfile = fopen($codeListFilePath, "a+") or die("Unable to open file!");
	
    return nl2br(fread($myfile,filesize($codeListFilePath)));

	fclose($myfile);
}

function formatCode($n){
	//xxxxx-xxxxx-xxxxx-xxxxx-xxxxx
	return vsprintf('%s%s%s%s%s-%s%s%s%s%s-%s%s%s%s%s-%s%s%s%s%s-%s%s%s%s%s', str_split($n));
}

function displayAllVouchers($ap_option){
	
	global $baseDirectory;
	global $conn;
	global $mainURL;

	$imgout = '';

	$sqlEvents = 'SELECT * FROM vocher WHERE `ap_option` = '.$ap_option.';'; 
	$resultset = mysqli_query($conn, $sqlEvents) or die("database error:". mysqli_error($conn));

	while( $rows = mysqli_fetch_assoc($resultset) ) {
		$image = $rows['img_path'];
		$imgout .= '<div id="download"><h4>ID: '.$rows['id'].'</h4>';
		$imgout .= '<img class="image" src="'.$image.'" /><br>';
		$imgout .= '<a href="'.$rows['url'].'" >'.formatCode($rows['vkey']).'</a><br>';
		$voucher_status = $rows['status'];

		$imgout .='<form method="POST" action="'.$mainURL.'?vocherid='.$rows['vkey'].'">';

		if ($voucher_status == "0"){
			// Status 1 - Accepted
			$imgout .= '<h4>Voucher Used</h4>
						<button type="submit" id="fas" name="submit" value="3">Delete</button>';
		} elseif ($voucher_status == 1){
			// Status 1 Available
			$imgout .= '<button type="submit" id="fas" name="submit" value="1">Accept</button>
						<button type="submit" id="fas" name="submit" value="2">Decline</button>
						<button type="submit" id="fas" name="submit" value="3">Delete</button>
						<button type="submit" name="submit">Download</button>';
		} elseif ($voucher_status == 2){
			// Status 2 Declined
			$imgout .= '<h4>Voucher has been Declined</h4>
						<button type="submit" id="fas" name="submit" value="3">Delete</button>';
		} elseif ($voucher_status == 3){
			// Status 2 Downloadet
			$imgout .= '<h4>Already Downloaded</h4>
						<button type="submit" id="fas" name="submit" value="1">Accept</button>
						<button type="submit" id="fas" name="submit" value="2">Decline</button>
						<button type="submit" id="fas" name="submit" value="3">Delete</button>';
		}
		$imgout .= '</form></div>';
		
	}	

/*
	$filename = '123.webp';

	header("Content-Type: text/html");

	header("Content-Length: ".filesize($filename)."\n\n");

	header("Content-Disposition: attachment; filename=$filename");

	echo file_get_contents($filename); */

	//$images = glob($baseDirectory."*.jpg");
	//foreach($images as $image) {
	//	$imgout .= '<div id="download"><img src="'.$image.'" /><br>';
	//	$imgout .= '<a class="button" href="'.$image.'" download="'.$image.'">Download</a></div>';
	//}

	return $imgout;
}

?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>Voucher Dashboard</title>

		<?php print $headerLinks ?>

	</head>

	<body>
		<div align="center">
			<?php 
			print $bodycontents."<br/>";

			print "<h3>All Vouchers</h3>";
			print "<h4>15 min Vouchers</h4>";
			print displayAllVouchers(15);
			print "<h4>30 min Vouchers</h4>";
			print displayAllVouchers(30);
			
			?>
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