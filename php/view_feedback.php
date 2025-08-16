<?php
session_start();
if (!isset($_SESSION['candidate_id'])) {
    header("Location: login_candidate.php");
    exit();
}

$candidate_id = $_SESSION['candidate_id'];
$conn = new mysqli("localhost", "root", "", "talentscout");

$query = "SELECT answers, feedback, evaluation_status FROM candidates WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $candidate_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>No test or feedback found yet.</p>";
    exit();
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Feedback</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f8fa;
            padding: 40px;
        }
        .box {
            background-color: #ffffff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .box h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #2980b9;
        }
        .qa-block {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h2>Your Test Feedback</h2>

<?php if (!empty($row['answers'])): ?>
<div class="box">
    <h3>Your Answers:</h3>
    <?php
    $answers = preg_split('/(Q\d+:)/', $row['answers'], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    for ($i = 0; $i < count($answers); $i += 2):
        $q = isset($answers[$i]) ? trim($answers[$i]) : '';
        $a = isset($answers[$i + 1]) ? trim($answers[$i + 1]) : '';
    ?>
        <div class="qa-block">
            <p><strong><?php echo htmlspecialchars($q); ?></strong></p>
            <p><?php echo nl2br(htmlspecialchars($a)); ?></p>
        </div>
    <?php endfor; ?>
</div>
<?php endif; ?>

<?php if (!empty($row['feedback'])): ?>
<div class="box">
    <h3>AI/Recruiter Feedback:</h3>
    <?php
    $lines = explode("\n", $row['feedback']);
    $currentBlock = [];
   $scores = [];
$lines = explode("\n", $row['feedback']);
$currentBlock = [];

foreach ($lines as $line) {
    $line = trim($line);

    if (preg_match('/^Answer\s*\d+/i', $line)) {
        if (!empty($currentBlock)) {
            echo "<div class='qa-block'>";
            echo "<p><strong>" . htmlspecialchars($currentBlock['answer'] ?? 'N/A') . "</strong></p>";
            echo "<p>Score: " . htmlspecialchars($currentBlock['score'] ?? 'N/A') . "</p>";
            echo "<p>Feedback: " . nl2br(htmlspecialchars($currentBlock['feedback'] ?? '')) . "</p>";
            echo "</div>";
        }
        $currentBlock = ['answer' => $line, 'score' => '', 'feedback' => ''];
    } elseif (stripos($line, 'score:') !== false) {
        // Match "Score: 8", "Score: 8/10", "Score - 9"
        if (preg_match('/Score\s*[:\-]?\s*(\d+)/i', $line, $match)) {
            $scoreValue = $match[1];
            $currentBlock['score'] = $scoreValue;
            $scores[] = (int)$scoreValue;
        }
    } elseif (stripos($line, 'feedback:') !== false) {
        $currentBlock['feedback'] = trim(str_ireplace('Feedback:', '', $line));
    } else {
        $currentBlock['feedback'] = ($currentBlock['feedback'] ?? '') . ' ' . $line;
    }
}

if (!empty($currentBlock)) {
    echo "<div class='qa-block'>";
    echo "<p><strong>" . htmlspecialchars($currentBlock['answer'] ?? 'N/A') . "</strong></p>";
    echo "<p>Score: " . htmlspecialchars($currentBlock['score'] ?? 'N/A') . "</p>";
    echo "<p>Feedback: " . nl2br(htmlspecialchars($currentBlock['feedback'] ?? '')) . "</p>";
    echo "</div>";
}

$averageScore = count($scores) > 0 ? round(array_sum($scores) / count($scores), 1) : "Not available";

    ?>
</div>
<div class="box">
    <h3>Overall Score:</h3>
    <p><?php echo is_numeric($averageScore) ? $averageScore . " / 10" : $averageScore; ?></p>
</div>

<?php endif; ?>

<div class="box">
    <h3>Status:</h3>
    <p><?php echo htmlspecialchars($row['evaluation_status']); ?></p>
</div>

<a href="candidate_dashboard.php"><button>Back to Dashboard</button></a>

</body>
</html>
