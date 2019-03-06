<?php 
include "../inc/lte-head.php";
?>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Main content -->
<section class="content">
<?php
$tgl=isset($_GET['tanggal']) ? $_GET['tanggal'] : date("d");
$bln=isset($_GET['bulan']) ? $_GET['bulan'] : date("m");
$thn=isset($_GET['tahun']) ? $_GET['tahun'] : date("Y");
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
if($level==98 or $level==97){
				$sq2=mysqli_query($koneksi, "select * from penempatan JOIN siswa USING(peserta_didik_id) where siswa.jk='L' and penempatan.rombel='$kelas' and penempatan.tapel='$tpl_aktif'");
				$sq3=mysqli_query($koneksi, "select * from penempatan JOIN siswa USING(peserta_didik_id) where siswa.jk='P' and penempatan.rombel='$kelas' and penempatan.tapel='$tpl_aktif'");
				$juml=mysqli_num_rows($sq2);
				$jump=mysqli_num_rows($sq3);
				$jtot=mysqli_query($koneksi, "select * from siswa where status=1");
				$jjum=mysqli_num_rows($jtot);
				
				$total=$juml+$jump;
				if($total>0){
					$perlak=($juml/$total)*100;
					$perper=($jump/$total)*100;
				}else{
					$perlak=0;
					$perper=0;
				};
				
				$sabsen=mysqli_query($koneksi, "select * from penempatan where rombel='$kelas' and tapel='$tpl_aktif'");
				$sakit=0;
				$ijin=0;
				$alfa=0;
				while($mk=mysqli_fetch_array($sabsen)){
					$pds=$mk['peserta_didik_id'];
					$snama=mysqli_query($koneksi, "select *,SUM(IF(absensi='S',1,0)) AS sakit,SUM(IF(absensi='I',1,0)) AS ijin,SUM(IF(absensi='A',1,0)) AS alfa from absensi where tanggal LIKE '$thn-$bln-%' and peserta_didik_id='$pds'");
					$jab=mysqli_fetch_array($snama);
					$sakit=$sakit+$jab['sakit'];
					$ijin=$ijin+$jab['ijin'];
					$alfa=$alfa+$jab['alfa'];
				};
				$jkeh=$sakit+$ijin+$alfa;
				$hef=mysqli_query($koneksi,"select * from hari_efektif where bulan='$bln' and tapel='$tpl_aktif'");
				$efek=mysqli_fetch_array($hef);
				if($efek['hari']==0){
					$harim=23;
				}else{
					$harim=$efek['hari'];
				};
				$hefek=round(($jkeh*100)/($harim*$total),2);
				
				}else{
				$sqlsisw = mysqli_query($koneksi, "siswa where status=1");
				$sq2=mysqli_query($koneksi, "select * from siswa where status=1 and jk='L'");
				$sq3=mysqli_query($koneksi, "select * from siswa where status=1 and jk='P'");
				$juml=mysqli_num_rows($sq2);
				$jump=mysqli_num_rows($sq3);
				
				$total=$juml+$jump;
				if($total>0){
					$perlak=($juml/$total)*100;
					$perper=($jump/$total)*100;
				}else{
					$perlak=0;
					$perper=0;
				};	
				};
