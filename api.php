<?php
header("Content-Type: application/json");

// Koneksi database - hardcode sementara untuk test
$conn = new mysqli(
    'shuttle.proxy.rlwy.net',
    'root',
    'loNOJCiUQGrPegvEOpNpVUKtVioRRwMn',
    'railway',
    35889
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
