<?php
session_start();
if (!isset($_SESSION['candidate_id'])) {
    header("Location: login_candidate.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Take Test</title>
  <style>
    /* Reset and body styling */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f8;
      color: #2c3e50;
    }

    /* Header */
    h2 {
      text-align: center;
      margin-top: 40px;
      font-size: 30px;
      color: #34495e;
    }

    /* Form Container */
    form {
      width: 90%;
      max-width: 800px;
      margin: 40px auto;
      background-color: #ffffff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
      box-sizing: border-box;
    }

    /* Input Fields */
    input[type="text"],
    input[type="email"],
    input[type="number"],
    textarea {
      width: 100%;
      padding: 14px 16px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
      box-sizing: border-box;
      background-color: #fdfdfd;
    }

    input:focus,
    textarea:focus {
      border-color: #3498db;
      outline: none;
    }

    /* Submit Button */
    input[type="submit"] {
      background-color: #3498db;
      color: #fff;
      border: none;
      padding: 14px 28px;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: #2980b9;
    }

    /* Responsive */
    @media (max-width: 600px) {
      form {
        padding: 20px;
      }

      h2 {
        font-size: 24px;
      }
    }
  </style>
</head>
<body >
  <h2>Candidate Information</h2>
  <form action="save_candidate.php" method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone" required>
    <input type="number" name="experience" placeholder="Experience (years)" required>
    <input type="text" name="position" placeholder="Position" required>
    <input type="text" name="location" placeholder="Location" required>
    <textarea name="tech_stack" placeholder="Tech Stack (e.g., Python, React)" required></textarea>
    <input type="submit" value="Generate Questions">
  </form>
</body>
</html>
