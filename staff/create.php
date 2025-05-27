<?php
require_once '../includes/header.php';

// เรียกใช้การเชื่อมต่อฐานข้อมูล
$config = require_once '../config/database.php';

$message = '';
$error = '';

// ตรวจสอบว่ามีการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $position = $_POST['position'] ?? '';
    $salary = $_POST['salary'] ?? 0;
    
    // ตรวจสอบข้อมูล
    if (empty($firstName) || empty($lastName) || empty($email)) {
        $error = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    } else {
        try {
            // เชื่อมต่อฐานข้อมูล
            $pdo = new PDO(
                "mysql:host={$config['db']['host']};dbname={$config['db']['database']};charset=utf8",
                $config['db']['username'],
                $config['db']['password']
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // เตรียมคำสั่ง SQL สำหรับเพิ่มข้อมูล
            $stmt = $pdo->prepare("
                INSERT INTO employees (first_name, last_name, email, position, salary)
                VALUES (:first_name, :last_name, :email, :position, :salary)
            ");
            
            // ผูกค่าพารามิเตอร์
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':position', $position);
            $stmt->bindParam(':salary', $salary);
            
            // ประมวลผลคำสั่ง SQL
            if ($stmt->execute()) {
                $message = 'เพิ่มข้อมูลบุคลากรเรียบร้อยแล้ว';
            } else {
                $error = 'ไม่สามารถเพิ่มข้อมูลบุคลากรได้';
            }
        } catch (PDOException $e) {
            $error = 'ข้อผิดพลาด: ' . $e->getMessage();
        }
    }
}
?>

<div class="card">
    <div class="card-header">
        <h4>เพิ่มข้อมูลบุคลากร</h4>
    </div>
    <div class="card-body">
        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="mb-3">
                <label for="first_name" class="form-label">ชื่อ</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            
            <div class="mb-3">
                <label for="last_name" class="form-label">นามสกุล</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">อีเมล</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="mb-3">
                <label for="position" class="form-label">ตำแหน่ง</label>
                <input type="text" class="form-control" id="position" name="position">
            </div>
            
            <div class="mb-3">
                <label for="salary" class="form-label">เงินเดือน</label>
                <input type="number" class="form-control" id="salary" name="salary" min="0">
            </div>
            
            <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
            <a href="../index.php" class="btn btn-secondary">ยกเลิก</a>
        </form>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>
