<?php
session_start();
$conn = new mysqli("localhost", "root", "", "talentscout");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $name = $_POST["name"];
$email = $_POST["email"];
$pass = $_POST["password"];
$check = $conn->prepare("SELECT * FROM candidate_auth WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    $message = "❌ Email already registered.";
} else {
    // Insert into candidate_auth first
    $stmt = $conn->prepare("INSERT INTO candidate_auth (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $pass);

    if ($stmt->execute()) {
        $auth_id = $stmt->insert_id;  // Get the new candidate_auth ID

        // Now insert into candidates with auth_id and name
        $stmt2 = $conn->prepare("INSERT INTO candidates (auth_id, name) VALUES (?, ?)");
        $stmt2->bind_param("is", $auth_id, $name);

        if ($stmt2->execute()) {
            $message = "✅ Signup successful. <a class='login-link' href='login_candidate.php'>Login now</a>";
        } else {
            $message = "❌ Failed to save candidate profile.";
        }
    } else {
        $message = "❌ Signup failed. Please try again.";
    }
}

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Candidate Signup</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('https://images.unsplash.com/photo-1552845108-5f775a2ccb9b?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Ymx1ZSUyMG1vdW50YWlufGVufDB8fDB8fHww') no-repeat center center fixed;
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
            color: #fff;
            margin-top: 15px;
        }

        .login-link {
            display: block;
            margin-top: 10px;
            color: #f1c40f;
            text-decoration: underline;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2 style = "color:#333";>Candidate Signup</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Sign Up">
    </form>
</div>

</body>
</html>