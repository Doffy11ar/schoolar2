<?php
include('../../config/database.php');
header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
    if (!@session_start()) {
        echo json_encode(['error' => 'No se pudo iniciar la sesión. Verifique la configuración del servidor.']);
        exit;
    }
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$sql = "
    SELECT 
        id,
        firstname,
        lastname,
        email,
        CASE WHEN status = true THEN 'Active' ELSE 'No active' END as status
    FROM users
";

$res = pg_query($conn, $sql);

if (!$res) {
    echo json_encode(['error' => 'Error en la consulta']);
    exit;
}

$users = [];

while ($row = pg_fetch_assoc($res)) {
    $users[] = $row;
}

echo json_encode($users);
?>
