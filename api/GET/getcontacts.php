<?php 

require_once 'connect.php';

$type = $_GET['item_type'];

if (isset($_GET['key'])) {
    $key = $_GET["key"];
    if ($type == 'users') {
        $query = "SELECT * FROM t_buku WHERE judul LIKE '%$key%'";
        $result = mysqli_query($conn, $query);
        $response = array();
        while( $row = mysqli_fetch_assoc($result) ){
            array_push($response, 
            array(
                'id'=>$row['id'], 
                'judul'=>$row['judul'], 
                'pengarang'=>$row['pengarang']) 
            );
        }
        echo json_encode($response);   
    }
} else {
    if ($type == 'users') {
        $query = "SELECT * FROM users";
        $result = mysqli_query($conn, $query);
        $response = array();
        while( $row = mysqli_fetch_assoc($result) ){
            array_push($response, 
            array(
                'id'=>$row['id'], 
                'judul'=>$row['judul'], 
                'pengarang'=>$row['pengarang'])
            );
        }
        echo json_encode($response);   
    }
}

mysqli_close($conn);

?>