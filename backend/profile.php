<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

include_once 'config.php';

$method = $_SERVER["REQUEST_METHOD"];

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->user_id)) {
        $conn = getConnect();
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE id = ?");
        $stmt->bind_param("i", $data->user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo json_encode(['profile' => $user]);
        } else {
            echo json_encode(['message' => 'User Not Found']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['message' => 'Invalid Input']);
    }
} else {
    echo json_encode(["message" => "Invalid Request"]);
}
