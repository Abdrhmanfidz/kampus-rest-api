<?php
header("Content-Type: application/json");

mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli();
$conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
$conn->real_connect(
    'shuttle.proxy.rlwy.net',
    'root',
    'loNOJCiUQGrPegvEOpNpVUKtVioRRwMn',
    'railway',
    35889,
    NULL,
    MYSQLI_CLIENT_SSL
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
