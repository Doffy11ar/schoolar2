<?php
include('../../config/database.php');
session_start();

if(isset($_SESSION['user_id'])){
    header('Location: ../index.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['e_mail'] ?? '';
    $passw = $_POST['p_sswd'] ?? '';
    $enc_pass = sha1($passw);

    $sql = "
        SELECT 
            id,
            firstname
        FROM 
            users
        WHERE
            email = '$email' AND
            password = '$enc_pass' AND
            status = true
        LIMIT 1
    ";

    $res = pg_query($conn, $sql);

    if($res){
        $row = pg_fetch_assoc($res);
        if($row){
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['firstname'] = $row['firstname'];
            header('Refresh:0; url=http://localhost/schoolar2/src/index.php');
        } else {
            echo "Login failed: Usuario o contraseña incorrectos.";
        }
    } else {
        echo "Error en la consulta a la base de datos.";
    }
} else {
    echo "Método no permitido.";
}
?>
