<?php
// http://localhost/pinjamruang/pinjamruang.php
// https://talent-it.vercel.app/student/dashboard
// Konfigurasi koneksi
$servername = "localhost";       // Server database
$username = "root";            // Username default XAMPP
$password = "";                // Password default kosong
$dbname   = "pinjamruang";     // Nama database yang dibuat

$conn = mysqli_connect($servername, $username, $password, $dbname);
// Jika berhasil
// echo "Koneksi ke  '$dbname' berhasil";
try{
$sql = "DROP TABLE b"; // hapus tabel
}catch(Exception $e){
  // echo "Tabel tidak ada";
}

try{
$sql = "DROP TABLE a"; // hapus tabel
}catch(Exception $e){
  // echo "Tabel tidak ada";
}

try{
  $sql = "CREATE TABLE b (
        Ruang VARCHAR(50) NOT NULL  PRIMARY KEY,
        Deskripsi VARCHAR(500)
    )";
if (mysqli_query($conn, $sql)) {
  // echo "Tabel berhasil dibuat";
} else {
    echo "Error membuat tabel: " . mysqli_error($conn);
}
}catch(Exception $e){
 // echo "Tabel sudah ada";
}

try{
  $sql = "CREATE TABLE a (
        Id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        Nama VARCHAR(50) NULL,
        RuangTerpilih VARCHAR(50) NULL,
        JamMulai VARCHAR(50) NULL,
        JamSelesai VARCHAR(50) NULL
    )";
if (mysqli_query($conn, $sql)) {
  // echo "Tabel berhasil dibuat";
} else {
    echo "Error membuat tabel: " . mysqli_error($conn);
}
}catch(Exception $e){
 // echo "Tabel sudah ada";
}

// taruk data
try{
 
  $sql = "INSERT INTO b (Ruang, Deskripsi)
VALUES ('Mandi',  'Ini adalah ruang untuk mandi. bisa dipakai untuk mandi dan kepentingan pribadi')";

if (mysqli_query($conn, $sql)) {
//  echo "data terbuat";
} else {
//  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
}catch(Exception $e){
//  echo "data sudah ada";
}
try{
 
  $sql = "INSERT INTO b (Ruang, Deskripsi)
VALUES ('Tidur', 'Ini adalah ruang untuk tidur. bisa dipakai untuk beristirahat dan tidur')";

if (mysqli_query($conn, $sql)) {
//  echo "data terbuat";
} else {
// echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
}catch(Exception $e){
//  echo "data sudah ada";
}
try{
 
  $sql = "INSERT INTO b (Ruang, Deskripsi)
VALUES ('Makan',  'Ini adalah ruang untuk makan. bisa dipakai untuk makan dan minum')";

if (mysqli_query($conn, $sql)) {
//  echo "data terbuat";
} else {
//  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
}catch(Exception $e){
//  echo "data sudah ada";
}
try{
 
  $sql = "INSERT INTO b (Ruang, Deskripsi)
VALUES ('Acara', 'Ini adalah ruang acara. bisa dipakai untuk acara dan kegiatan')";
if (mysqli_query($conn, $sql)) {
  // echo "data terbuat";
} else {
  // echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
}catch(Exception $e){
//  echo "data sudah ada";
}
try{
 
   $sql = "INSERT INTO b (Ruang, Deskripsi)
VALUES ('Meeting', 'Ini adalah ruang untuk meeting. bisa dipakai untuk meeting dan rapat')";
if (mysqli_query($conn, $sql)) {
//  echo "data terbuat";
} else {
//  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
}catch(Exception $e){
//  echo "data sudah ada";
}

$sql = "SELECT Ruang, Deskripsi FROM b";
$result = $conn->query($sql);


