<?php
 include 'fpdf/fpdf.php';
 include 'exfpdf.php';
 include 'easyTable.php';
 include '../../inc/db_connect.php';
 
	$kelas=$_GET['kelas'];
	$ab=substr($kelas, 0, 1);
	$tapel=$_GET['tapel'];
	$smt=$_GET['smt'];
	$tipe=$_GET['tipe'];
	if($smt==1){
		$romawi='I';
	}else{
		$romawi='II';
	};
	//Isi KKM
		$kkm=$connect->query("select min(nilai) as kkmsekolah from kkm where tapel='$tapel'")->fetch_assoc();
		if(empty($kkm['kkmsekolah'])){
			$kkmsaya=0;
		}else{
			$kkmsaya=$kkm['kkmsekolah'];
		};
		//Jumlah Siswa 
		$jsiswa=$connect->query("select count(id_rombel) as jumlah_siswa from penempatan where rombel='$kelas' and tapel='$tapel'")->fetch_assoc();
		$namamapel=$connect->query("select * from mapel order by id_mapel asc")->fetch_assoc();
		$namafilenya="Form F1 ".$kelas.".pdf";
		$pdf=new exFPDF('P','mm',array(215,330));
		$pdf->AddPage(); 
		$pdf->SetFont('helvetica','',12);

		$table2=new easyTable($pdf, 1);
		$table2->rowStyle('font-size:14');
		$table2->easyCell('LAPORAN PENCAPAIAN TARGET KURIKULUM, KETUNTASAN BELAJAR','align:C;font-style:B');
		$table2->printRow();
		$table2->rowStyle('font-size:14');
		$table2->easyCell('DAN TARAP SERAP KURIKULUM SEKOLAH DASAR','align:C;font-style:B');
		$table2->printRow();
		$table2->rowStyle('font-size:14');
		$table2->easyCell('TAHUN PELAJARAN '.$tapel,'align:C;font-style:B');
		$table2->printRow();
		$table2->endTable(5);
		
		$table2=new easyTable($pdf, '{105,5,105}');
		$table2->rowStyle('font-size:12');
		$table2->easyCell('SEMESTER','align:R');
		$table2->easyCell(':','align:C');
		$table2->easyCell($romawi.' / '.$tipe,'align:L');
		$table2->printRow();
		$table2->rowStyle('font-size:12');
		$table2->easyCell('KELAS','align:R');
		$table2->easyCell(':','align:L');
		$table2->easyCell($kelas,'align:L');
		$table2->printRow();
		$table2->rowStyle('font-size:12');
		$table2->easyCell('SEKOLAH DASAR','align:R');
		$table2->easyCell(':','align:L');
		$table2->easyCell('SD ISLAM AL-JANNAH','align:L');
		$table2->printRow();
		$table2->rowStyle('font-size:12');
		$table2->easyCell('KECAMATAN','align:R');
		$table2->easyCell(':','align:L');
		$table2->easyCell('GABUSWETAN','align:L');
		$table2->printRow();
		$table2->rowStyle('font-size:12');
		$table2->easyCell('KABUPATEN','align:R');
		$table2->easyCell(':','align:L');
		$table2->easyCell('INDRAMAYU','align:L');
		$table2->printRow();
		$table2->endTable(5);
		
		$table3=new easyTable($pdf, '{9,56,24,10,10,10,10,18,12,12,9,24,15}','align:L;border:1');
		$table3->rowStyle('font-size:9');
		$table3->easyCell('NO','rowspan:3;align:C');
		$table3->easyCell('MATA PELAJARAN','rowspan:3;align:C');
		$table3->easyCell('TARGET KURIKULUM (%)','rowspan:3;align:C');
		$table3->easyCell('NILAI','colspan:3;align:C');
		$table3->easyCell('KETUNTASAN','colspan:5;align:C');
		$table3->easyCell('TARAP SERAP KURIKULUM','rowspan:3;align:C');
		$table3->easyCell('KET','rowspan:3;align:C');
		$table3->printRow();
		$table3->rowStyle('font-size:9');
		$table3->easyCell($tipe,'colspan:3;align:C');
		$table3->easyCell('KKM','rowspan:2;align:C');
		$table3->easyCell('JUMLAH SISWA','rowspan:2;align:C');
		$table3->easyCell('NILAI','colspan:2;align:C');
		$table3->easyCell('%','rowspan:2;align:C');
		$table3->printRow();
		$table3->rowStyle('font-size:9');
		$table3->easyCell('NTT','align:C');
		$table3->easyCell('NTR','align:C');
		$table3->easyCell('RT2','align:C');
		$table3->easyCell('>= KKM','align:C');
		$table3->easyCell('< KKM','align:C');
		$table3->printRow();
		
		//PAI
		$idpel=1;
		if($tipe=="PTS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}elseif($tipe=="PAS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}else{
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		};
		$rerata=$nrata/$jsiswa['jumlah_siswa'];
		//$ntt=$connect->query("select min(id_rombel) as jumlah_siswa from penempatan where rombel='$kelas' and tapel='$tapel'")->fetch_assoc();
		
		$table3->rowStyle('font-size:9');
		$table3->easyCell('1','align:C');
		$table3->easyCell('Pendidikan Agama','align:L');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($nmax,0),'align:C');
		$table3->easyCell(number_format($nmin,0),'align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell($kkmsaya,'align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('0','align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell('Tuntas','align:C');
		$table3->printRow();
		
		//PKn
		$idpel=2;
		if($tipe=="PTS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}elseif($tipe=="PAS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}else{
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		};
		$rerata=$nrata/$jsiswa['jumlah_siswa'];
		//$ntt=$connect->query("select min(id_rombel) as jumlah_siswa from penempatan where rombel='$kelas' and tapel='$tapel'")->fetch_assoc();
		
		$table3->rowStyle('font-size:9');
		$table3->easyCell('2','align:C');
		$table3->easyCell('Pendidikan Kewarganegaraan','align:L');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($nmax,0),'align:C');
		$table3->easyCell(number_format($nmin,0),'align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell($kkmsaya,'align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('0','align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell('Tuntas','align:C');
		$table3->printRow();
		
		//Bahasa
		$idpel=3;
		if($tipe=="PTS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}elseif($tipe=="PAS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}else{
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		};
		$rerata=$nrata/$jsiswa['jumlah_siswa'];
		//$ntt=$connect->query("select min(id_rombel) as jumlah_siswa from penempatan where rombel='$kelas' and tapel='$tapel'")->fetch_assoc();
		
		$table3->rowStyle('font-size:9');
		$table3->easyCell('3','align:C');
		$table3->easyCell('Bahasa Indonesia','align:L');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($nmax,0),'align:C');
		$table3->easyCell(number_format($nmin,0),'align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell($kkmsaya,'align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('0','align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell('Tuntas','align:C');
		$table3->printRow();
		
		//Matematika
		$idpel=4;
		if($tipe=="PTS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}elseif($tipe=="PAS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}else{
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		};
		$rerata=$nrata/$jsiswa['jumlah_siswa'];
		//$ntt=$connect->query("select min(id_rombel) as jumlah_siswa from penempatan where rombel='$kelas' and tapel='$tapel'")->fetch_assoc();
		
		$table3->rowStyle('font-size:9');
		$table3->easyCell('4','align:C');
		$table3->easyCell('Matematika','align:L');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($nmax,0),'align:C');
		$table3->easyCell(number_format($nmin,0),'align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell($kkmsaya,'align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('0','align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell('Tuntas','align:C');
		$table3->printRow();
		
		//IPA
		if($ab>3){
		$idpel=5;
		if($tipe=="PTS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}elseif($tipe=="PAS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}else{
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		};
		$rerata=$nrata/$jsiswa['jumlah_siswa'];
		//$ntt=$connect->query("select min(id_rombel) as jumlah_siswa from penempatan where rombel='$kelas' and tapel='$tapel'")->fetch_assoc();
		
		$table3->rowStyle('font-size:9');
		$table3->easyCell('5','align:C');
		$table3->easyCell('Ilmu Pengetahuan Alam','align:L');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($nmax,0),'align:C');
		$table3->easyCell(number_format($nmin,0),'align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell($kkmsaya,'align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('0','align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell('Tuntas','align:C');
		$table3->printRow();
		}else{
			$table3->rowStyle('font-size:9');
			$table3->easyCell('5','align:C');
			$table3->easyCell('Ilmu Pengetahuan Alam','align:L');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->printRow();	
		};
		
		//IPS
		if($ab>3){
		$idpel=6;
		if($tipe=="PTS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}elseif($tipe=="PAS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}else{
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		};
		$rerata=$nrata/$jsiswa['jumlah_siswa'];
		//$ntt=$connect->query("select min(id_rombel) as jumlah_siswa from penempatan where rombel='$kelas' and tapel='$tapel'")->fetch_assoc();
		
		$table3->rowStyle('font-size:9');
		$table3->easyCell('6','align:C');
		$table3->easyCell('Ilmu Pengetahuan Sosial','align:L');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($nmax,0),'align:C');
		$table3->easyCell(number_format($nmin,0),'align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell($kkmsaya,'align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('0','align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell('Tuntas','align:C');
		$table3->printRow();
		}else{
			$table3->rowStyle('font-size:9');
			$table3->easyCell('6','align:C');
			$table3->easyCell('Ilmu Pengetahuan Sosial','align:L');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->easyCell('','bgcolor:#acaeaf;');
			$table3->printRow();	
		};
		
		//SBdP
		$idpel=7;
		if($tipe=="PTS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}elseif($tipe=="PAS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}else{
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		};
		$rerata=$nrata/$jsiswa['jumlah_siswa'];
		//$ntt=$connect->query("select min(id_rombel) as jumlah_siswa from penempatan where rombel='$kelas' and tapel='$tapel'")->fetch_assoc();
		
		$table3->rowStyle('font-size:9');
		$table3->easyCell('7','align:C');
		$table3->easyCell('Seni Budaya dan Prakarya','align:L');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($nmax,0),'align:C');
		$table3->easyCell(number_format($nmin,0),'align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell($kkmsaya,'align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('0','align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell('Tuntas','align:C');
		$table3->printRow();
		
		//PJOK
		$idpel=8;
		if($tipe=="PTS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nuts where id_pd='$ids' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}elseif($tipe=="PAS"){
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='1' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		}else{
			$skl = "select * from penempatan where rombel='$kelas' and tapel='$tapel'";
			$qkl = $connect->query($skl);
			$nmin=100;
			$nmax=0;
			$nrata=0;
			while($sis=$qkl->fetch_assoc()){
				$ids=$sis['peserta_didik_id'];
				$nsis=$connect->query("select MIN(nilai) as nilai_min from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis['nilai_min']>$nmin){
					$nmin=$nmin;
				}else{
					$nmin=$nsis['nilai_min'];
				};
				$nsis1=$connect->query("select MAX(nilai) as nilai_max from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				if($nsis1['nilai_max']>$nmax){
					$nmax=$nsis1['nilai_max'];
				}else{
					$nmax=$nmax;
				};
				$nsis2=$connect->query("select AVG(nilai) as nilai_rata from nats where id_pd='$ids' and kelas='$ab' and smt='2' and tapel='$tapel' and mapel='$idpel'")->fetch_assoc();
				$nrata=$nrata+$nsis2['nilai_rata'];
			};
		};
		$rerata=$nrata/$jsiswa['jumlah_siswa'];
		//$ntt=$connect->query("select min(id_rombel) as jumlah_siswa from penempatan where rombel='$kelas' and tapel='$tapel'")->fetch_assoc();
		
		$table3->rowStyle('font-size:9');
		$table3->easyCell('8','align:C');
		$table3->easyCell('Pendidikan Jasmani Olahraga dan Kesehatan','align:L');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($nmax,0),'align:C');
		$table3->easyCell(number_format($nmin,0),'align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell($kkmsaya,'align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('0','align:C');
		$table3->easyCell($jsiswa['jumlah_siswa'],'align:C');
		$table3->easyCell('100','align:C');
		$table3->easyCell(number_format($rerata,0),'align:C');
		$table3->easyCell('Tuntas','align:C');
		$table3->printRow();
		
		$table3->rowStyle('font-size:9');
		$table3->easyCell('','align:C;bgcolor:#acaeaf;');
		$table3->easyCell('Muatan Lokal','align:L;bgcolor:#acaeaf;');
		$table3->easyCell('','align:C;bgcolor:#acaeaf;');
		$table3->easyCell('','align:C;bgcolor:#acaeaf;');
		$table3->easyCell('','align:C;bgcolor:#acaeaf;');
		$table3->easyCell('','align:C;bgcolor:#acaeaf;');
		$table3->easyCell('','align:C;bgcolor:#acaeaf;');
		$table3->easyCell('','align:C;bgcolor:#acaeaf;');
		$table3->easyCell('','align:C;bgcolor:#acaeaf;');
		$table3->easyCell('','align:C;bgcolor:#acaeaf;');
		$table3->easyCell('','align:C;bgcolor:#acaeaf;');
		$table3->easyCell('','align:C;bgcolor:#acaeaf;');
		$table3->easyCell('','align:C;bgcolor:#acaeaf;');
		$table3->printRow();
		
		$table3->endTable();
		//selesai isi tabel siswa
		
		
		//Tertanda Wali Kelas 
		$ttd=new easyTable($pdf, '{50,71,6,72,111}');
		$ttd->rowStyle('font-size:12');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('Gabuswetan, ............................ 20.......','align:C; valign:T');
		$ttd->printRow();
		
		$ttd->rowStyle('font-size:12');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('Guru Kelas '.$kelas,'align:C; valign:T');
		$ttd->printRow();
		
		$ttd->rowStyle('font-size:12');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->printRow();
		
		$ttd->rowStyle('font-size:12');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->printRow();
		
		$ttd->rowStyle('font-size:12');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->printRow();
		
		$ttd->rowStyle('font-size:12');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->printRow();
		
		$ttd->rowStyle('font-size:12');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->printRow();
		
		$ttd->rowStyle('font-size:12');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('');
		$ttd->easyCell('___________________________','align:C; valign:T');
		$ttd->printRow();
		$ttd->endTable();
		
		//	$pdf->Output('D',$namafilenya);
		 $pdf->Output();


 

?>