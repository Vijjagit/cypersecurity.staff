<?php
require_once '../includes/header.php';

// เรียกใช้การเชื่อมต่อฐานข้อมูล
$config = require_once '../config/database.php';

$employees = [];
$error = '';

try {
    // เชื่อมต่อฐานข้อมูล
    $pdo = new PDO(
        "mysql:host={$config['db']['host']};dbname={$config['db']['database']};charset=utf8",
        $config['db']['username'],
        $config['db']['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // เตรียมคำสั่ง SQL สำหรับดึงข้อมูล
    $stmt = $pdo->query("SELECT * FROM employees ORDER BY id DESC");
    
    // ดึงข้อมูลทั้งหมด
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'ข้อผิดพลาด: ' . $e->getMessage();
}
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>รายชื่อบุคลากรทั้งหมด</h4>
        <a href="create.php" class="btn btn-primary">เพิ่มบุคลากรใหม่</a>
    </div>
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (empty($employees) && empty($error)): ?>
            <div class="alert alert-info">ไม่พบข้อมูลบุคลากร</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>อีเมล</th>
                            <th>ตำแหน่ง</th>
                            <th>เงินเดือน</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td><?php echo $employee['id']; ?></td>
                                <td><?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?></td>
                                <td><?php echo $employee['email']; ?></td>
                                <td><?php echo $employee['position']; ?></td>
                                <td><?php echo number_format($employee['salary'], 2); ?></td>
                                <td>
                                    <a href="update.php?id=<?php echo $employee['id']; ?>" class="btn btn-sm btn-warning">แก้ไข</a>
                                    <a href="delete.php?id=<?php echo $employee['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('คุณต้องการลบข้อมูลนี้หรือไม่?')">ลบ</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>
