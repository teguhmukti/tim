<?php 
 
 //Mendapatkan Nilai ID
 $id = $_GET['id'];
 
 //Import File Koneksi Database
 require_once('koneksi.php');
 
 //Membuat SQL Query
 $sql = "DELETE FROM t_buku WHERE id=$id;";
 
 
 //Menghapus Nilai pada Database 
 if(mysqli_query($conn,$sql)){
 echo 'Berhasil Menghapus Pegawai';
 }else{
 echo 'Gagal Menghapus Pegawai';
 }
 
 mysqli_close($conn);
 ?>