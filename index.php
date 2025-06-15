<?php
$todosFile = 'todos.json';

// GET isteği: Listeyi göster
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo file_get_contents($todosFile);
}

// POST isteği: Yeni todo ekle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['text'])) {
        echo json_encode(['error' => 'Eksik veri']);
        http_response_code(400);
        exit;
    }

    $todos = json_decode(file_get_contents($todosFile), true);
    $todos[] = [
        'text' => $input['text'],
        'created_at' => date('Y-m-d H:i:s')
    ];
    file_put_contents($todosFile, json_encode($todos, JSON_PRETTY_PRINT));
    echo json_encode(['status' => 'Todo eklendi']);
}
?>
