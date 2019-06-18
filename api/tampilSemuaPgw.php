<?php 

require_once('koneksi.php');

$sql = "SELECT * FROM t_buku";

$r = mysqli_query($conn,$sql);

$result = array();

while($row = mysqli_fetch_array($r)){
	//memasukkan nama dan id kedalam array kososng yang telah dibuat
	array_push($result,array(
		"id"=>$row['id'],
		"judul"=>$row['judul'],
		"pengarang"=>$row['pengarang'],
		"penerbit"=>$row['penerbit'],
		"th_terbit"=>$row['th_terbit'],
		"jml_hal"=>$row['jml_hal'],
		"id_lokasi"=>$row['id_lokasi']
	));
}

//menampilkan array dalam format json
echo json_encode(array('result'=>$result));

mysqli_close($conn);





?>