<?php
session_start();
if(!isset($_SESSION['start_pegawai'])){
	header("Location:login.php");
}
 ?>
<?php
$conn = new mysqli ('localhost','root','') or die(mysqli_error());
$db = mysqli_select_db($conn,'db_payklik') or die ("Database Error");
$query = mysqli_query($conn,"SELECT * FROM admin WHERE user='".$_SESSION['start_pegawai']."'");
$row = mysqli_fetch_array($query);

 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<title>Data Pegawai</title>
	<style type="text/css">
@font-face {
    font-family: 'selphiaselphia_script';
    src: url('fontss/selphia-webfont.eot');
    src: url('fontss/selphia-webfontd41d.eot?#iefix') format('embedded-opentype'),
         url('fontss/selphia-webfont.html') format('woff2'),
         url('fontss/selphia-webfont.woff') format('woff'),
         url('fontss/selphia-webfont.ttf') format('truetype'),
         url('fontss/selphia-webfont.svg#selphiaselphia_script') format('svg');
    font-weight: normal;
	 font-style: italic;
}
.content{
		margin-top: 80px;
	}
	h5{
		color:white;
		line-height: 30px;
		float: right;
		position: relative;

	}
	#drop{
		line-height: 40px;
		color:white;
		background: transparent;
		border: none;
		float: right;
		position: relative;
		}
	.dorpdown-menu{
		float: right;
		outline: none;
	}
	#ff{
		position: relative;
		left: 100px;
	}

#logo{
    font-family: 'selphiaselphia_script';
    font-size: 20pt;
    color:white;
    letter-spacing: 2px;

}
	</style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="#" class="navbar-brand" id="logo">Pay.Klik</a>
    </div>

    <div class="collapse navbar-collapse" id="myNavbar">
    <ul class="nav navbar-nav">
        <li><a href="home.php"><span class="glyphicon glyphicon-home"></span> Beranda</a></li>
        <li class="active"><a href="index.php"><span class="glyphicon glyphicon-list"></span>  Pegawai</a></li>
        <li><a href="tambahKar.php"><span class="glyphicon glyphicon-user"></span> Tambah Data</a></li>

     <li> <form class="navbar-form" role="search" action="cari.php" method="post">
        <div class="form-group input-group">
          <input type="text" class="form-control" placeholder="Cari NIP Pegawai" name="carinip">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit" name="submit">
              <span class="glyphicon glyphicon-search"></span>
            </button>
          </span>
        </div>
      </form></li>
      </ul>
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" id="drop">
          <span class="glyphicon glyphicon-option-vertical"></span>
       </button>
        <ul class="dropdown-menu dropdown-menu-right" id="dm">
          <li><a href="#"><span class="glyphicon glyphicon-cog"></span> Setting</a></li>
          <li><a href="logout.php"> <span class="glyphicon glyphicon-off"></span> Logout</a></li>
        </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign in as <?php echo $row['user']; ?></a></li>
      </ul>
    </div>
  </div>
</nav>


<div class="container">
	<div class="content">
		<h2>Data Pegawai</h2>
		<hr>
		<?php
		if(isset($_GET['aksi']) == 'delete'){
			$nip = $_GET['nip'];
			$Data = mysqli_query($conn, "SELECT * FROM dat_gaji WHERE nip='$nip'");
			if(mysqli_num_rows($Data) == 0){
				echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Data tidak ditemukan.</div>';
			}else{
				$delete = mysqli_query($conn, "DELETE FROM dat_gaji WHERE nip='$nip'");
				if($delete){
					echo '<div class="alert alert-primary alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Data berhasil dihapus.</div>';
				}else{
					echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Data gagal dihapus.</div>';
				}
			}
			}
			?>
		<form class="form-inline" method="get">
			<div class="form-group">
				<select name="filter" class="form-control" onchange="form.submit()">
					<option value="0">Data Pegawai</option>
					<?php $filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : NULL); ?>
				</select>
			</div>
		</form>
		<br>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<tr>
                <th>No</th>
				<th>NIP</th>
				<th>Nama Pegawai</th>
                <th>No. Gaji</th>
                <th>Bulan</th>
				<th>Tahun</th>
				<th>Gaji Pokok</th>
				<th>Gaji Bersih</th>
                <th>Tools</th>
			</tr>
			<?php
				if($filter){
					$sql = mysqli_query($conn,"SELECT * FROM dat_gaji WHERE status='$filter' ORDER BY nip ASC");
				}else{
					$sql = mysqli_query($conn, "SELECT * FROM dat_gaji ORDER BY nip ASC");
				}
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">Data Tidak Ada.</td></tr>';
				}
			 	else{

			 		while($row = mysqli_fetch_assoc($sql)){
			 			echo "<tr>
			 					<td>".$row['no']."</td>
			 					<td>".$row['nip']."</td>
			 					<td><a href='profile.php?nip=".$row['nip']."'><span class='glyphicon glyphicon-user'></span> ".$row['nmpeg']."</a></td>
			 					<td>".$row['nogaji']."</td>
			 					<td>".$row['bulan']."</td>
			 					<td>".$row['tahun']."</td>
			 					<td>Rp.".$row['gjpokok']."</td>
			 					<td>Rp.".$row['bersih']."</td>";

						echo '<td>
								<a  data-toggle="tooltip" data-placement="top" title="Ubah Data" href="ubah.php?nip='.$row['nip'].'" title="Edit Data" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-edit"></span></a>
								<a  data-toggle="tooltip" data-placement="top" title="Ganti Password" href="ubah.php?nip='.$row['nip'].'" title="Edit Data" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-refresh"></span></a>
								<a data-toggle="tooltip" data-placement="top" title="Hapus Data" href="index.php?aksi=delete&nip='.$row['nip'].'" title="Hapus Data" onclick="return confirm(\'Anda yakin akan menghapus data '.$row['nmpeg'].'?\')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span></a>
								';
			 		}
			 	}
			 ?>
		</table>
	</div>
</div>


<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
</body>
</html>
