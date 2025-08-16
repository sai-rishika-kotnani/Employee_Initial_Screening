<?php
$conn = new mysqli("localhost", "root", "", "talentscout");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recruiter_name = $_POST['recruiter_user'];
    $recruiter_pass = $_POST['recruiter_pass'];
    $recruiter_email = $_POST['recruiter_email'];

    // Check if name already exists
    $check = $conn->prepare("SELECT * FROM recruiters WHERE name = ?");
    $check->bind_param("s", $recruiter_name);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "❌ Username already taken. Please choose another.";
    } else {
        $stmt = $conn->prepare("INSERT INTO recruiters (name, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $recruiter_name, $recruiter_pass, $recruiter_email);

        if ($stmt->execute()) {
            header("Location: recruiter_login.php?registered=1");
            exit();
        } else {
            $message = "❌ Error registering recruiter.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recruiter Signup</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('https://png.pngtree.com/thumb_back/fh260/background/20240727/pngtree-the-leaves-are-green-and-yellow-and-there-is-water-that-image_15928332.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }

        .signup-container {
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

        .signup-container h2 {
            margin-bottom: 25px;
            color: #fff;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.85);
            font-size: 15px;
        }

        input[type="submit"] {
            width: 95%;
            padding: 12px;
            margin: 10px auto;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            display: block;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #27ae60;
        }

        .message {
            color: white;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .login-link {
            color:rgb(0, 0, 0);
            text-decoration: underline;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="signup-container">
    <h2>Recruiter Signup</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="recruiter_user" placeholder="Username" required><br>
        <input type="password" name="recruiter_pass" placeholder="Password" required><br>
        <input type="email" name="recruiter_email" placeholder="Email" required><br>
        <input type="submit" value="Sign Up">
    </form>
   
</div>

</body>
</html>
