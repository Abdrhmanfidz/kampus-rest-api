<?php
header("Content-Type: application/json");

mysqli_report(MYSQLI_REPORT_OFF);

$host = 'shuttle.proxy.rlwy.net';
$user = 'root';
$pass = 'loNOJCiUQGrPegvEOpNpVUKtVioRRwMn'; // ← ganti ini
$db   = 'railway';
$port = 35889;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die(json_encode([
        "status" => "error",
        "message" => "Koneksi gagal: " . $conn->connect_error
    ]));
}

echo json_encode([
    "status" => "success", 
    "message" => "Koneksi berhasil!"
]);
?>
