<?php
error_reporting(0);
ini_set('display_errors', 0);
putenv('DYLD_LIBRARY_PATH');
putenv('PATH=/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/bin');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$uploadDir = _DIR_ . DIRECTORY_SEPARATOR . "uploads";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$pythonExe  = '/usr/bin/python3';
$scriptPath = _DIR_ . DIRECTORY_SEPARATOR . 'p.py';

// معالجة ملف الصورة المرفوع
if (isset($_FILES['image'])) {
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

    $command = escapeshellcmd($pythonExe) . ' ' . escapeshellarg($scriptPath) . ' ' . escapeshellarg($targetPath) . ' 2>&1';
    $output  = shell_exec($command);

    // استخراج نص الـ JSON بين أول { وآخر } لتجاهل أي تحذيرات أخر
    $jsonString = '';
    $startPos   = strpos($output, '{');
    $endPos     = strrpos($output, '}');

    if ($startPos !== false && $endPos !== false && $endPos >= $startPos) {
        $jsonString = substr($output, $startPos, $endPos - $startPos + 1);
    }

    $decoded = json_decode($jsonString, true);

    if ($decoded === null) {
        echo json_encode(['success' => false, 'error' => 'Python execution error: ' . trim($output)]);
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
        'image'         => './uploads/' . $safeName,
    ]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'No image provided']);