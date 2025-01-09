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
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $hashedPassword = password_hash($data->password, PASSWORD_BCRYPT);
        $stmt->bind_param("ss", $data->username, $hashedPassword);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'User Registered']);
        } else {
            echo json_encode(['message' => 'User Registration Failed']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['message' => 'Invalid Input']);
    }
} else {
    echo json_encode(["message" => "Invalid Request"]);
}
