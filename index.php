<?php
include ('connect.php');

//URL for validation
$rooturl = 'http://localhost/q/';
$url = $rooturl.'confirm.php?vocherid=';	

$bodycontents = '<h1>Voucher Dashboard</h1>
<a href="'.$rooturl.'generate.php" >Generate Voucher</a> | <a href="'.$rooturl.'validate.php" >Validate Voucher</a>';
$baseDirectory = 'output/';
$codeListTxt = 'CodeList.txt';



if (isset($_POST['submit'])) {
	//$vochercount = $_POST['vochercount'];
	//$vocherurl = $_POST['vocherurl'];
	//print $vochercount.' '.$vocherurl;
	//$appointmentOption = $_POST['ap_option'];
	//if (isset($_POST['del_db'])){
	//	$del_DB = true;
	//}else{
	//	$del_DB = false;
	//}
	//print "Del DB: ".$del_DB;
	//print "ap_option: ".$appointmentOption;
	//$doGenerateCodes = true;
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

function displayAllVouchers($ap_option){
	
	global $baseDirectory;
	global $conn;

	$imgout = '';

	$sqlEvents = 'SELECT * FROM vocher WHERE `status` = 1 AND `ap_option` = '.$ap_option.';'; 
	$resultset = mysqli_query($conn, $sqlEvents) or die("database error:". mysqli_error($conn));
	while( $rows = mysqli_fetch_assoc($resultset) ) {
		$image = $rows['img_path'];
		$imgout .= '<div id="download"><h4>ID: '.$rows['id'].'</h4>';
		$imgout .= '<img src="'.$image.'" /><br>';
		$imgout .= 'Key: <a href="'.$rows['url'].'" >'.$rows['vkey'].'</a><br>';
		$imgout .= '<a class="button" href="'.$image.'" download="'.$image.'">Download</a><br></div>';
		
	}

	

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
				padding: 5px 5px 5px 5px;
			}
		</style>
	</head>

	<body>
		<div align="center" class="voucher-admin">
			<?php 
			print $bodycontents."<br/>";

			print "<h3>All Vouchers</h3>";
			print "<h4>15 min Vouchers</h4>";
			print displayAllVouchers(15);
			print "<h4>30 min Vouchers</h4>";
			print displayAllVouchers(30);
			
			?>
		</div>
	</body>

</html>

<?php $conn->close(); ?>