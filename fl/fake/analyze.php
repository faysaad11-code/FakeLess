<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$uploadDir = __DIR__ . DIRECTORY_SEPARATOR . "uploads";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// ── URL mode ──
if (!empty($_POST['image_url'])) {
    $url = filter_var(trim($_POST['image_url']), FILTER_VALIDATE_URL);
    if (!$url) {
        echo json_encode(['success' => false, 'error' => 'Invalid URL']);
        exit;
    }
    $ctx  = stream_context_create(['http' => ['timeout' => 15, 'follow_location' => true]]);
    $data = @file_get_contents($url, false, $ctx);
    if ($data === false) {
        echo json_encode(['success' => false, 'error' => 'Could not fetch image from URL']);
        exit;
    }
    $safeName   = "img_" . time() . "_" . mt_rand(1000, 9999) . ".jpg";
    $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $safeName;
    file_put_contents($targetPath, $data);

    $pythonExe  = '/usr/bin/python3';
    $scriptPath = __DIR__ . DIRECTORY_SEPARATOR . 'p.py';
    $command    = '"' . $pythonExe . '" "' . $scriptPath . '" "' . $targetPath . '" 2>&1';
    $output     = shell_exec($command);

    if ($output === null) { echo json_encode(['success' => false, 'error' => 'Python script did not execute']); exit; }
    $decoded = json_decode($output, true);
    if ($decoded === null) { echo json_encode(['success' => false, 'error' => 'Parse error: ' . $output]); exit; }
    if (isset($decoded['error'])) { echo json_encode(['success' => false, 'error' => $decoded['error']]); exit; }

    echo json_encode([
        'success'       => true,
        'prediction'    => $decoded['prediction'],
        'probabilities' => $decoded['probabilities'],
        'image'         => '/fl/fake/uploads/' . $safeName,
    ]);
    exit;
}

// ── File upload mode ──
if (!isset($_FILES['image'])) {
    echo json_encode(['success' => false, 'error' => 'No image provided']);
    exit;
}

$uploadDir = __DIR__ . DIRECTORY_SEPARATOR . "uploads";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Upload failed']);
    exit;
}

$ext        = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
$allowedExt = ['png', 'jpg', 'jpeg', 'bmp', 'webp'];

if (!in_array($ext, $allowedExt, true)) {
    echo json_encode(['success' => false, 'error' => 'Unsupported file type']);
    exit;
}

$safeName   = "img_" . time() . "_" . mt_rand(1000, 9999) . "." . $ext;
$targetPath = $uploadDir . DIRECTORY_SEPARATOR . $safeName;

if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
    echo json_encode(['success' => false, 'error' => 'Could not save image']);
    exit;
}

$pythonExe  = '/usr/bin/python3';
$scriptPath = __DIR__ . DIRECTORY_SEPARATOR . 'p.py';

$command = '"' . $pythonExe . '" "' . $scriptPath . '" "' . $targetPath . '" 2>&1';
$output  = shell_exec($command);

if ($output === null) {
    echo json_encode(['success' => false, 'error' => 'Python script did not execute']);
    exit;
}

$decoded = json_decode($output, true);

if ($decoded === null) {
    echo json_encode(['success' => false, 'error' => 'Failed to parse Python output: ' . $output]);
    exit;
}

if (isset($decoded['error'])) {
    echo json_encode(['success' => false, 'error' => $decoded['error']]);
    exit;
}

echo json_encode([
    'success'       => true,
    'prediction'    => $decoded['prediction'],
    'probabilities' => $decoded['probabilities'],
    'image'         => '/fl/fake/uploads/' . $safeName,
]);
