<?php 

require_once '../../inc/db_connect.php';
//if form is submitted
if($_POST) {	

	$validator = array('success' => false, 'messages' => array());
	$idr=$_POST['idraport'];
	
	$deskripsi=$_POST['deskripsi'];
	$sql = "SELECT * FROM deskripsi_k13 WHERE id_raport='$idr'";
	$usis = $connect->query($sql)->fetch_assoc();
	if(empty($deskripsi)){
		$validator['success'] = false;
		$validator['messages'] = "Tidak Boleh Kosong Datanya!";
	}else{
			$sql = "update deskripsi_k13 set deskripsi='$deskripsi' where id_raport='$idr'";
			$query = $connect->query($sql);
			$validator['success'] = true;
			$validator['messages'] = "Raport Sikap berhasil diperbaharui!";		
	};
	
	// close the database connection
	$connect->close();

	echo json_encode($validator);

}