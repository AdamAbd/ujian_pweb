<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

include_once 'config.php';

$method = $_SERVER["REQUEST_METHOD"];

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->username) && !empty($data->email) && !empty($data->password)) {
        $conn = getConnect();

        // Check if the email already exists
        $checkEmailStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkEmailStmt->bind_param("s", $data->email);
        $checkEmailStmt->execute();
        $checkEmailStmt->store_result();

        if ($checkEmailStmt->num_rows > 0) {
            echo json_encode(['message' => 'Email already registered']);
        } else {
            // Proceed with inserting the new user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $hashedPassword = password_hash($data->password, PASSWORD_BCRYPT);
            $stmt->bind_param("sss", $data->username, $data->email, $hashedPassword);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'User Registered']);
            } else {
                echo json_encode(['message' => 'User Registration Failed']);
            }

            $stmt->close();
        }

        $checkEmailStmt->close();
        $conn->close();
    } else {
        echo json_encode(['message' => 'Invalid Input']);
    }
} else {
    echo json_encode(["message" => "Invalid Request"]);
}
