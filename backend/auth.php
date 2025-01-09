<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

include_once 'config.php';

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
    case 'POST':
        if ($_GET['action'] === 'register') {
            register();
        } elseif ($_GET['action'] === 'login') {
            login();
        }
        break;
    case 'GET':
        if ($_GET['action'] === 'profile') {
            profile();
        }
        break;
    default:
        echo json_encode(["message" => "Invalid Request"]);
        break;
}

function register()
{
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
}

function login()
{
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
}

function profile()
{
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $conn = getConnect();
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
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
        echo json_encode(['message' => 'Not Logged In']);
    }
}
