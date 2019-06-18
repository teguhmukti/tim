<?php 
	
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$id = $_POST['id'];
		$judul = $_POST['judul'];
		$pengarang = $_POST['pengarang'];
		$penerbit = $_POST['penerbit'];
		$th_terbit = $_POST['th_terbit'];
		$jml_hal = $_POST['jml_hal'];
		$id_lokasi = $_POST['id_lokasi'];
		
		require_once('koneksi.php');
		
		//Membuat SQL Query
		$sql = "UPDATE t_buku SET judul = '$judul', pengarang = '$pengarang', penerbit = '$penerbit', th_terbit = '$th_terbit', jml_hal = '$jml_hal', id_lokasi = '$id_lokasi' WHERE id = $id;";
		
		//Meng-update Database 
		if(mysqli_query($conn,$sql)){
			echo 'Berhasil Update Data Pegawai';
		}else{
			echo 'Gagal Update Data Pegawai';
		}
		
		mysqli_close($conn);
	}



?>