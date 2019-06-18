<?php 

require_once 'koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM t_buku ORDER BY id DESC");

$array = array();

while ($row = mysqli_fetch_assoc($query)) {
    array_push($array, array(
        'id' => $row['id'], 
        'judul' => $row['judul'], 
        'pengarang' => $row['pengarang'], ));
}

echo json_encode($array);

?>