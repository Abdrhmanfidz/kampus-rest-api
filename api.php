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
        "message" => $conn->connect_error
    ]));
}

$method = $_SERVER['REQUEST_METHOD'];
$table  = $_GET['table'] ?? '';

// ==================== FUNGSI TAMPIL SEMUA ====================
function tampilSemua($conn){
    $mahasiswa = $conn->query("SELECT * FROM mahasiswa");
    $data_mahasiswa = [];
    while($row = $mahasiswa->fetch_assoc()){
        $data_mahasiswa[] = $row;
    }

    $dosen = $conn->query("SELECT * FROM dosen");
    $data_dosen = [];
    while($row = $dosen->fetch_assoc()){
        $data_dosen[] = $row;
    }

    $matkul = $conn->query("SELECT 
        mata_kuliah.id_matkul,
        mata_kuliah.nama_matkul,
        mata_kuliah.sks,
        mahasiswa.nama AS nama_mahasiswa,
        dosen.nama_dosen
    FROM mata_kuliah
    JOIN mahasiswa ON mata_kuliah.id_mahasiswa = mahasiswa.id_mahasiswa
    JOIN dosen ON mata_kuliah.id_dosen = dosen.id_dosen");
    $data_matkul = [];
    while($row = $matkul->fetch_assoc()){
        $data_matkul[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "data" => [
            "mahasiswa"   => $data_mahasiswa,
            "dosen"       => $data_dosen,
            "mata_kuliah" => $data_matkul
        ]
    ]);
}

switch($table){

    // ==================== KOSONG = TAMPIL SEMUA ====================
    case '':
        tampilSemua($conn);
        break;

    // ==================== ALL ====================
    case 'all':
        tampilSemua($conn);
        break;

    // ==================== MATA KULIAH ====================
    case 'matkul':
        switch($method){
            case 'GET':
                if(isset($_GET['id'])){
                    $id = $_GET['id'];
                    $stmt = $conn->prepare("SELECT * FROM mata_kuliah WHERE id_matkul = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $data = $result->fetch_assoc();
                    if($data){
                        echo json_encode(["status" => "success", "data" => $data]);
                    } else {
                        echo json_encode(["status" => "error", "message" => "Data tidak ditemukan"]);
                    }
                } else {
                    $result = $conn->query("SELECT * FROM mata_kuliah");
                    $data = [];
                    while($row = $result->fetch_assoc()){
                        $data[] = $row;
                    }
                    echo json_encode(["status" => "success", "data" => $data]);
                }
                break;

            case 'POST':
                $body = json_decode(file_get_contents("php://input"), true);
                $stmt = $conn->prepare("INSERT INTO mata_kuliah (nama_matkul, sks, id_mahasiswa, id_dosen) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("siii", $body['nama_matkul'], $body['sks'], $body['id_mahasiswa'], $body['id_dosen']);
                if($stmt->execute()){
                    echo json_encode(["status" => "success", "message" => "Mata kuliah berhasil ditambahkan"]);
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
                    echo json_encode(["status" => "success", "message" => "Mata kuliah berhasil diupdate"]);
                } else {
                    echo json_encode(["status" => "error", "message" => $stmt->error]);
                }
                break;

            case 'DELETE':
                $id = $_GET['id'];
                $stmt = $conn->prepare("DELETE FROM mata_kuliah WHERE id_matkul=?");
                $stmt->bind_param("i", $id);
                if($stmt->execute()){
                    echo json_encode(["status" => "success", "message" => "Mata kuliah berhasil dihapus"]);
                } else {
                    echo json_encode(["status" => "error", "message" => $stmt->error]);
                }
                break;
        }
        break;

    // ==================== MATA KULIAH + JOIN ====================
    case 'matkul-detail':
        switch($method){
            case 'GET':
                if(isset($_GET['id'])){
                    $id = $_GET['id'];
                    $stmt = $conn->prepare("SELECT 
                        mata_kuliah.id_matkul,
                        mata_kuliah.nama_matkul,
                        mata_kuliah.sks,
                        mahasiswa.nama AS nama_mahasiswa,
                        dosen.nama_dosen
                    FROM mata_kuliah
                    JOIN mahasiswa ON mata_kuliah.id_mahasiswa = mahasiswa.id_mahasiswa
                    JOIN dosen ON mata_kuliah.id_dosen = dosen.id_dosen
                    WHERE mata_kuliah.id_matkul = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $data = $result->fetch_assoc();
                    if($data){
                        echo json_encode(["status" => "success", "data" => $data]);
                    } else {
                        echo json_encode(["status" => "error", "message" => "Data tidak ditemukan"]);
                    }
                } else {
                    $result = $conn->query("SELECT 
                        mata_kuliah.id_matkul,
                        mata_kuliah.nama_matkul,
                        mata_kuliah.sks,
                        mahasiswa.nama AS nama_mahasiswa,
                        dosen.nama_dosen
                    FROM mata_kuliah
                    JOIN mahasiswa ON mata_kuliah.id_mahasiswa = mahasiswa.id_mahasiswa
                    JOIN dosen ON mata_kuliah.id_dosen = dosen.id_dosen");
                    $data = [];
                    while($row = $result->fetch_assoc()){
                        $data[] = $row;
                    }
                    echo json_encode(["status" => "success", "data" => $data]);
                }
                break;

            default:
                echo json_encode(["status" => "error", "message" => "Method tidak diizinkan"]);
                break;
        }
        break;

    // ==================== MAHASISWA ====================
    case 'mahasiswa':
        switch($method){
            case 'GET':
                if(isset($_GET['id'])){
                    $id = $_GET['id'];
                    $stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE id_mahasiswa = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $data = $result->fetch_assoc();
                    if($data){
                        echo json_encode(["status" => "success", "data" => $data]);
                    } else {
                        echo json_encode(["status" => "error", "message" => "Data tidak ditemukan"]);
                    }
                } else {
                    $result = $conn->query("SELECT * FROM mahasiswa");
                    $data = [];
                    while($row = $result->fetch_assoc()){
                        $data[] = $row;
                    }
                    echo json_encode(["status" => "success", "data" => $data]);
                }
                break;

            case 'POST':
                $body = json_decode(file_get_contents("php://input"), true);
                $stmt = $conn->prepare("INSERT INTO mahasiswa (nama) VALUES (?)");
                $stmt->bind_param("s", $body['nama']);
                if($stmt->execute()){
                    echo json_encode(["status" => "success", "message" => "Mahasiswa berhasil ditambahkan"]);
                } else {
                    echo json_encode(["status" => "error", "message" => $stmt->error]);
                }
                break;

            case 'PUT':
                $id = $_GET['id'];
                $body = json_decode(file_get_contents("php://input"), true);
                $stmt = $conn->prepare("UPDATE mahasiswa SET nama=? WHERE id_mahasiswa=?");
                $stmt->bind_param("si", $body['nama'], $id);
                if($stmt->execute()){
                    echo json_encode(["status" => "success", "message" => "Mahasiswa berhasil diupdate"]);
                } else {
                    echo json_encode(["status" => "error", "message" => $stmt->error]);
                }
                break;

            case 'DELETE':
                $id = $_GET['id'];
                $stmt = $conn->prepare("DELETE FROM mahasiswa WHERE id_mahasiswa=?");
                $stmt->bind_param("i", $id);
                if($stmt->execute()){
                    echo json_encode(["status" => "success", "message" => "Mahasiswa berhasil dihapus"]);
                } else {
                    echo json_encode(["status" => "error", "message" => $stmt->error]);
                }
                break;
        }
        break;

    // ==================== DOSEN ====================
    case 'dosen':
        switch($method){
            case 'GET':
                if(isset($_GET['id'])){
                    $id = $_GET['id'];
                    $stmt = $conn->prepare("SELECT * FROM dosen WHERE id_dosen = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $data = $result->fetch_assoc();
                    if($data){
                        echo json_encode(["status" => "success", "data" => $data]);
                    } else {
                        echo json_encode(["status" => "error", "message" => "Data tidak ditemukan"]);
                    }
                } else {
                    $result = $conn->query("SELECT * FROM dosen");
                    $data = [];
                    while($row = $result->fetch_assoc()){
                        $data[] = $row;
                    }
                    echo json_encode(["status" => "success", "data" => $data]);
                }
                break;

            case 'POST':
                $body = json_decode(file_get_contents("php://input"), true);
                $stmt = $conn->prepare("INSERT INTO dosen (nama_dosen) VALUES (?)");
                $stmt->bind_param("s", $body['nama_dosen']);
                if($stmt->execute()){
                    echo json_encode(["status" => "success", "message" => "Dosen berhasil ditambahkan"]);
                } else {
                    echo json_encode(["status" => "error", "message" => $stmt->error]);
                }
                break;

            case 'PUT':
                $id = $_GET['id'];
                $body = json_decode(file_get_contents("php://input"), true);
                $stmt = $conn->prepare("UPDATE dosen SET nama_dosen=? WHERE id_dosen=?");
                $stmt->bind_param("si", $body['nama_dosen'], $id);
                if($stmt->execute()){
                    echo json_encode(["status" => "success", "message" => "Dosen berhasil diupdate"]);
                } else {
                    echo json_encode(["status" => "error", "message" => $stmt->error]);
                }
                break;

            case 'DELETE':
                $id = $_GET['id'];
                $stmt = $conn->prepare("DELETE FROM dosen WHERE id_dosen=?");
                $stmt->bind_param("i", $id);
                if($stmt->execute()){
                    echo json_encode(["status" => "success", "message" => "Dosen berhasil dihapus"]);
                } else {
                    echo json_encode(["status" => "error", "message" => $stmt->error]);
                }
                break;
        }
        break;

    default:
        echo json_encode([
            "status"  => "error",
            "message" => "Gunakan ?table=matkul, ?table=matkul-detail, ?table=mahasiswa, atau ?table=dosen"
        ]);
        break;
}

$conn->close();
?>
