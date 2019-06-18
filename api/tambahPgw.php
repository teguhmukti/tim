<?php 

if($_SERVER['REQUEST_METHOD']=='POST'){
	
	//Mendapatkan nilai variable
	$name = $_POST['name'];
	$desg = $_POST['desg'];
	$sal = $_POST['salary'];
	
	$sql = "INSERT INTO tb_pegawai (nama,posisi,gaji) VALUES ('$name','$desg','$sal')";
	
	require_once('koneksi.php');
	
	
	if(mysqli_query($conn,$sql)){
		echo 'Berahasil Menambahkan Pegawai';
	}else{
		echo 'Gagal Menambahkan Pegawai';
	}
	
	mysqli_close($conn);
}
?>