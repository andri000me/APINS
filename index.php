<?php
include '../inc/db.php';
session_start();
if(!isset($_SESSION['userid'])){
	header('location:login.html');
}else{ 
	$iduser=$_SESSION['userid'];
	$h2 = mysqli_num_rows(mysqli_query($koneksi, "select * from ptk where ptk_id='$iduser'"));
	if($h2>0){
		$h = mysqli_fetch_array(mysqli_query($koneksi, "select * from ptk where ptk_id='$iduser'"));
		if($h['jenis_ptk_id']==11){
			header('location:./operator');
		}else{
			header('location:./guru');
		};
	}else{
	    header('location:./pd');
	}
}; 
?>