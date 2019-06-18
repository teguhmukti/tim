<?php 

require_once 'connection.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){

    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    if ( $name == '' || $email == '' ){

        echo 'Mohon isi semua data!';

    } else {

        $query = "UPDATE users SET name='$name', email='$email' WHERE id = '$id' ";

        if ( mysqli_query($conn, $query) ){
            $response["value"] = 1;
            $response["message"] = $name." Sukses diubah";
            echo json_encode($response);
        } else {
            $response["value"] = 0;
            $response["message"] = "Oops! ".$name." Gagal diubah, \n Silahkan Coba lagi!";
            echo json_encode($response);
        }
    }

    mysqli_close($conn);

} else {
    $response["value"] = 0;
    $response["message"] = "oops! Coba lagi!";
    echo json_encode($response);
}

?>