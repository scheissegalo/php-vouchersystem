<?php
include ('connect.php');
include 'qrcode.php';

/*
Todo: Rename file to not spam output folder | rename("/test/file1.txt","/home/docs/my_file.txt");
Uppercase with php | echo strtoupper("Hello WORLD!");
redirect on download | JS?
Format date on Card | https://www.w3schools.com/php/func_date_date_format.asp
default option in forms
*/

$default_gift_card_code = '';
$default_gift_card_date = '';
$default_gift_card_sx = '';
$url = $ValidateURL.'?vocherid=';
$vkey_already = false;
$message = '';

$saveimage = false;

if (isset($_POST['submit'])) {
    $gift_card_txt = $_POST['title_text'];
    $gift_card_fz = $_POST['font_size'];
    $gift_card_opt = $_POST['ap_option']; 
    $gift_card_code = $_POST['code_opt']; 
    $gift_card_txt_opt = $_POST['uppercase'];
    $gc_code = $_POST['gc_code'];

    $sqlEvents = "SELECT vkey FROM vocher;";
    $resultset = mysqli_query($conn, $sqlEvents) or die("database error:". mysqli_error($conn));

    while( $rows = mysqli_fetch_assoc($resultset) ) {
        if ($rows['vkey'] == $gc_code) {
            //Match
            $vkey_already = true;
            //$image = $rows['img_path'];
        }
    }

    if (isset($_POST['qr_code'])){
        $gift_card_qr = true;
    }else{
        $gift_card_qr = false;
    }
    

    if (isset($_POST['top_pos'])){
        $gift_card_tp = $_POST['top_pos'];  
    }else {
        $gift_card_tp = 50;
    }

    if (isset($_POST['code_opt'])){
        //$DLimage = $_POST['image_src'];
    }else{
        $gift_card_code = 0;
    }
    $saveimage = true;

}else{
    $gift_card_txt = "GIFT CARD";
    $gift_card_fz = 80;
    $gift_card_opt = "";
    $gift_card_tp = 50;
    $gift_card_code = '2';
    $saveimage = false;
    $gift_card_qr = false;
}

if(isset($_GET['dl'])){

    if (!$vkey_already){
        $currentCode = $_GET['vocher_code'];
        $datenow = $_GET['vocher_date'];
        $appointmentOption = $_GET['ap_option']; // vocher_code vocher_date ap_option
        $img_path = $_GET['path'];

        $sql = "INSERT INTO vocher (vkey, status, date, url, ap_option, img_path)
        VALUES ('".$currentCode."', '3', '".$datenow."', '".$url.$currentCode."', '".$appointmentOption."', '".$img_path."')";

        if ($conn->query($sql) === TRUE) {
            //echo "New record created successfully";
        }

        header("Content-Type: text/html");
        header("Content-Length: ".filesize($img_path)."\n\n");
        header("Content-Disposition: attachment; filename=$img_path");
        echo file_get_contents($img_path);
    }else{
        $message = "You already have a vocher with that code";
    }
}

function generateRandomString($length = 10) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function formatCode($n){
	//xxxxx-xxxxx-xxxxx-xxxxx-xxxxx
	return vsprintf('%s%s%s%s%s-%s%s%s%s%s-%s%s%s%s%s-%s%s%s%s%s-%s%s%s%s%s', str_split($n));
}

