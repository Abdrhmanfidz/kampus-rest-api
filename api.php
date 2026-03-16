<?php

header("Content-Type: application/json");

// koneksi database
$conn = new mysqli(
    $_ENV['MYSQLHOST'],
    $_ENV['MYSQLUSER'],
    $_ENV['MYSQLPASSWORD'],
    $_ENV['MYSQLDATABASE'],
    $_ENV['MYSQLPORT']
);
);

if ($conn->connect_error) {
    die(json_encode([
        "status"=>"error",
        "message"=>"Koneksi database gagal"
    ]));
}

$method = $_SERVER['REQUEST_METHOD'];

switch($method){

// ================= GET =================
case 'GET':

$sql = "SELECT 
mata_kuliah.id_matkul,
mata_kuliah.nama_matkul,
mata_kuliah.sks,
mahasiswa.nama AS nama_mahasiswa,
dosen.nama_dosen
FROM mata_kuliah
JOIN mahasiswa ON mata_kuliah.id_mahasiswa = mahasiswa.id_mahasiswa
JOIN dosen ON mata_kuliah.id_dosen = dosen.id_dosen";

$result = $conn->query($sql);

$data=[];

while($row=$result->fetch_assoc()){
$data[]=$row;
}

echo json_encode([
"status"=>"success",
"data"=>$data
]);

break;


// ================= POST =================
case 'POST':

$data = json_decode(file_get_contents("php://input"),true);

$nama_matkul=$data['nama_matkul'];
$sks=$data['sks'];
$id_mahasiswa=$data['id_mahasiswa'];
$id_dosen=$data['id_dosen'];

$sql="INSERT INTO mata_kuliah
(nama_matkul,sks,id_mahasiswa,id_dosen)
VALUES
('$nama_matkul','$sks','$id_mahasiswa','$id_dosen')";

if($conn->query($sql)){
echo json_encode([
"status"=>"success",
"message"=>"Data berhasil ditambahkan"
]);
}else{
echo json_encode([
"status"=>"error",
"message"=>"Data gagal ditambahkan"
]);
}

break;


// ================= PUT =================
case 'PUT':

$id=$_GET['id'];

$data = json_decode(file_get_contents("php://input"),true);

$nama_matkul=$data['nama_matkul'];
$sks=$data['sks'];
$id_mahasiswa=$data['id_mahasiswa'];
$id_dosen=$data['id_dosen'];

$sql="UPDATE mata_kuliah SET
nama_matkul='$nama_matkul',
sks='$sks',
id_mahasiswa='$id_mahasiswa',
id_dosen='$id_dosen'
WHERE id_matkul='$id'";

if($conn->query($sql)){
echo json_encode([
"status"=>"success",
"message"=>"Data berhasil diupdate"
]);
}else{
echo json_encode([
"status"=>"error",
"message"=>"Data gagal diupdate"
]);
}

break;


// ================= DELETE =================
case 'DELETE':

$id=$_GET['id'];

$sql="DELETE FROM mata_kuliah WHERE id_matkul='$id'";

if($conn->query($sql)){
echo json_encode([
"status"=>"success",
"message"=>"Data berhasil dihapus"
]);
}else{
echo json_encode([
"status"=>"error",
"message"=>"Data gagal dihapus"
]);
}

break;

}

?>
