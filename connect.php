<?php
// MYSQL Settings
$host = 'localhost';
$DB = 'vocher';
$user = 'root';
$password = '';

//Other Settings
$mainURL = 'http://localhost/php-vouchersystem/'; // Main URL for this script
$baseDirectory = 'output/'; // Voucher Output Directory make sure is's there, if not create it and give writing rights.
$ValidateURL = $mainURL.'validate.php'; //URL for validation
$font_path = 'D:\\xamp2\\htdocs\\php-vouchersystem\\Nunito-Regular.ttf'; // Nunito Font Folder, absolute path!
$voucher15min = 'whc_gc_15.jpg'; // 15 min version of Voucher Background
$voucher30min = 'whc_gc_30.jpg'; // 30 min version of Voucher Background

$conn = mysqli_connect($host, $user, $password, $DB) or die("Connection failed: " . mysqli_connect_error());
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>
