<?php
header("Content-Type: application/json");
mysqli_report(MYSQLI_REPORT_OFF);

$host = 'shuttle.proxy.rlwy.net';
$port = 35889;
$user = 'root';
$pass = 'loNOJCiUQGrPegvEOpNpVUKtVioRRwMn';
$db   = 'railway';

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_errno) {
    die(json_encode([
        "status"  => "error",
        "code"    => $conn->connect_errno,
        "message" => $conn->connect_error
    ]));
}

echo json_encode([
    "status"  => "success",
    "message" => "Koneksi berhasil!"
]);
?>
