<?php 

require_once '../../inc/db_connect.php';
function TanggalIndo($date){
    $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
 
    $tahun = substr($date, 0, 4);
    $bulan = substr($date, 5, 2);
    $tgl   = substr($date, 8, 2);
 
    $result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;        
    return($result);
};
$tapel=$_GET['tapel'];
$output = array('data' => array());

$sql = "SELECT * FROM ptk order by nama asc";
$query = $connect->query($sql);
while ($row = $query->fetch_assoc()) {
	$idp=$row['ptk_id'];
	$sqlp = "SELECT * FROM mengajar where tapel='$tapel' and ptk_id='$idp'";
	$queryp = $connect->query($sqlp);
	$pn = $queryp->fetch_assoc();
	$ada = $queryp->num_rows;
	$rmb=$pn['rombel'];
	$niy=$row['niy_nigk'];
	$actionButton = '
	<a href="editptk.php?idp='.$idp.'" class="btn btn-effect-ripple btn-xs btn-success"> <i class="fa fa-pencil"></i> Ubah</a>
	';
	if($ada>0){
		if(!empty($rmb)){
			$ptn=$rmb;
		}else{
			$ptn="Semua Kelas";
		};
	}else{
		$ptn="Belum ada Kelas";
	};
	
	$output['data'][] = array(
		$row['nama'],
		$row['nik'],
		$row['niy_nigk'],
		$row['nuptk'],
		$ptn,
		$actionButton
	);
}

// database connection close
$connect->close();

echo json_encode($output);