function generateVoucher($title_text, $ap_option, $font_size, $headroom = 50, $add_code = 2){

	global $baseDirectory;
	global $font_path;
	global $voucher15minclean;
	global $voucher30minclean;
    global $voucherclean;
    global $default_gift_card_code;
    global $default_gift_card_date;
    global $default_gift_card_sx;
    global $saveimage;
    global $gift_card_qr;
    global $url;
    global $ValidateURL;

    $datenow = date("Y-m-d H:i:s", time());
    $default_gift_card_date = $datenow;
    $currentCode = generateRandomString(25);	
    $file_suffix = generateRandomString(10);
    $default_gift_card_sx = $file_suffix;

    if (!$saveimage){
        $file_suffix = '';
        $default_gift_card_sx = '';
        //echo "save Image active";
        $img_dest = $baseDirectory.'whc_gc_custom_temp.jpg';
    }else{
        $img_dest = $baseDirectory.'whc_gc_custom_'.$currentCode.'.jpg';
    }

    $default_gift_card_code = $currentCode; // Workaround to get current code later in HTML


    // Code Options
    if ($add_code == 1){
        // Code and Date
        $text = "Code: ".formatCode($currentCode).' | Date: '.$datenow;
    }elseif($add_code == 2){
        // Only Code
        $text = "Code: ".formatCode($currentCode);
    }elseif($add_code == 3){
        // Only Date
        $text = 'Date: '.$datenow;
    }else{
        $text = '';
    }    
    
    // Appointment Option
	if ($ap_option == "15"){
		$dest = imagecreatefromjpeg($voucher15minclean);
	}elseif ($ap_option == "30"){
		$dest = imagecreatefromjpeg($voucher30minclean);
	}else{
        $dest = imagecreatefromjpeg($voucherclean);
    }

	// Allocate A Color For The Text
	$white = imagecolorallocate($dest, 198, 143, 102);
    // Set Font Size
    $size = $font_size;

    if ($gift_card_qr){
        $options = [
            "s" => "qr-h",
            "sf" => "5",
        ];
        $scantext = "Scan with your Phone";
        //$scantext1 = "or enter code here:";
        //$scantext2 = $ValidateURL;
        imagettftext($dest, 15, 0, 890, 560, $white, $font_path, $scantext); // Print Scan with your Phone
        //imagettftext($dest, 15, 0, 890, 580, $white, $font_path, $scantext1); // Print Scan with your Phone
        //imagettftext($dest, 15, 0, 700, 600, $white, $font_path, $scantext2); // Print Scan with your Phone
        $generator = new QRCode($url.$currentCode, $options);
        $qr_image = $generator->render_image();
        $fileName = $baseDirectory."qr_".$currentCode.".png";
		imagepng($qr_image, $fileName, 9);
        $src = imagecreatefrompng($fileName);
		//saveQRCodeToGiftcard($qr_image, $fileName, formatCode($currentCode), $i, $appointmentOption, $newID);
		//imagedestroy($qr_image);
        imagecopymerge($dest, $src, 850, 250, 0, 0, 285, 285, 100); 
        imagedestroy($qr_image);
        unlink($fileName);
    }

    // Calculate Top Position
    if ($headroom <= 20){
        $y_pos = $font_size + 20;
    }else{
        $y_pos = $font_size + $headroom;
    }

    // Center Text to image
    $bbox = imagettfbbox($size, 0, $font_path, $title_text);
    $center = (imagesx($dest) / 2) - (($bbox[2] - $bbox[0]) / 2 + 20);

    $bbox1 = imagettfbbox(20, 0, $font_path, $text);
    $center1 = (imagesx($dest) / 2) - (($bbox1[2] - $bbox1[0]) / 2);

    // Print Title
	imagettftext($dest, $size, 0, $center, $y_pos, $white, $font_path, $title_text); 
    // Print Code
    imagettftext($dest, 20, 0, $center1, 720, $white, $font_path, $text); 

    // Save image to output folder
	//imagejpeg($dest, $baseDirectory.'whc_gc_custom_'.$file_suffix.$ap_option.'.jpg', 100);
    imagejpeg($dest, $img_dest, 100);

    //return $baseDirectory.'whc_gc_custom_'.$file_suffix.$ap_option.'.jpg';    
    return $img_dest;    

    // Remove image from memory
	imagedestroy($dest);

}

$img_path = generateVoucher($gift_card_txt, $gift_card_opt, $gift_card_fz, $gift_card_tp, $gift_card_code);

