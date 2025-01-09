<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

include_once 'config.php';

$method = $_SERVER["REQUEST_METHOD"];

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->username) && !empty($data->password)) {
        $conn = getConnect();
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $data->username);
        $stmt->execute();
        $stmt->bind_result($id, $hashedPassword);

        if ($stmt->fetch() && password_verify($data->password, $hashedPassword)) {
            echo json_encode(['message' => 'Login Successful', 'user_id' => $id]);
        } else {
            echo json_encode(['message' => 'Invalid Credentials']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['message' => 'Invalid Input']);
    }
} else {
    echo json_encode(["message" => "Invalid Request"]);
}
