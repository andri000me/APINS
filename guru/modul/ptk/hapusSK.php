<?php 

require_once '../../inc/db_connect.php';

$output = array('success' => false, 'messages' => array());

$memberId = $_POST['member_id'];
$sql = "DELETE from SK where id_sk= {$memberId}";
$query = $connect->query($sql);
if($query === TRUE) {
	$output['success'] = true;
	$output['messages'] = 'Nomor SK Berhasil dihapus';
} else {
	$output['success'] = false;
	$output['messages'] = 'Error saat mencoba menghapus data siswa';
}

// close database connection
$connect->close();

echo json_encode($output);