<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['candidate_id']) || !isset($_POST['overall_feedback'])) {
        die("❌ Error: Required fields missing.");
    }

    $candidate_id = $_POST['candidate_id'];
    $final_feedback = trim($_POST['overall_feedback']);

    // Extract scores from feedback
    preg_match_all('/Score\s*[:\-]?\s*(\d+)/i', $final_feedback, $matches);
    $scores = array_map('intval', $matches[1]);
    $average_score = count($scores) > 0 ? round(array_sum($scores) / count($scores), 1) : null;

    $conn = new mysqli("localhost", "root", "", "talentscout");

    if ($conn->connect_error) {
        die("❌ Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE candidates SET feedback = ?, score = ?, feedback_status = 'Available', evaluation_status = 'Done' WHERE id = ?");
    $stmt->bind_param("sii", $final_feedback, $average_score, $candidate_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('✅ Feedback and score updated successfully');
                window.location.href = 'dashboard_recruiter.php';
              </script>";
        exit;
    } else {
        echo "❌ Error executing query: " . $stmt->error;
    }
} else {
    echo "❌ Invalid request method.";
}
?>
