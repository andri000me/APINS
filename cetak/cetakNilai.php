<?php
 include 'fpdf/fpdf.php';
 include 'exfpdf.php';
 include 'easyTable.php';
 include '../../inc/db_connect.php';
 function TanggalIndo($tanggal)
{
	$bulan = array ('Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split = explode('-', $tanggal);
	return $split[2] . ' ' . $bulan[ (int)$split[1]-1 ] . ' ' . $split[0];
};
$idp=$_GET['idp'];
$kelas=$_GET['kelas'];
$smt=$_GET['smt'];
$ab=substr($kelas, 0, 1);
$tapel=$_GET['tapel'];
$sqls = "select * from siswa where peserta_didik_id='$idp'";
$querys = $connect->query($sqls);
$siswa=$querys->fetch_assoc();
$rombs=$connect->query("select * from penempatan where peserta_didik_id='$idp' and tapel='$tapel'")->fetch_assoc();
$namafilenya="Raport ".$siswa['nama']." Semester ".$smt.".pdf";
 $pdf=new exFPDF();
 $pdf->AddPage(); 
 $pdf->SetFont('helvetica','',12);

 $table2=new easyTable($pdf, 1);
 $table2->rowStyle('font-size:15; font-style:B;');
 $table2->easyCell('','img:tutwuri.jpg,w35;align:C');
 $table2->printRow();
 $table2->endTable(5);
 
 $table2=new easyTable($pdf, 1);
 $table2->rowStyle('font-size:20; font-style:B;');
 $table2->easyCell('RAPOR', 'align:C;');
 $table2->printRow();
 $table2->rowStyle('font-size:20; font-style:B;');
 $table2->easyCell('PESERTA DIDIK', 'align:C;');
 $table2->printRow();
 $table2->rowStyle('font-size:20; font-style:B;');
 $table2->easyCell('SEKOLAH DASAR', 'align:C;');
 $table2->printRow();
 $table2->rowStyle('font-size:20; font-style:B;');
 $table2->easyCell('(SD)', 'align:C;');
 $table2->printRow();
 $table2->endTable(100);
 
 $table2=new easyTable($pdf, 1);
 $table2->rowStyle('font-size:12');
 $table2->easyCell('Nama Peserta Didik', 'align:C;');
 $table2->printRow();
 $table2->rowStyle('font-size:16; font-style:B;border:1');
 $table2->easyCell($siswa['nama'], 'align:C;');
 $table2->printRow();
 $table2->rowStyle('font-size:12');
 $table2->easyCell('NIS', 'align:C;');
 $table2->printRow();
 $table2->rowStyle('font-size:16; font-style:B;border:1');
 $table2->easyCell($siswa['nis'], 'align:C;');
 $table2->printRow();
 $table2->rowStyle('font-size:12');
 $table2->easyCell('NISN', 'align:C;');
 $table2->printRow();
 $table2->rowStyle('font-size:16; font-style:B;border:1');
 $table2->easyCell($siswa['nisn'], 'align:C;');
 $table2->printRow();
 $table2->endTable(20);
 
 $table2=new easyTable($pdf, 1);
 $table2->rowStyle('font-size:14; font-style:B;');
 $table2->easyCell('KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN', 'align:C;');
 $table2->printRow();
 $table2->rowStyle('font-size:14; font-style:B;');
 $table2->easyCell('REPUBLIK INDONESIA', 'align:C;');
 $table2->printRow();
 $table2->endTable();

//halaman 2
 $pdf->AddPage(); 
 $pdf->SetFont('helvetica','',12);

 $table2=new easyTable($pdf, 1);
 $table2->rowStyle('font-size:15; font-style:B;');
 $table2->easyCell('IDENTITAS PESERTA DIDIK', 'align:C;');
 $table2->printRow();
 $table2->endTable(10);
 
 $table3=new easyTable($pdf, '{60, 8, 1, 110}','align:L');
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Nama Peserta Didik');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell($siswa['nama'],'border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('NIS');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell($siswa['nis'],'border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('NISN');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell($siswa['nisn'],'border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Tempat, Tanggal Lahir');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell($siswa['tempat'].', '.TanggalIndo($siswa['tanggal']),'border:B;font-style:B');
 $table3->printRow();
 
 if($siswa['jk']==="L"){
	 $kelam="Laki-laki";
 }else{
	 $kelam="Perempuan";
 };
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Jenis Kelamin');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell($kelam,'border:B;font-style:B');
 $table3->printRow();
 
 $idag=$siswa['agama'];
 $pag=$connect->query("select * from agama where id_agama='$idag'")->fetch_assoc();
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Agama');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell($pag['nama_agama'],'border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Pendidikan Sebelumnya');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell($siswa['pend_sebelum'],'border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Alamat Peserta Didik');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell($siswa['alamat'],'border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('.');
 $table3->easyCell('');
 $table3->easyCell('');
 $table3->easyCell('','border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Nama Orang Tua');
 $table3->easyCell('');
 $table3->easyCell('');
 $table3->easyCell('');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Ayah');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell($siswa['nama_ayah'],'border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Ibu');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell($siswa['nama_ibu'],'border:B;font-style:B');
 $table3->printRow();
 
 $idpa=$siswa['pek_ayah'];
 $peka=$connect->query("select * from pekerjaan where id_pekerjaan='$idpa'")->fetch_assoc();
 $idpi=$siswa['pek_ibu'];
 $peki=$connect->query("select * from pekerjaan where id_pekerjaan='$idpi'")->fetch_assoc();
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Pekerjaan Orang Tua');
 $table3->easyCell('');
 $table3->easyCell('');
 $table3->easyCell('');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Ayah');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell($peka['nama_pekerjaan'],'border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Ibu');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell($peki['nama_pekerjaan'],'border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Alamat Orang Tua');
 $table3->easyCell('');
 $table3->easyCell('');
 $table3->easyCell('');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Jalan');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell($siswa['jalan'],'border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Kelurahan/Desa');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell($siswa['kelurahan'],'border:B;font-style:B');
 $table3->printRow();
 
 $idkec=$siswa['kecamatan'];
 $keca=$connect->query("select * from kecamatan where id_kecamatan='$idkec'")->fetch_assoc();
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Kecamatan');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell($keca['nama_kecamatan'],'border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Kabupaten/Kota');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell('Indramayu','border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Provinsi');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell('Jawa Barat','border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Wali Peserta Didik');
 $table3->easyCell('');
 $table3->easyCell('');
 $table3->easyCell('');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Nama');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell('','border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Pekerjaan');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell('','border:B;font-style:B');
 $table3->printRow();
 
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Alamat');
 $table3->easyCell(':');
 $table3->easyCell('');
 $table3->easyCell('','border:B;font-style:B');
 $table3->printRow();
 $table3->endTable(15);
 
 $table3=new easyTable($pdf, '{10, 30, 20, 8, 1, 110}','align:L');
 $table3->rowStyle('font-size:12;min-height:40');
 $table3->easyCell('');
 $table3->easyCell("Pas Foto\nUkuran\n3x4",'border:1;align:C;valign:M;font-style:B');
 $table3->easyCell('');
 $table3->easyCell('');
 $table3->easyCell('');
 $table3->easyCell(".....................................................\nKepala Sekolah\n\n\n\n\n\n_________________________________\nNIP. .........................");
 $table3->printRow();
 $table3->endTable(15);
 

//halaman 3
 $pdf->AddPage(); 
 $pdf->SetFont('helvetica','',12);

 $table2=new easyTable($pdf, 1);
 $table2->rowStyle('font-size:15; font-style:B;');
 $table2->easyCell('RAPOR PESERTA DIDIK DAN PROFIL PESERTA DIDIK', 'align:C;');
 $table2->printRow();
 $table2->endTable(5);
 
 $table3=new easyTable($pdf, '{80, 8, 140, 70, 8, 60}','align:L');
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Nama Peserta Didik');
 $table3->easyCell(':');
 $table3->easyCell($siswa['nama']);
 $table3->easyCell('Kelas');
 $table3->easyCell(':');
 $table3->easyCell($rombs['rombel']);
 $table3->printRow();
 $table3->rowStyle('font-size:12');
 $table3->easyCell('NISN/NIS');
 $table3->easyCell(':');
 $table3->easyCell($siswa['nisn'].'/'.$siswa['nis']);
 $table3->easyCell('Semester');
 $table3->easyCell(':');
 $table3->easyCell($smt);
 $table3->printRow();
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Nama Sekolah');
 $table3->easyCell(':');
 $table3->easyCell('SD ISLAM AL-JANNAH');
 $table3->easyCell('Tahun Pelajaran');
 $table3->easyCell(':');
 $table3->easyCell($tapel);
 $table3->printRow();
 $table3->rowStyle('font-size:12');
 $table3->easyCell('Alamat Sekolah');
 $table3->easyCell(':');
 $table3->easyCell('Jl. Raya Gabuswetan No. 1 Gabuswetan Indramayu','colspan:4');
 $table3->printRow();
 $table3->endTable(10);
 
//====================================================================
$table4=new easyTable($pdf, '{8,100}', 'align:L');
$table4->rowStyle('font-size:12; font-style:B;');
$table4->easyCell('A.');
$table4->easyCell('Sikap');
$table4->printRow();
$table4->endTable(3);

// Sikap spiritual
$nsp=$connect->query("select * from deskripsi_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and jns='k1'")->fetch_assoc();
$nso=$connect->query("select * from deskripsi_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and jns='k2'")->fetch_assoc();
$sikap=new easyTable($pdf,2, 'border:1');
$sikap->rowStyle('font-size:14; font-style:B; bgcolor:#BEBEBE');
$sikap->easyCell('Deskripsi','colspan:2; align:C');
$sikap->printRow();
$sikap->rowStyle('font-size:12; min-height:35');
$sikap->easyCell("1. Sikap Spiritual",'valign:T');
$sikap->easyCell($nsp['deskripsi'],'valign:T');
$sikap->printRow();
$sikap->rowStyle('font-size:12; min-height:35');
$sikap->easyCell("2. Sikap Sosial",'valign:T');
$sikap->easyCell($nso['deskripsi'],'valign:T');
$sikap->printRow();
$sikap->endTable(10);

//Isi KKM
$kkm=$connect->query("select min(nilai) as kkmsekolah from kkm where tapel='$tapel'")->fetch_assoc();
if(empty($kkm['kkmsekolah'])){
	$kkmsaya=0;
}else{
	$kkmsaya=$kkm['kkmsekolah'];
};
$table5=new easyTable($pdf, '{8,100}', 'align:L');
$table5->rowStyle('font-size:12; font-style:B;');
$table5->easyCell('B.');
$table5->easyCell('Pengetahuan dan Ketrampilan');
$table5->printRow();
$table5->easyCell(' ');
$table5->easyCell('KKM Satuan Pendidikan : '.$kkmsaya);
$table5->printRow();
$table5->endTable(3);
//Isi Raport
$rapo=new easyTable($pdf, '{10, 50, 15, 25, 50, 15, 25, 50}', 'border:1');
$rapo->rowStyle('font-size:12; font-style:B; bgcolor:#BEBEBE');
$rapo->easyCell('No','rowspan:2; align:C; valign:M');
$rapo->easyCell('Muatan Pelajaran','rowspan:2; align:C; valign:M');
$rapo->easyCell('Pengetahuan','colspan:3; align:C; valign:M');
$rapo->easyCell('Ketrampilan','colspan:3; align:C; valign:M');
$rapo->printRow();
$rapo->rowStyle('font-size:12; font-style:B; bgcolor:#BEBEBE');
$rapo->easyCell('Nilai','align:C; valign:M');
$rapo->easyCell('Predikat','align:C; valign:M');
$rapo->easyCell('Deskripsi','align:C; valign:M');
$rapo->easyCell('Nilai','align:C; valign:M');
$rapo->easyCell('Predikat','align:C; valign:M');
$rapo->easyCell('Deskripsi','align:C; valign:M');
$rapo->printRow(true);
//mulai cetak PAI
$npe=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='1' and jns='k3'")->fetch_assoc();
$nke=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='1' and jns='k4'")->fetch_assoc();
$rapo->rowStyle('font-size:10; min-height:75');
$rapo->easyCell('1','align:C; valign:T');
$rapo->easyCell('Pendidikan Agama dan Budi Pekerti','valign:T');
$rapo->easyCell(number_format($npe['nilai'],0),'align:C; valign:T');
$rapo->easyCell($npe['predikat'],'align:C; valign:T');
$rapo->easyCell($npe['deskripsi'],'valign:T');
$rapo->easyCell(number_format($nke['nilai'],0),'align:C; valign:T');
$rapo->easyCell($nke['predikat'],'align:C; valign:T');
$rapo->easyCell($nke['deskripsi'],'valign:T');
$rapo->printRow();

//mulai cetak PKn
$npe=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='2' and jns='k3'")->fetch_assoc();
$nke=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='2' and jns='k4'")->fetch_assoc();
$rapo->rowStyle('font-size:10; min-height:75');
$rapo->easyCell('2','align:C; valign:T');
$rapo->easyCell('Pendidikan Pancasila dan Kewarganegaraan','valign:T');
$rapo->easyCell(number_format($npe['nilai'],0),'align:C; valign:T');
$rapo->easyCell($npe['predikat'],'align:C; valign:T');
$rapo->easyCell($npe['deskripsi'],'valign:T');
$rapo->easyCell(number_format($nke['nilai'],0),'align:C; valign:T');
$rapo->easyCell($nke['predikat'],'align:C; valign:T');
$rapo->easyCell($nke['deskripsi'],'valign:T');
$rapo->printRow();

//mulai cetak Bahasa Indonesia
$npe=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='3' and jns='k3'")->fetch_assoc();
$nke=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='3' and jns='k4'")->fetch_assoc();
$rapo->rowStyle('font-size:10; min-height:75');
$rapo->easyCell('3','align:C; valign:T');
$rapo->easyCell('Bahasa Indonesia','valign:T');
$rapo->easyCell(number_format($npe['nilai'],0),'align:C; valign:T');
$rapo->easyCell($npe['predikat'],'align:C; valign:T');
$rapo->easyCell($npe['deskripsi'],'valign:T');
$rapo->easyCell(number_format($nke['nilai'],0),'align:C; valign:T');
$rapo->easyCell($nke['predikat'],'align:C; valign:T');
$rapo->easyCell($nke['deskripsi'],'valign:T');
$rapo->printRow();

//mulai cetak Matematika
$npe=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='4' and jns='k3'")->fetch_assoc();
$nke=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='4' and jns='k4'")->fetch_assoc();
$rapo->rowStyle('font-size:10; min-height:75');
$rapo->easyCell('4','align:C; valign:T');
$rapo->easyCell('Matematika','valign:T');
$rapo->easyCell(number_format($npe['nilai'],0),'align:C; valign:T');
$rapo->easyCell($npe['predikat'],'align:C; valign:T');
$rapo->easyCell($npe['deskripsi'],'valign:T');
$rapo->easyCell(number_format($nke['nilai'],0),'align:C; valign:T');
$rapo->easyCell($nke['predikat'],'align:C; valign:T');
$rapo->easyCell($nke['deskripsi'],'valign:T');
$rapo->printRow();

//mulai cetak IPA
$npe=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='5' and jns='k3'")->fetch_assoc();
$nke=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='5' and jns='k4'")->fetch_assoc();
$rapo->rowStyle('font-size:10; min-height:75');
$rapo->easyCell('5','align:C; valign:T');
$rapo->easyCell('Ilmu Pengetahuan Alam','valign:T');
$rapo->easyCell(number_format($npe['nilai'],0),'align:C; valign:T');
$rapo->easyCell($npe['predikat'],'align:C; valign:T');
$rapo->easyCell($npe['deskripsi'],'valign:T');
$rapo->easyCell(number_format($nke['nilai'],0),'align:C; valign:T');
$rapo->easyCell($nke['predikat'],'align:C; valign:T');
$rapo->easyCell($nke['deskripsi'],'valign:T');
$rapo->printRow();
//mulai cetak IPS
$npe=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='6' and jns='k3'")->fetch_assoc();
$nke=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='6' and jns='k4'")->fetch_assoc();
$rapo->rowStyle('font-size:10; min-height:75');
$rapo->easyCell('6','align:C; valign:T');
$rapo->easyCell('Ilmu Pengetahuan Sosial','valign:T');
$rapo->easyCell(number_format($npe['nilai'],0),'align:C; valign:T');
$rapo->easyCell($npe['predikat'],'align:C; valign:T');
$rapo->easyCell($npe['deskripsi'],'valign:T');
$rapo->easyCell(number_format($nke['nilai'],0),'align:C; valign:T');
$rapo->easyCell($nke['predikat'],'align:C; valign:T');
$rapo->easyCell($nke['deskripsi'],'valign:T');
$rapo->printRow();
//mulai cetak SBK
$npe=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='7' and jns='k3'")->fetch_assoc();
$nke=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='7' and jns='k4'")->fetch_assoc();
$rapo->rowStyle('font-size:10; min-height:75');
$rapo->easyCell('7','align:C; valign:T');
$rapo->easyCell('Seni Budaya dan Prakarya','valign:T');
$rapo->easyCell(number_format($npe['nilai'],0),'align:C; valign:T');
$rapo->easyCell($npe['predikat'],'align:C; valign:T');
$rapo->easyCell($npe['deskripsi'],'valign:T');
$rapo->easyCell(number_format($nke['nilai'],0),'align:C; valign:T');
$rapo->easyCell($nke['predikat'],'align:C; valign:T');
$rapo->easyCell($nke['deskripsi'],'valign:T');
$rapo->printRow();	
//mulai cetak PJOK
$npe=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='8' and jns='k3'")->fetch_assoc();
$nke=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='8' and jns='k4'")->fetch_assoc();
$rapo->rowStyle('font-size:10; min-height:75');
$rapo->easyCell('8','align:C; valign:T');
$rapo->easyCell('Pendidikan Jasmani, Olahraga dan Kesehatan','valign:T');
$rapo->easyCell(number_format($npe['nilai'],0),'align:C; valign:T');
$rapo->easyCell($npe['predikat'],'align:C; valign:T');
$rapo->easyCell($npe['deskripsi'],'valign:T');
$rapo->easyCell(number_format($nke['nilai'],0),'align:C; valign:T');
$rapo->easyCell($nke['predikat'],'align:C; valign:T');
$rapo->easyCell($nke['deskripsi'],'valign:T');
$rapo->printRow();
//MULOK
$rapo->rowStyle('font-size:10');
$rapo->easyCell('9','align:C; valign:T');
$rapo->easyCell('Muatan Lokal','colspan:7; valign:T');
$rapo->printRow();

//mulai cetak Pendidikan Budi Pekerti
$npe=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='11' and jns='k3'")->fetch_assoc();
$nke=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='11' and jns='k4'")->fetch_assoc();
$rapo->rowStyle('font-size:10; min-height:75');
$rapo->easyCell('','align:C; valign:T');
$rapo->easyCell('a. Pendidikan Budi Pekerti','valign:T');
$rapo->easyCell(number_format($npe['nilai'],0),'align:C; valign:T');
$rapo->easyCell($npe['predikat'],'align:C; valign:T');
$rapo->easyCell($npe['deskripsi'],'valign:T');
$rapo->easyCell(number_format($nke['nilai'],0),'align:C; valign:T');
$rapo->easyCell($nke['predikat'],'align:C; valign:T');
$rapo->easyCell($nke['deskripsi'],'valign:T');
$rapo->printRow();
//mulai cetak Bahasa Indramayu
$npe=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='9' and jns='k3'")->fetch_assoc();
$nke=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='9' and jns='k4'")->fetch_assoc();
$rapo->rowStyle('font-size:10; min-height:75');
$rapo->easyCell('','align:C; valign:T');
$rapo->easyCell('b. Bahasa Indramayu','valign:T');
$rapo->easyCell(number_format($npe['nilai'],0),'align:C; valign:T');
$rapo->easyCell($npe['predikat'],'align:C; valign:T');
$rapo->easyCell($npe['deskripsi'],'valign:T');
$rapo->easyCell(number_format($nke['nilai'],0),'align:C; valign:T');
$rapo->easyCell($nke['predikat'],'align:C; valign:T');
$rapo->easyCell($nke['deskripsi'],'valign:T');
$rapo->printRow();
//mulai cetak Bahasa Inggris
$npe=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='10' and jns='k3'")->fetch_assoc();
$nke=$connect->query("select * from raport_k13 where id_pd='$idp' and kelas='$ab' and smt='$smt' and tapel='$tapel' and mapel='10' and jns='k4'")->fetch_assoc();
$rapo->rowStyle('font-size:10; min-height:75');
$rapo->easyCell('','align:C; valign:T');
$rapo->easyCell('c. Bahasa Inggris','valign:T');
$rapo->easyCell(number_format($npe['nilai'],0),'align:C; valign:T');
$rapo->easyCell($npe['predikat'],'align:C; valign:T');
$rapo->easyCell($npe['deskripsi'],'valign:T');
$rapo->easyCell(number_format($nke['nilai'],0),'align:C; valign:T');
$rapo->easyCell($nke['predikat'],'align:C; valign:T');
$rapo->easyCell($nke['deskripsi'],'valign:T');
$rapo->printRow();
$rapo->endTable(5);

$pdf->AddPage();
//Ekstrakurikuler
$table6=new easyTable($pdf, '{8,100}', 'align:L');
$table6->rowStyle('font-size:12; font-style:B;');
$table6->easyCell('C.');
$table6->easyCell('Ekstra Kurikuler');
$table6->printRow();
$table6->endTable(3);

$eks=new easyTable($pdf, '{20, 100, 200}', 'border:1');
$eks->rowStyle('font-size:12; font-style:B; bgcolor:#BEBEBE; min-height:10');
$eks->easyCell('No.','align:C; valign:M');
$eks->easyCell('Kegiatan Ekstrakurikuler','align:C; valign:M');
$eks->easyCell('Keterangan','align:C; valign:M');
$eks->printRow();
$eks->rowStyle('font-size:12; min-height:15');
$eks->easyCell('1.');
$eks->easyCell('Praja Muda Karana (Pramuka)');
$eks->easyCell('');
$eks->printRow();
$eks->rowStyle('font-size:12; min-height:15');
$eks->easyCell('2.');
$eks->easyCell('');
$eks->easyCell('');
$eks->printRow();
$eks->rowStyle('font-size:12; min-height:15');
$eks->easyCell('3.');
$eks->easyCell('');
$eks->easyCell('');
$eks->printRow();
$eks->endTable();

//Saran
$table7=new easyTable($pdf, '{8,100}', 'align:L');
$table7->rowStyle('font-size:12; font-style:B;');
$table7->easyCell('D.');
$table7->easyCell('Saran-saran');
$table7->printRow();
$table7->endTable(3);
$srn=new easyTable($pdf, 1, 'border:1');
$srn->rowStyle('font-size:12; font-style:B; min-height:35');
$srn->easyCell('');
$srn->printRow();
$srn->endTable(5);

//Tinggi Berat Badan
$tbb1=$connect->query("select * from data_kesehatan where peserta_didik_id='$idp' and smt='1' and tapel='$tapel'")->fetch_assoc();
$tbb2=$connect->query("select * from data_kesehatan where peserta_didik_id='$idp' and smt='2' and tapel='$tapel'")->fetch_assoc();
$table8=new easyTable($pdf, '{8,100}', 'align:L');
$table8->rowStyle('font-size:12; font-style:B;');
$table8->easyCell('E.');
$table8->easyCell('Tinggi dan Berat Badan');
$table8->printRow();
$table8->endTable(3);
$tbn=new easyTable($pdf, '{20, 100, 50, 50}', 'border:1');
$tbn->rowStyle('font-size:12; font-style:B; bgcolor:#BEBEBE; min-height:7');
$tbn->easyCell('No.','rowspan:2;align:C; valign:M');
$tbn->easyCell('Aspek yang Dinilai','rowspan:2;align:C; valign:M');
$tbn->easyCell('Semester','colspan:2; align:C; valign:M');
$tbn->printRow();
$tbn->rowStyle('font-size:12; font-style:B; bgcolor:#BEBEBE; min-height:7');
$tbn->easyCell('1','align:C; valign:M');
$tbn->easyCell('2','align:C; valign:M');
$tbn->printRow();
$tbn->rowStyle('font-size:12; min-height:10');
$tbn->easyCell('1.','align:L; valign:T');
$tbn->easyCell('Tinggi Badan','align:L; valign:T');
$tbn->easyCell($tbb1['tinggi'],'align:C; valign:M');
$tbn->easyCell($tbb2['tinggi'],'align:C; valign:M');
$tbn->printRow();
$tbn->rowStyle('font-size:12; min-height:10');
$tbn->easyCell('2.','align:L; valign:T');
$tbn->easyCell('Berat Badan','align:L; valign:T');
$tbn->easyCell($tbb1['berat'],'align:C; valign:M');
$tbn->easyCell($tbb2['berat'],'align:C; valign:M');
$tbn->printRow();
$tbn->endTable(5);
//Kesehatan
$table9=new easyTable($pdf, '{8,100}', 'align:L');
$table9->rowStyle('font-size:12; font-style:B;');
$table9->easyCell('F.');
$table9->easyCell('Kesehatan');
$table9->printRow();
$table9->endTable(3);
$kes=new easyTable($pdf, '{20, 100, 100}', 'border:1');
$kes->rowStyle('font-size:12; font-style:B; bgcolor:#BEBEBE; min-height:7');
$kes->easyCell('No.','align:C; valign:M');
$kes->easyCell('Aspek Fisik','align:C; valign:M');
$kes->easyCell('Keterangan','align:C; valign:M');
$kes->printRow();

$kes->rowStyle('font-size:12; min-height:15');
$kes->easyCell('1.','align:L; valign:T');
$kes->easyCell('Pendengaran','align:L; valign:T');
$kes->easyCell($tbb2['pendengaran'],'align:C; valign:M');
$kes->printRow();
$kes->rowStyle('font-size:12; min-height:15');
$kes->easyCell('2.','align:L; valign:T');
$kes->easyCell('Penglihatan','align:L; valign:T');
$kes->easyCell($tbb2['penglihatan'],'align:C; valign:M');
$kes->printRow();
$kes->rowStyle('font-size:12; min-height:15');
$kes->easyCell('3.','align:L; valign:T');
$kes->easyCell('Gigi','align:L; valign:T');
$kes->easyCell($tbb2['gigi'],'align:C; valign:M');
$kes->printRow();
$kes->rowStyle('font-size:12; min-height:15');
$kes->easyCell('4.','align:L; valign:T');
$kes->easyCell("Lainnya\n\n..................................",'align:L; valign:T');
$kes->easyCell($tbb2['lainnya'],'align:C; valign:M');
$kes->printRow();
$kes->endTable(5);

$pdf->AddPage();

//Prestasi
$prest=$connect->query("select * from data_prestasi where peserta_didik_id='$idp' and smt='$smt' and tapel='$tapel'")->fetch_assoc();
$table10=new easyTable($pdf, '{8,100}', 'align:L');
$table10->rowStyle('font-size:12; font-style:B;');
$table10->easyCell('G.');
$table10->easyCell('Prestasi');
$table10->printRow();
$table10->endTable(3);
$pres=new easyTable($pdf, '{20, 75, 125}', 'border:1');
$pres->rowStyle('font-size:12; font-style:B; bgcolor:#BEBEBE; min-height:7');
$pres->easyCell('No.','align:C; valign:M');
$pres->easyCell('Jenis Prestasi','align:C; valign:M');
$pres->easyCell('Keterangan','align:C; valign:M');
$pres->printRow();

$pres->rowStyle('font-size:12; min-height:15');
$pres->easyCell('1.','align:L; valign:T');
$pres->easyCell('Kesenian','align:L; valign:T');
$pres->easyCell($prest['kesenian'],'align:L; valign:T');
$pres->printRow();
$pres->rowStyle('font-size:12; min-height:15');
$pres->easyCell('2.','align:L; valign:T');
$pres->easyCell('Olahraga','align:L; valign:T');
$pres->easyCell($prest['olahraga'],'align:L; valign:T');
$pres->printRow();
$pres->rowStyle('font-size:12; min-height:15');
$pres->easyCell('3.','align:L; valign:T');
$pres->easyCell('Akademik','align:L; valign:T');
$pres->easyCell($prest['akademik'],'align:L; valign:T');
$pres->printRow();
$pres->endTable(5);

if($smt==1){
//Absensi
$table11=new easyTable($pdf, '{8,100}', 'align:L');
$table11->rowStyle('font-size:12; font-style:B;');
$table11->easyCell('H.');
$table11->easyCell('Ketidakhadiran');
$table11->printRow();
$table11->endTable(3);
$hadir=new easyTable($pdf, '{50, 10, 20}', 'align:L');
$hadir->rowStyle('font-size:12; min-height:7');
$hadir->easyCell('Sakit','align:L');
$hadir->easyCell(':','align:L');
$hadir->easyCell('    Hari','align:L');
$hadir->printRow();
$hadir->rowStyle('font-size:12; min-height:7');
$hadir->easyCell('Ijin','align:L');
$hadir->easyCell(':','align:L');
$hadir->easyCell('    Hari','align:L');
$hadir->printRow();
$hadir->rowStyle('font-size:12; min-height:7');
$hadir->easyCell('Tanpa Keterangan','align:L');
$hadir->easyCell(':','align:L');
$hadir->easyCell('    Hari','align:L');
$hadir->printRow();
$hadir->endTable(10);
}else{
//Absensi
$table11=new easyTable($pdf, '{8,100}', 'align:L');
$table11->rowStyle('font-size:12; font-style:B;');
$table11->easyCell('H.');
$table11->easyCell('Ketidakhadiran');
$table11->printRow();
$table11->endTable(3);
$hadir=new easyTable($pdf, '{50, 10, 20, 100}', 'split-row:true; align:L; border:1');
$hadir->easyCell('Sakit','align:L; border:0;');
$hadir->easyCell(':','align:L; border:0;');
$hadir->easyCell('    Hari','align:L; border:0;');
if($ab==6){
    $hadir->easyCell("Keputusan:\nBerdasarkan Pencapaian seluruh Kompetensi,\npeserta didik dinyatakan:\n\nLulus/Tidak Lulus dari SD ......................................\n\n*) Coret yang tidak perlu.",'rowspan:5; align:L; valign:T');
}
else{
    $hadir->easyCell("Keputusan:\nBerdasarkan Pencapaian seluruh Kompetensi,\npeserta didik dinyatakan:\n\nNaik/Tinggal*) Kelas ....... (............)\n\n*) Coret yang tidak perlu.",'rowspan:5; align:L; valign:T');
};
$hadir->printRow();
$hadir->rowStyle('font-size:12; min-height:7');
$hadir->easyCell('Ijin','align:L; border:0;');
$hadir->easyCell(':','align:L; border:0;');
$hadir->easyCell('    Hari','align:L; border:0;');
$hadir->printRow();
$hadir->rowStyle('font-size:12; min-height:7');
$hadir->easyCell('Tanpa Keterangan','align:L; border:0;');
$hadir->easyCell(':','align:L; border:0;');
$hadir->easyCell('    Hari','align:L; border:0;');
$hadir->printRow();
$hadir->rowStyle('font-size:12; min-height:7');
$hadir->easyCell('','align:L; border:0;');
$hadir->easyCell('','align:L; border:0;');
$hadir->easyCell('','align:L; border:0;');
$hadir->printRow();
$hadir->rowStyle('font-size:12; min-height:7');
$hadir->easyCell('','align:L; border:0;');
$hadir->easyCell('','align:L; border:0;');
$hadir->easyCell('','align:L; border:0;');
$hadir->printRow();
$hadir->endTable(10);	
};
//TTD
$ttd=new easyTable($pdf, 2);
$ttd->rowStyle('font-size:12');
$ttd->easyCell("Mengetahui:\nOrang Tua/Wali,\n\n\n\n\n\n\n\n___________________________",'align:C; valign:T');
$ttd->easyCell("Indramayu, ........................ 20.....\nGuru Kelas,\n\n\n\n\n\n\n\n___________________________\n NIP...................................",'align:C; valign:T');
$ttd->printRow();
$ttd->endTable(5);

$ttd1=new easyTable($pdf, 1);
$ttd1->rowStyle('font-size:12');
$ttd1->easyCell("Mengetahui:\nKepala Sekolah,\n\n\n\n\n\n\n\n___________________________\nNIP. ..................................",'align:C; valign:T');
$ttd1->printRow();
$ttd1->endTable(5);


 $pdf->Output('D',$namafilenya);


 

?>