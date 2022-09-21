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
	$bodycontents = 'Codes successfully generated';
}else{
    $sqlEvents = 'SELECT * FROM vocher WHERE `status` = 1;'; 
	$resultset = mysqli_query($conn, $sqlEvents) or die("database error:". mysqli_error($conn));
    $countRows = mysqli_num_rows($resultset);
	$bodycontents = '<h1>Voucher Generator - '.$countRows.'Valid Vouchers in database</h1><br>
    <a href="'.$mainURL.'index.php" >Voucher Dashboard</a> | <a href="'.$mainURL.'validate.php" >Validate Voucher</a><br>
	<form method="POST" action="'.$rooturl.'">
		<label>Vocher Count</label>
		<input type="number" name="vochercount" value="10" min="5" max="100" step="1" ><br>
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
		$generator->output_image();

		/* Create bitmap image. */
		$image = $generator->render_image();
		//imagepng($image);
		$fileName = $baseDirectory."qr_".$i.".png";
		imagepng($image, $fileName, 9);
		saveQRCodeToGiftcard($image, $fileName, $currentCode, $i, $appointmentOption, $newID);
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
		<script src="dist/easy.qrcode.min.js" type="text/javascript" charset="utf-8"></script>
		<script>(function(a,b){if("function"==typeof define&&define.amd)define([],b);else if("undefined"!=typeof exports)b();else{b(),a.FileSaver={exports:{}}.exports}})(this,function(){"use strict";function b(a,b){return"undefined"==typeof b?b={autoBom:!1}:"object"!=typeof b&&(console.warn("Deprecated: Expected third argument to be a object"),b={autoBom:!b}),b.autoBom&&/^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(a.type)?new Blob(["\uFEFF",a],{type:a.type}):a}function c(a,b,c){var d=new XMLHttpRequest;d.open("GET",a),d.responseType="blob",d.onload=function(){g(d.response,b,c)},d.onerror=function(){console.error("could not download file")},d.send()}function d(a){var b=new XMLHttpRequest;b.open("HEAD",a,!1);try{b.send()}catch(a){}return 200<=b.status&&299>=b.status}function e(a){try{a.dispatchEvent(new MouseEvent("click"))}catch(c){var b=document.createEvent("MouseEvents");b.initMouseEvent("click",!0,!0,window,0,0,0,80,20,!1,!1,!1,!1,0,null),a.dispatchEvent(b)}}var f="object"==typeof window&&window.window===window?window:"object"==typeof self&&self.self===self?self:"object"==typeof global&&global.global===global?global:void 0,a=/Macintosh/.test(navigator.userAgent)&&/AppleWebKit/.test(navigator.userAgent)&&!/Safari/.test(navigator.userAgent),g=f.saveAs||("object"!=typeof window||window!==f?function(){}:"download"in HTMLAnchorElement.prototype&&!a?function(b,g,h){var i=f.URL||f.webkitURL,j=document.createElement("a");g=g||b.name||"download",j.download=g,j.rel="noopener","string"==typeof b?(j.href=b,j.origin===location.origin?e(j):d(j.href)?c(b,g,h):e(j,j.target="_blank")):(j.href=i.createObjectURL(b),setTimeout(function(){i.revokeObjectURL(j.href)},4E4),setTimeout(function(){e(j)},0))}:"msSaveOrOpenBlob"in navigator?function(f,g,h){if(g=g||f.name||"download","string"!=typeof f)navigator.msSaveOrOpenBlob(b(f,h),g);else if(d(f))c(f,g,h);else{var i=document.createElement("a");i.href=f,i.target="_blank",setTimeout(function(){e(i)})}}:function(b,d,e,g){if(g=g||open("","_blank"),g&&(g.document.title=g.document.body.innerText="downloading..."),"string"==typeof b)return c(b,d,e);var h="application/octet-stream"===b.type,i=/constructor/i.test(f.HTMLElement)||f.safari,j=/CriOS\/[\d]+/.test(navigator.userAgent);if((j||h&&i||a)&&"undefined"!=typeof FileReader){var k=new FileReader;k.onloadend=function(){var a=k.result;a=j?a:a.replace(/^data:[^;]*;/,"data:attachment/file;"),g?g.location.href=a:location=a,g=null},k.readAsDataURL(b)}else{var l=f.URL||f.webkitURL,m=l.createObjectURL(b);g?g.location=m:location.href=m,g=null,setTimeout(function(){l.revokeObjectURL(m)},4E4)}});f.saveAs=g.saveAs=g,"undefined"!=typeof module&&(module.exports=g)});</script>
		<style type="text/css">
			body{
				margin: 0;
				padding: 0;
			}
			
			#header{
				text-align: left;
				margin: 0;
				line-height: 80px;
				background-color: #007bff;
				color: #fff;
				padding-left: 20px;
				font-size: 36px;
			}
			
			#header a{color: #FFFF00;}
			#header a:hover{color: #FF9933;}
			#container {
				/* width: 1030px; */
				margin: 10px auto;
			}

			.imgblock {
				margin: 10px 0;
				text-align: center;
				float: left;
				min-height: 420px;
				border-bottom: 1px solid #B4B7B4;
			}

			.qr table {
				
			}

			.title {
				font-size: 15px;
				font-weight: bold;
				color: #fff;
				text-align: center;
				width: 330px;
				margin: 10px 5px;
				height: 60px;
				background-color: #0084C6;
				line-height: 60px;
			}
		
			#footer {
				margin-top: 20px;
				border-top: 1px solid gainsboro;
				line-height: 40px;
				clear: both;
				text-align: center;
			}

			#footer a {
				color: #0084C6;
				text-decoration: none;
			}
			.voucher-admin img{
				max-width: 200px;
				padding: 0 4px 0 0;
			}
			#download{
				display: inline-block;
			}
		</style>
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

</html>

<?php $conn->close(); ?>