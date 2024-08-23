<?php 
include 'header.php';
?>
<!-- IMAGE -->
<div class="container-fluid" style="margin: 0;padding: 0;">
	<div class="image" style="margin-top: -21px">
		<!-- <img src="image/home/1.jpg" style="width: 100%;  height: 650px;"> -->
	</div>
</div>
<br>
<br>

<!-- PRODUK TERBARU -->
<div class="container">
	<h2 style=" width: 100%; border-bottom: 4px solid #ff8680; margin-top: 80px;"><b>Produk Kami</b></h2>

	<div class="row">
		<?php 
		$result = mysqli_query($conn, "SELECT * FROM produk");
		while ($row = mysqli_fetch_assoc($result)) {
			?>
			<div class="col-sm-6 col-md-4">
				<div class="thumbnail" style="position: relative;">
					<?php if($row['status'] == 'Tidak Tersedia') { ?>
						<div style="position: relative;">
							<img src="image/produk/<?= $row['image']; ?>" style="filter: grayscale(100%); opacity: 0.5;">
							<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: black; font-size: 20px; font-weight: bold;">Tidak Tersedia</div>
						</div>
					<?php } else { ?>
						<img src="image/produk/<?= $row['image']; ?>" >
					<?php } ?>
					<div class="caption">
						<h3><?= $row['nama'];  ?></h3>
						<h4>Rp.<?= number_format($row['harga']); ?></h4>
						<?php if($row['status'] != 'Tidak Tersedia') { // Hanya tampilkan tombol jika status tersedia ?>
						<div class="row">
							<div class="col-md-6">
								<a href="detail_produk.php?produk=<?= $row['kode_produk']; ?>" class="btn btn-warning btn-block">Detail</a> 
							</div>
							<?php if(isset($_SESSION['kd_cs'])){ ?>
								<div class="col-md-6">
									<a href="proses/add.php?produk=<?= $row['kode_produk']; ?>&kd_cs=<?= $kode_cs; ?>&hal=1" class="btn btn-success btn-block" role="button"><i class="glyphicon glyphicon-shopping-cart"></i> Tambah</a>
								</div>
								<?php 
							}
							else{
								?>
								<div class="col-md-6">
									<a href="keranjang.php" class="btn btn-success btn-block" role="button"><i class="glyphicon glyphicon-shopping-cart"></i> Tambah</a>
								</div>

								<?php 
							}
							?>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php 
		}
		?>
	</div>

</div>
<br>
<br>
<br>
<br>
<?php 
include 'footer.php';
?>