?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>Voucher Editor</title>

		<?php print $headerLinks ?>

	</head>

	<body>
        <div id="header">
            <h1>Voucher Editor</h1>
            <a href="<?php print $mainURL; ?>index.php" >Voucher Dashboard</a> | <a href="<?php print $mainURL; ?>generate.php" >Voucher Generator</a><br>
        </div>
		<div class="voucher-admin">
			<?php 
            print "<h3>".$message."</h3>";
			print '<br/>
            <div class="form_container">
            <form method="POST" action="'.$mainURL.'editor.php#voucherimg">
                <div class="form_item_container">
                    <h2>Settings</h2>
                    <h4 class="subtitle">Enter Text and press Generate button to generate your voucher. If you are happy, click the download button</h4>
                    <div class="form_item">
                        <img src="img/prev_gc_title.jpg" />
                    </div>
                    <div class="form_item">
                        <label>Text on Card</label>
                    </div>
                    <div class="form_item">
                        <input type="text" name="title_text" placeholder="Gift Card Text" value="'.$gift_card_txt.'" maxlength="20" style="max-width:200px;">
                        <input type="checkbox" name="uppercase" value="uppercase" checked><label>uppercase</label>
                    </div>
                    <div class="form_item">
                        <img src="img/prev_gc_size.jpg" />
                    </div>
                    <div class="form_item">
                        <label>Font Size</label>
                    </div>
                    <div class="form_item">
                        <input type="number" name="font_size" value="'.$gift_card_fz.'" min="8" max="100" step="1" ><br>
                        <span>min 8 | max 100 | default 80</span>
                    </div>
                    <div class="form_item">
                        <img src="img/prev_gc_top.jpg" />
                    </div>
                    <div class="form_item">
                        <label>Top Position</label>
                    </div>
                    <div class="form_item">
                        <input type="number" name="top_pos" value="'.$gift_card_tp.'" min="20" max="130" step="1" ><br>
                        <span>min 20 | max 130 | default 50</span>
                    </div>
                    <div class="form_item">
                        <img src="img/prev_gc_option.jpg" />
                    </div>
                    <div class="form_item">
                        <label>Appointment Option</label>
                    </div>
                    <div class="form_item">
                        <select name="ap_option" id="ap_option">
                            <option value="0">none</option>
                            <option value="15">15 min</option>
                            <option value="30">30 min</option>
                        </select>
                        <span>none | 15 min | 30 min</span>
                    </div>
                    <div class="form_item">
                        <img src="img/prev_gc_code.jpg" />
                    </div>
                    <div class="form_item">
                        <label>Code / Date</label>
                    </div>
                    <div class="form_item">
                        <select name="code_opt" id="code_opt">
                            <option value="0">none</option>
                            <option value="1">Code and Date</option>
                            <option value="2" selected>Code Only</option>
                            <option value="3">Date Only</option>
                        </select><br>
                        <span>Choose if you want to add date, code or both</span>
                    </div>
                    <div class="form_item">
                        <img src="img/prev_gc_qr.jpg" />
                    </div>
                    <div class="form_item">
                        <label>QR-Code</label>
                    </div>
                    <div class="form_item">
                        <input type="checkbox" name="qr_code" value="qr_code"><label>QR-Code</label><br>
                        <span>Choose if you want to display QR-Code</span>
                    </div>
                    <input type="hidden" name="image_src" id="image_src" value="'.$img_path.'" />
                    <input type="hidden" name="gc_code" id="gc_code" value="'.$default_gift_card_code.'" />
                    <button type="submit" name="submit" style="margin-top:5px;">Generate</button>
                </div>
            </form>
            </div>
            <div class="gen_image_container" id="voucherimg">
                <h2>Generated Voucher</h2>
                <div class="code_text">Code: '.formatCode($default_gift_card_code).'</div><a href="'.$mainURL.'editor.php?vocher_code='.$default_gift_card_code.'&ap_option='.$gift_card_opt.'&vocher_date='.$default_gift_card_date.'&dl=1&path='.$img_path.'" >DOWNLOAD</a><br/>
                <span>by downloading you save the current version of the card into the database and make it valid.</span><br>
                <div class="gen_image">
                    <img src="'.$img_path.'" />
                </div><br>
            </div>
            ';
			?>
		</div>
    <?php print $footer_txt; ?>
        
	</body>

	<?php print $footerLinks ?>

</html>

<?php $conn->close(); 
// Download image - not working properly
    if (isset($DLimage)){
        header("Content-Type: text/html");
        header("Content-Length: ".filesize($DLimage)."\n\n");
        header("Content-Disposition: attachment; filename=$DLimage");
        echo file_get_contents($DLimage);
    }
?>