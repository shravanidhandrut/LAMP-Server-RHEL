<?php
// ============================================
// DATABASE CONNECTION
// ============================================
$host     = 'localhost';
$dbname   = 'studentdb';
$username = 'lampuser';
$password = 'LampPass@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// ============================================
// HANDLE FORM ACTIONS (Add / Delete)
// ============================================

// Add a new student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name   = htmlspecialchars(trim($_POST['name']));
    $email  = htmlspecialchars(trim($_POST['email']));
    $course = htmlspecialchars(trim($_POST['course']));

    if ($name && $email && $course) {
        $stmt = $pdo->prepare("INSERT INTO students (name, email, course) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $course]);
        $success = "Student added successfully!";
    } else {
        $error = "All fields are required.";
    }
}

// Delete a student
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$id]);
    $success = "Student deleted.";
}

// Fetch all students
$students = $pdo->query("SELECT * FROM students ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records — LAMP Project</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            color: #333;
        }

        header {
            background: #c0392b;
            color: white;
            padding: 20px 40px;
        }

        header h1 { font-size: 24px; font-weight: 600; }
        header p  { font-size: 13px; opacity: 0.85; margin-top: 4px; }

        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }

        /* Messages */
        .msg-success {
            background: #d4edda; color: #155724;
            border: 1px solid #c3e6cb;
            padding: 12px 16px; border-radius: 6px; margin-bottom: 20px;
        }
        .msg-error {
            background: #f8d7da; color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 12px 16px; border-radius: 6px; margin-bottom: 20px;
        }

        /* Form Card */
        .card {
            background: white;
            border-radius: 10px;
            padding: 28px;
            margin-bottom: 32px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        .card h2 {
            font-size: 17px;
            margin-bottom: 20px;
            color: #c0392b;
            border-bottom: 2px solid #f0f2f5;
            padding-bottom: 10px;
        }

        .form-row {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .form-row input {
            flex: 1;
            min-width: 180px;
            padding: 10px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border 0.2s;
        }

        .form-row input:focus { border-color: #c0392b; }

        .btn-add {
            background: #c0392b;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-add:hover { background: #a93226; }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        thead { background: #c0392b; color: white; }
        thead th { padding: 13px 16px; text-align: left; font-size: 13px; font-weight: 600; }

        tbody tr { border-bottom: 1px solid #f0f2f5; transition: background 0.15s; }
        tbody tr:hover { background: #fdf5f5; }
        tbody tr:last-child { border-bottom: none; }

        td { padding: 13px 16px; font-size: 14px; }

        .badge {
            background: #fdecea;
            color: #c0392b;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .btn-delete {
            background: none;
            border: 1px solid #e74c3c;
            color: #e74c3c;
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-delete:hover { background: #e74c3c; color: white; }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
            font-size: 14px;
        }

        footer {
            text-align: center;
            padding: 30px;
            color: #aaa;
            font-size: 12px;
        }
    </style>
</head>
<body>

<header>
    <h1>Student Records Management</h1>
</header>

<div class="container">

    <?php if (isset($success)): ?>
        <div class="msg-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="msg-error"><?= $error ?></div>
    <?php endif; ?>

    <!-- ADD STUDENT FORM -->
    <div class="card">
        <h2>Add New Student</h2>
        <form method="POST">
            <div class="form-row">
                <input type="text"  name="name"   placeholder="Full Name"     required>
                <input type="email" name="email"  placeholder="Email Address" required>
                <input type="text"  name="course" placeholder="Course"        required>
                <button type="submit" name="add" class="btn-add">Add Student</button>
            </div>
        </form>
    </div>

    <!-- STUDENTS TABLE -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Added On</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr>
                    <td colspan="6" class="empty-state">
                        No students yet. Add one above!
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td><?= $s['id'] ?></td>
                    <td><?= $s['name'] ?></td>
                    <td><?= $s['email'] ?></td>
                    <td><span class="badge"><?= $s['course'] ?></span></td>
                    <td><?= date('d M Y', strtotime($s['created_at'])) ?></td>
                    <td>
                        <a href="?delete=<?= $s['id'] ?>"
                           class="btn-delete"
                           onclick="return confirm('Delete this student?')">
                           Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>
