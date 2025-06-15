<?php

$filename = 'todos.json';

// Dosya yoksa boş dizi oluştur
if (!file_exists($filename)) {
    file_put_contents($filename, json_encode([]));
}

// JSON dosyasını oku
$todos = json_decode(file_get_contents($filename), true);

// POST isteği: Yeni todo ekle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $text = trim($data['text'] ?? '');

    if ($text !== '') {
        $todos[] = [
            'id' => uniqid(),
            'text' => $text,
            'created_at' => date('Y-m-d H:i:s')
        ];
        file_put_contents($filename, json_encode($todos, JSON_PRETTY_PRINT));
    }

    echo json_encode($todos);
    exit;
}

// DELETE isteği: ID'ye göre sil
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $idToDelete = $data['id'] ?? '';

    $todos = array_filter($todos, function($todo) use ($idToDelete) {
        return $todo['id'] !== $idToDelete;
    });

    file_put_contents($filename, json_encode(array_values($todos), JSON_PRETTY_PRINT));
    echo json_encode(['status' => 'deleted']);
    exit;
}

// GET isteği: Tüm todos’u getir
echo json_encode($todos);
exit;
