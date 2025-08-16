<?php
session_start();
$conn = new mysqli("localhost", "root", "", "talentscout");

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recruiter_user = $_POST['recruiter_user'];
    $recruiter_pass = $_POST['recruiter_pass'];

    $query = "SELECT * FROM recruiters WHERE name = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $recruiter_user, $recruiter_pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['recruiter'] = $recruiter_user;
        header("Location: dashboard_recruiter.php");
        exit();
    } else {
        $error = "❌ Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recruiter Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('https://png.pngtree.com/thumb_back/fh260/background/20240727/pngtree-the-leaves-are-green-and-yellow-and-there-is-water-that-image_15928332.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-container {
            width: 350px;
            margin: 140px auto 0;
            padding: 30px;
            border-radius: 15px;
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            color: #fff;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 25px;
            color: #fff;
        }

        input[type="text"],
        input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.85);
            font-size: 15px;
        }

        input[type="submit"],
        .signup-btn {
            width: 95%;
            padding: 12px;
            margin: 10px auto;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            display: block;
            text-align: center;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        .signup-btn {
            background-color: #2ecc71;
            text-decoration: none;
        }

        .signup-btn:hover {
            background-color: #27ae60;
        }

        .error {
            color: yellow;
            background: rgba(0, 0, 0, 0.3);
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Recruiter Login</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="recruiter_user" placeholder="Username" required>
        <input type="password" name="recruiter_pass" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>

    <a class="signup-btn" href="signup_recruiter.php">Sign Up</a>
</div>

</body>
</html>
