<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

include_once 'config.php';

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
    case 'GET':
        getTasks();
        break;
    case 'POST':
        createTask();
        break;
    case 'PUT':
        updateTask();
        break;
    case 'DELETE':
        deleteTask();
        break;
    default:
        echo json_encode(["message" => "Invalid Request"]);
        break;
}

function createTask()
{
    $data = json_decode(file_get_contents('php://input'));
    if (!empty($data->title)) {
        $conn = getConnect();
        $stmt = $conn->prepare("INSERT INTO notes (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $data->title, $data->content);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Note Created']);
        } else {
            echo json_encode(['message' => 'Note Not Created']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['message' => 'Incomplete Data']);
    }
}

function updateTask()
{
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {
        $conn = getConnect();
        $query = "UPDATE notes SET ";
        $params = [];
        $types = "";

        if (!empty($data->title)) {
            $query .= "title = ?, ";
            $params[] = $data->title;
            $types .= "s";
        }

        if (!empty($data->content)) {
            $query .= "content = ?, ";
            $params[] = $data->content;
            $types .= "s";
        }

        $query = rtrim($query, ", ") . " WHERE id = ?";
        $params[] = $data->id;
        $types .= "i";

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Note Updated']);
        } else {
            echo json_encode(['message' => 'Note Not Updated']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['message' => 'Invalid ID']);
    }
}

function deleteTask()
{
    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data->id)) {
        $conn = getConnect();
        $stmt = $conn->prepare("DELETE FROM notes WHERE id = ?");
        $stmt->bind_param("i", $data->id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Note Deleted']);
        } else {
            echo json_encode(['message' => 'Note Not Deleted']);
        }
        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['message' => 'Invalid ID']);
    }
}

function getTasks()
{
    $conn = getConnect();

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM notes WHERE id = ?");
        $stmt->bind_param("i", $id);
    } else {
        $stmt = $conn->prepare("SELECT * FROM notes");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $tasks = [];

        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }

        echo json_encode($tasks);
    } else {
        echo json_encode(['message' => 'No Notes Found']);
    }
    $stmt->close();
    $conn->close();
}
