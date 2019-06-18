<?php 
	//Mendapatkan Nilai Dari Variable ID Pegawai yang ingin ditampilkan
	$id = $_GET['id'];
	
	//Importing database
	require_once('koneksi.php');
	
	//Membuat SQL Query dengan pegawai yang ditentukan secara spesifik sesuai ID
	$sql = "SELECT * FROM t_buku WHERE id=$id";
	
	//Mendapatkan Hasil 
	$r = mysqli_query($conn,$sql);
	
	//Memasukkan Hasil Kedalam Array
	$result = array();
	$row = mysqli_fetch_array($r);
	array_push($result,array(
			"id"=>$row['id'],
			"judul"=>$row['judul'],
			"pengarang"=>$row['pengarang'],
			"penerbit"=>$row['penerbit'],
			"th_terbit"=>$row['th_terbit'],
			"jml_hal"=>$row['jml_hal'],
			"id_lokasi"=>$row['id_lokasi']
		));
 
	//Menampilkan dalam format JSON
	echo json_encode(array('result'=>$result));
	
	mysqli_close($conn);
?>