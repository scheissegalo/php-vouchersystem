<?php
// MYSQL Settings
$host = 'localhost';
$DB = 'database';
$user = 'username';
$password = 'password';

// Connect Database
$conn = mysqli_connect($host, $user, $password, $DB) or die("Connection failed: " . mysqli_connect_error()."<br>Please edit the connect.php file and try again");
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error()."<br>Please edit the connect.php file and try again");
    exit();
}

// Date/Time Settings
date_default_timezone_set('UTC');

// Other Settings
$mainURL = 'http://voucher.sample.com/php-vouchersystem/'; // Main URL for this script
$homepage = 'https://sample.com/'; // Homepage URL
$baseDirectory = 'output/'; // Voucher Output Directory make sure is's there, if not create it and give writing rights.
$ValidateURL = $mainURL.'validate.php'; //URL for validation
$font_path = 'D:\\xamp2\\htdocs\\php-vouchersystem\\\fonts\\Nunito-Regular.ttf'; // Windows example for linux use /folder/to/font/nunito.ttf - Nunito Font Folder, absolute path!
$voucher15min = 'whc_gc_15.jpg'; // 15 min version of Voucher Background
$voucher30min = 'whc_gc_30.jpg'; // 30 min version of Voucher Background
$vouchernotime = 'whc_gc_no.jpg'; // no time limit version of Voucher Background
$voucherclean = 'whc_gc_clean.jpg'; // clean version of Voucher Background
$voucher15minclean = 'whc_gc_15_clean.jpg'; // 15 min clean version of Voucher Background
$voucher30minclean = 'whc_gc_30_clean.jpg'; // 30 min clean version of Voucher Background

// CSS or JavaScript files to add to the header
$headerLinks = '
    <link rel="stylesheet" href="style.css" />
';

// JavaScript files to add to the footer
$footerLinks = '
    <script src="script.js"></script>
';

$footer_txt = '        <!-- Feel Free to remove Copyright from footer -->
<div id="footer">Copyright 2022 <a href="https://karich.design">Karich.Design</a>
 - OpenSource <a href="https://github.com/scheissegalo/php-vouchersystem">PHP-VOUCHERSYSTEM</a>
 - Report a <a href="#">bug or issue</a></div>';

?>