?>
			<div class="alert alert-success alert-dismissible">
            <h4><i class="icon fa fa-check"></i> Versi 6.1.1</h4>
			</div>
				<?php if($level==98 or $level==97){ ?>
				
		<div class="row">
			<div class="col-md-3 col-sm-6 col-xs-12">
			  <div class="info-box bg-aqua">
				<span class="info-box-icon"><i class="fa fa-university"></i></span>

				<div class="info-box-content">
				  <span class="info-box-text">Rombel</span>
				  <span class="info-box-number"><?=$kelas;?></span>

				  <div class="progress">
					<div class="progress-bar" style="width: 70%"></div>
				  </div>
					  <span class="progress-description">
						12 Rombel
					  </span>
				</div>
				<!-- /.info-box-content -->
			  </div>
			  <!-- /.info-box -->
			</div>
			<!-- /.col -->
			
			<div class="col-md-3 col-sm-6 col-xs-12">
			  <div class="info-box bg-aqua">
				<span class="info-box-icon"><i class="fa fa-users"></i></span>

				<div class="info-box-content">
				  <span class="info-box-text">Jumlah Siswa</span>
				  <span class="info-box-number"><?=$total;?></span>

				  <div class="progress">
					<div class="progress-bar" style="width: 70%"></div>
				  </div>
					  <span class="progress-description">
						dari <?=$jjum;?> Siswa
					  </span>
				</div>
				<!-- /.info-box-content -->
			  </div>
			  <!-- /.info-box -->
			</div>
			<!-- /.col -->
			
			<div class="col-md-3 col-sm-6 col-xs-12">
			  <div class="info-box bg-aqua">
				<span class="info-box-icon"><i class="fa fa-male"></i></span>

				<div class="info-box-content">
				  <span class="info-box-text">Laki-laki</span>
				  <span class="info-box-number"><?=$juml;?></span>

				  <div class="progress">
					<div class="progress-bar" style="width: 70%"></div>
				  </div>
					  <span class="progress-description">
						dari <?=$jjum;?> Siswa
					  </span>
				</div>
				<!-- /.info-box-content -->
			  </div>
			  <!-- /.info-box -->
			</div>
			<!-- /.col -->
			
			<div class="col-md-3 col-sm-6 col-xs-12">
			  <div class="info-box bg-aqua">
				<span class="info-box-icon"><i class="fa fa-female"></i></span>

				<div class="info-box-content">
				  <span class="info-box-text">Perempuan</span>
				  <span class="info-box-number"><?=$jump;?></span>

				  <div class="progress">
					<div class="progress-bar" style="width: 70%"></div>
				  </div>
					  <span class="progress-description">
						dari <?=$jjum;?> Siswa
					  </span>
				</div>
				<!-- /.info-box-content -->
			  </div>
			  <!-- /.info-box -->
			</div>
			<!-- /.col -->
			
			
		</div>
		<?php }; ?>

      <!-- Default box -->
	  <div class="row">
	  <?php if($level==98 or $level==97){ ?>
		<div class="col-lg-6 col-xs-12">
			<div class="box">
				<div class="box-header with-border">
				  <h3 class="box-title">Data Absensi Bulan <?=$BulanIndo[(int)$bln-1];?></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-lg-12 col-xs-6">
							<div class="small-box bg-red">
								<div class="inner">
								  <h3><?=$hefek;?>%</h3>

								  <p>Persentase Absen</p>
								</div>
								<div class="icon">
								  <i class="fa fa-bar-chart"></i>
								</div>
								
							 </div>
						</div>
						<div class="col-lg-4 col-xs-6">
							<div class="small-box bg-aqua">
								<div class="inner">
								  <h3><?=$sakit;?></h3>

								  <p>Sakit</p>
								</div>
								<div class="icon">
								  <i class="fa fa-users"></i>
								</div>
							 </div>
						</div>
						<div class="col-lg-4 col-xs-6">
							<div class="small-box bg-aqua">
								<div class="inner">
								  <h3><?=$ijin;?></h3>

								  <p>Ijin</p>
								</div>
								<div class="icon">
								  <i class="fa fa-users"></i>
								</div>
							 </div>
						</div>
						<div class="col-lg-4 col-xs-6">
							<div class="small-box bg-aqua">
								<div class="inner">
								  <h3><?=$alfa;?></h3>

								  <p>Tanpa Keterangan</p>
								</div>
								<div class="icon">
								  <i class="fa fa-users"></i>
								</div>
							 </div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
	  <?php }else{ ?>
		<div class="col-lg-6 col-xs-12">
		  <div class="box">
			<div class="box-header with-border">
			  <h3 class="box-title">INFORMASI</h3>
			</div>
			<div class="box-body">
				<p>Masih Tahap Pengembangan untuk Guru Mata Pelajaran</p>
			</div>
			<!-- /.box-body -->
		  </div>
		  <!-- /.box -->
		</div>
	  <?php }; ?>
		<div class="col-lg-6 col-xs-12">
		  <div class="box">
			<div class="box-header with-border">
			  <h3 class="box-title">SD ISLAM AL-JANNAH</h3>
			</div>
			<div class="box-body table-responsive no-padding">
				<table class="table">
					<tr>
					  <td>NPSN</td>
					  <td>20258088</td>
					</tr>
					<tr>
					  <td>Bentuk Pendidikan</td>
					  <td>SD</td>
					</tr>
					<tr>
					  <td>Status</td>
					  <td>Swasta</td>
					</tr>
					<tr>
					  <td>Kecamatan</td>
					  <td>Kec. Gabuswetan</td>
					</tr>
					<tr>
					  <td>Kabupaten</td>
					  <td>Kab. Indramayu</td>
					</tr>
					<tr>
					  <td>Provinsi</td>
					  <td>Jawa Barat</td>
					</tr>
					<tr>
					  <td>Kepala Sekolah</td>
					  <td>Umar Ali</td>
					</tr>
					
				</table>
			</div>
			<!-- /.box-body -->
		  </div>
		  <!-- /.box -->
		</div>
	  </div>
</section>
<!-- /.content -->

<?php include "../inc/lte-script.php";?>
</body>
</html>
