<?php
include "db/db.php";   // path to db.php inside the db folder



if (!isset($_GET['query']) || $_GET['query'] === '') {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}

// Escape % and _ then add wildcards
$search = $_GET['query'];
$search = str_replace(['%', '_'], ['\%', '\_'], $search);
$search = "%{$search}%";

// Query suggestions table by keywords
$sql = "SELECT id, title, type
        FROM suggestions
        WHERE symptom_keywords LIKE ?
        ORDER BY title ASC
        LIMIT 10";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => $conn->error]);
    exit;
}
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestions[] = [
        'id'    => $row['id'],
        'title' => $row['title'],
        'type'  => $row['type'],
        // IMPORTANT: link points to the existing view_suggestion.php
        'link'  => 'view_suggestion.php?id=' . $row['id']
    ];
}

header('Content-Type: application/json');
echo json_encode($suggestions);

$stmt->close();
$conn->close();
