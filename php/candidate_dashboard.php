<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['auth_id'])) {
    header("Location: login_candidate.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "talentscout");

$auth_id = $_SESSION['auth_id'];

$query = "
SELECT c.name, a.email, c.submission_status, c.evaluation_status, c.feedback_status
FROM candidates c
JOIN candidate_auth a ON c.auth_id = a.id
WHERE a.id = ?
";


$stmt = $conn->prepare($query);
$stmt->bind_param("i", $auth_id);
$stmt->execute();
$result = $stmt->get_result();
$candidate = $result && $result->num_rows > 0 ? $result->fetch_assoc() : null;

// Progress bar logic
$progress = 0;
$status_class = "not-started";
$status_text = "Not Started";
$bar_color = "#e74c3c"; // red by default

if ($candidate) {
    if ($candidate['submission_status'] === 'Submitted') {
        if ($candidate['evaluation_status'] === 'Under Evaluation') {
            $progress = 50;
            $status_class = "under-evaluation";
            $status_text = "Under Evaluation";
            $bar_color = "#f39c12"; // orange
        } elseif ($candidate['evaluation_status'] === 'Done') {
            $progress = 100;
            $status_class = "done";
            $status_text = "Completed";
            $bar_color = "#2ecc71"; // green
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Candidate Dashboard</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f9f9f9;
      color: #333;
      background: url('https://images.unsplash.com/photo-1742729251767-8f9cef8806a3?w=700&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTI2fHxzdGFja3N8ZW58MHx8MHx8fDA%3D') no-repeat center center fixed;
      background-size: cover;
    }

    .dashboard {
      max-width: 600px;
      margin: 60px auto;
      background: #fff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      text-align: center;
    }

    h1 {
      font-size: 24px;
      margin-bottom: 10px;
    }

    h1 .emoji {
      margin-right: 8px;
    }

    p {
      font-size: 16px;
      margin-bottom: 30px;
      color: #666;
    }

    h2 {
      font-size: 20px;
      margin-bottom: 15px;
      color: #2c3e50;
    }

    .progress-wrapper {
      margin: 30px 0;
    }

    .progress-bar {
      height: 16px;
      background-color: #e0e0e0;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
    }

    .progress-fill {
      height: 100%;
      transition: width 0.4s ease-in-out;
    }

    .status-text {
      margin-top: 8px;
      font-weight: bold;
    }

    button {
      padding: 12px 24px;
      margin: 10px 5px;
      font-size: 15px;
      border: none;
      background-color: #3498db;
      color: white;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #2980b9;
    }

    .error {
      color: #e74c3c;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <?php if ($candidate): ?>
      <h1><span class="emoji">👋</span>Welcome, <?php echo htmlspecialchars($candidate['name']); ?>!</h1>
      <p>This is your dashboard.</p>

      <h2>Progress</h2>
      <div class="progress-wrapper">
        <div class="progress-bar">
          <div class="progress-fill" style="width: <?php echo $progress; ?>%; background-color: <?php echo $bar_color; ?>;"></div>
        </div>
        <div class="status-text" style="color: <?php echo $bar_color; ?>;"><?php echo $status_text; ?></div>
      </div>

      <?php if ($candidate['submission_status'] === 'Not Started'): ?>
        <a href="take_test.php"><button>Take Test</button></a>
      <?php endif; ?>

      <?php if ($candidate['feedback_status'] === 'Available'): ?>
        <a href="view_feedback.php"><button>View Feedback</button></a>
      <?php endif; ?>

    <?php else: ?>
      <h1><span class="emoji">👋</span>Welcome!</h1>
      <p>This is your dashboard.</p>
      <p class="error">Candidate data not found. Please contact support.</p>
    <?php endif; ?>

    <a href="logout.php"><button>Logout</button></a>
  </div>
</body>
</html>
