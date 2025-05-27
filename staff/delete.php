<?php
// เรียกใช้การเชื่อมต่อฐานข้อมูล
$config = require_once '../config/database.php';

// ตรวจสอบว่ามี ID ที่ส่งมาหรือไม่
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: read.php');
    exit;
}

$id = $_GET['id'];

try {
    // เชื่อมต่อฐานข้อมูล
    $pdo = new PDO(
        "mysql:host={$config['db']['host']};dbname={$config['db']['database']};charset=utf8",
        $config['db']['username'],
        $config['db']['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // เตรียมคำสั่ง SQL สำหรับลบข้อมูล
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = :id");
    $stmt->bindParam(':id', $id);
    
    // ประมวลผลคำสั่ง SQL
    $stmt->execute();
    
    // กลับไปยังหน้ารายการ
    header('Location: read.php');
    exit;
} catch (PDOException $e) {
    die('ข้อผิดพลาด: ' . $e->getMessage());
}
?>
