<?php 

require_once '../../inc/db_connect.php';
$kelas=$_GET['kelas'];
$smt=$_GET['smt'];
$tapel=$_GET['tapel'];
$mp=$_GET['mp'];
$ab=substr($kelas, 0, 1);
$output = array('data' => array());

$sql = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
$query = $connect->query($sql);
while($s=$query->fetch_assoc()) {
	$idp=$s['peserta_didik_id'];
	$sql2 = "select * from siswa where peserta_didik_id='$idp'";
	$query2 = $connect->query($sql2);
	$j=$query2->fetch_assoc();
	$sql1 = "select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$mp' and jns='k3'";
	$query1 = $connect->query($sql1);
	$ada=$query1->num_rows;
	$n=$query1->fetch_assoc();
	$idr=$n['id_raport'];
	$actionButton = '
		<div class="btn-group">
		<a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#mod-raport" data-kelas="'.$kelas.'" data-mp="'.$mp.'" data-tapel="'.$tapel.'" data-smt="'.$smt.'" data-pdid="'.$idp.'" id="getRaport" data-backdrop="false"><i class="fa fa-rotate-left"></i></a>
		</div>';
	if($ada>0){
		$output['data'][] = array(
		$j['nama'],
		$n['nilai'],$n['predikat'],$n['deskripsi'].' <a href="#editRaport" class="btn btn-effect-ripple btn-xs btn-danger" type="button" id="'.$idr.'" data-toggle="modal" data-id="'.$idr.'"><i class="fa fa-edit"></i></a>',
		$actionButton
		);
		
	}else{
		$output['data'][] = array(
		$j['nama'],
		$n['nilai'],$n['predikat'],$n['deskripsi'],
		$actionButton
		);
	};
	
	
	
};

	

// database connection close
$connect->close();

echo json_encode($output);