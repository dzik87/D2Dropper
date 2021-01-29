<?php
$file = "trade/".$_POST['filename'].".png";
if (isset($_POST['data'])) {
	$data = $_POST['data'];
	file_put_contents($file, base64_decode($data));
	// return the filename
	echo json_encode($file);
}
echo json_encode(false);
?>