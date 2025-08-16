<?php
session_start();
$conn = new mysqli("localhost", "root", "", "talentscout");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $pass = $_POST["password"];

    $stmt = $conn->prepare("SELECT a.id as auth_id, a.email, c.id as candidate_id, c.name FROM candidate_auth a 
JOIN candidates c ON a.id = c.auth_id
WHERE a.email = ? AND a.password = ?");

    $stmt->bind_param("ss", $email, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
       $_SESSION['candidate_id'] = $row['candidate_id'];
$_SESSION['auth_id'] = $row['auth_id'];
$_SESSION['candidate_email'] = $row['email'];


        // ✅ Redirect using PHP
        header("Location: candidate_dashboard.php");
        exit();
    } else {
        $error = "❌ Invalid credentials.";
    }
    $stmt->close();
$conn->close();

}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Candidate Login</title>
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
    margin: 150px auto 0 auto; /* Moved box down */
    padding: 30px;
    border-radius: 15px;
    backdrop-filter: blur(8px);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    color: #333;
    text-align: center;
}

.login-container h2 {
    margin-bottom: 25px;
    color: #fff;
}

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
}

.signup-btn:hover {
    background-color: #27ae60;
}

.error {
    color: red;
    margin-bottom: 10px;
}

    </style>
</head>
<body>

<div class="login-container">
    <h2 style = "color: #333;">Candidate Login</h2>

    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Login">
    </form>

    <a href="signup_candidate.php" class="signup-btn">Sign Up</a>
</div>

</body>
</html>
