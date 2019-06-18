<?php 

require_once 'connection.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){

    $name = $_POST['name'];
    $email = $_POST['email'];

    if ( $name == '' || $email == '' ){

        echo 'Mohon isi semua data!';

    } else {

        $query = "INSERT INTO users (name,email) VALUES ('$name', '$email')";

        if ( mysqli_query($conn, $query) ){
            $response["value"] = 1;
            $response["message"] = $name." Sukses ditambahkan";
            echo json_encode($response);
        } else {
            $response["value"] = 0;
            $response["message"] = "Oops! ".$name." Gagal ditambahkan, \n Silahkan Coba lagi!";
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