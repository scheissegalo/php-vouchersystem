<?php
include ('connect.php');

//URL for validation
$url = $mainURL.'validate.php?vocherid=';	

$bodycontents = '<h4 class="warning">Warning for safety you can only download a voucher once! If not downloaded, the voucher is invalid!</h4><br>';

if (isset($_POST['submit'])) {
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
		$sqlEvents = 'SELECT * FROM vocher WHERE `vkey` = "'.$_GET['vocherid'].'";'; 
		$resultset = mysqli_query($conn, $sqlEvents) or die("database error:". mysqli_error($conn));
		while( $rows = mysqli_fetch_assoc($resultset) ) {
			$img_path = $rows['img_path'];
		}

		$sql = "DELETE FROM vocher WHERE `vocher`.`vkey` = '".$_GET['vocherid']."';";
		$results = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));

		unlink($img_path);
		//header("Location: $calendar_url");

	}elseif ($_POST['submit'] == 4){
		// Download
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

	$sqlEvents = 'SELECT * FROM vocher WHERE `ap_option` = '.$ap_option.' ORDER BY `status` ASC;'; 
	$resultset = mysqli_query($conn, $sqlEvents) or die("database error:". mysqli_error($conn));

	while( $rows = mysqli_fetch_assoc($resultset) ) {

		$voucher_status = $rows['status'];

		if ($voucher_status == 0){ // accepted
			$extra_classes = 'voucher_accepted';			
		}elseif ($voucher_status == 1){ // available
			$extra_classes = 'voucher_available';
		}elseif ($voucher_status == 2){ // Declined
			$extra_classes = 'voucher_declined';
		}elseif ($voucher_status == 3){ // Downloadet
			$extra_classes = 'voucher_downloadet';
		}

		$image = $rows['img_path'];
		$imgout .= '<div id="download" class="voucher_container '.$extra_classes.'">';
		$imgout .= '<img class="image" src="'.$image.'" /><br>';
		$imgout .= '<a href="'.$rows['url'].'" >'.formatCode($rows['vkey']).'</a><br>';




		$imgout .='<form method="POST" action="'.$mainURL.'?vocherid='.$rows['vkey'].'">';

		if ($voucher_status == "0"){
			// Status 0 - Accepted
			$imgout .= '<h4>Voucher Used</h4>
						<button type="submit" id="fas" name="submit" value="3">Delete</button>';
		} elseif ($voucher_status == 1){
			// Status 1 Available
			$imgout .= '<h4>Available</h4>
						<button type="submit" id="fas" name="submit" value="3">Delete</button>
						<button type="submit" id="fas" name="submit" value="4">Download</button>';
		} elseif ($voucher_status == 2){
			// Status 2 Declined
			$imgout .= '<h4>Voucher has been Declined</h4>
						<button type="submit" id="fas" name="submit" value="3">Delete</button>';
		} elseif ($voucher_status == 3){
			// Status 3 Downloadet
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

$sqlEvents = 'SELECT * FROM vocher'; 
$resultset = mysqli_query($conn, $sqlEvents) or die("database error:". mysqli_error($conn));
$countRows = mysqli_num_rows($resultset);

$sqlEvents = 'SELECT * FROM vocher WHERE status = "0"'; 
$resultset = mysqli_query($conn, $sqlEvents) or die("database error:". mysqli_error($conn));
$countRows_accepted = mysqli_num_rows($resultset);

$sqlEvents = 'SELECT * FROM vocher WHERE status = "1"'; 
$resultset = mysqli_query($conn, $sqlEvents) or die("database error:". mysqli_error($conn));
$countRows_available = mysqli_num_rows($resultset);

$sqlEvents = 'SELECT * FROM vocher WHERE status = "2"'; 
$resultset = mysqli_query($conn, $sqlEvents) or die("database error:". mysqli_error($conn));
$countRows_declined = mysqli_num_rows($resultset);

$sqlEvents2 = 'SELECT * FROM vocher WHERE status = "3"'; 
$resultset2 = mysqli_query($conn, $sqlEvents2) or die("database error:". mysqli_error($conn));
$countRows_downloadet = mysqli_num_rows($resultset2);

?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>Voucher Dashboard</title>

		<?php print $headerLinks ?>

	</head>

	<body>
		<div id="header">
            <h1>Voucher Dashboard</h1>
            <a href="<?php print $mainURL; ?>editor.php" >Voucher Editor</a> | <a href="<?php print $mainURL; ?>generate.php" >Voucher Generator</a><br>
        </div>
		<div class="voucher-admin">
			<?php 
			print $bodycontents."<br/>";
			print $countRows.' vouchers in your Database<br>
					'.$countRows_accepted.' Accepted | '.$countRows_available.' Available | '.$countRows_declined.' Declined | '.$countRows_downloadet.' Downloadet
			';
			print '<div class="voucher_content">
					<h4 class="voucher_title">15 min Vouchers</h4>';
			print displayAllVouchers(15);
			print '</div><div class="voucher_content"><h4 class="voucher_title">30 min Vouchers</h4>';
			print displayAllVouchers(30);
			print '</div><div class="voucher_content"><h4 class="voucher_title">Vouchers without time limit</h4>';
			print displayAllVouchers(0);
			print '</div>';
			
			?>
		</div>
		<?php print $footer_txt; ?>
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