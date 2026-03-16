<?php
header("Content-Type: application/json");
mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli(
    'caboose.proxy.rlwy.net',
    'root',
    'loNOJCiUQGrPegvEOpNpVUKtVioRRwMn',
    'railway',
    35889
);

if ($conn->connect_errno) {
    die(json_encode([
        "status"  => "error",
        "code"    => $conn->connect_errno,
        "message" => $conn->connect_error
    ]));
}

$method = $_SERVER['REQUEST_METHOD'];

switch($method){
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
        $data = [];
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        echo json_encode(["status" => "success", "data" => $data]);
        break;

    case 'POST':
        $body = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO mata_kuliah (nama_matkul, sks, id_mahasiswa, id_dosen) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siii", $body['nama_matkul'], $body['sks'], $body['id_mahasiswa'], $body['id_dosen']);
        if($stmt->execute()){
            echo json_encode(["status" => "success", "message" => "Data berhasil ditambahkan"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
        break;

    case 'PUT':
        $id = $_GET['id'];
        $body = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("UPDATE mata_kuliah SET nama_matkul=?, sks=?, id_mahasiswa=?, id_dosen=? WHERE id_matkul=?");
        $stmt->bind_param("siiii", $body['nama_matkul'], $body['sks'], $body['id_mahasiswa'], $body['id_dosen'], $id);
        if($stmt->execute()){
            echo json_encode(["status" => "success", "message" => "Data berhasil diupdate"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM mata_kuliah WHERE id_matkul=?");
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            echo json_encode(["status" => "success", "message" => "Data berhasil dihapus"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Method tidak diizinkan"]);
        break;
}

$conn->close();
?>
