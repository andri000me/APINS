<?php 

require_once '../../inc/db_connect.php';
//if form is submitted
if($_POST) {	

	$validator = array('success' => false, 'messages' => array());
	$kelas=$_POST['kelas'];
	$smt=$_POST['smt'];
	$tema=$_POST['tema'];
	$nama_tema=$_POST['nama_tema'];
	if(empty($kelas) || empty($smt) || empty($tema) || empty($nama_tema)){
		$validator['success'] = false;
		$validator['messages'] = "Tidak Boleh Kosong Datanya!";
	}else{
		$sql = "select * from tema where kelas='$kelas' and smt='$smt' and tema='$tema'";
		$query = $connect->query($sql);
		$cks = $query->fetch_assoc();
		$ada=$query->num_rows;
		if($ada>0){
			$validator['success'] = false;
			$validator['messages'] = "Tema sudah ada, silahkan hapus terlebih dahulu!";
		}else{
			$sql1 = "insert into tema values('','$kelas','$smt','$tema','$nama_tema')";
			$query1 = $connect->query($sql1);
			if($query1 === TRUE) {			
				$validator['success'] = true;
				$validator['messages'] = "Penambahan Tema berhasil dilakukan";		
			} else {		
				$validator['success'] = false;
				$validator['messages'] = "Error while adding the member information";
			};
		};
		
	};
	
	// close the database connection
	$connect->close();

	echo json_encode($validator);

}