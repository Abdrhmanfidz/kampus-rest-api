<?php
header("Content-Type: application/json");

// Koneksi database - hardcode sementara untuk test
$conn = new mysqli(
    'mysql.railway.internal',
    'root',
    'loNOJCiUQGrPegvEOpNpVUKtVioRRwMn',
    'railway',
    3306
);

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
