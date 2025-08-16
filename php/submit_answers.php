<?php
session_start();

if (!isset($_SESSION['test_id']) || !isset($_SESSION['candidate_id'])) {
    header("Location: login_candidate.php");
    exit();
}

$candidate_id = $_SESSION['candidate_id'];
$questions = $_POST['questions_raw'] ?? '';

// Combine all submitted answers
$answers_combined = '';
for ($i = 0; isset($_POST["answer$i"]); $i++) {
    $answers_combined .= "Q" . ($i + 1) . ": " . trim($_POST["answer$i"]) . "\n";
}

// OpenAI API call
$api_key = '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';

$prompt = "Evaluate each of the following answers separately. For each one, use the following format:\n\nAnswer 1:\nScore: 8\nFeedback: [Your comment]\n\nQuestions:\n$questions\n\nAnswers:\n$answers_combined";

$data = [
    'model' => 'gpt-3.5-turbo',
    'messages' => [['role' => 'user', 'content' => $prompt]],
    'max_tokens' => 500
];

$headers = [
    'Content-Type: application/json',
    'Authorization: ' . 'Bearer ' . $api_key
];

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    die("Error calling OpenAI: " . curl_error($ch));
}
curl_close($ch);

$response_data = json_decode($response, true);

// Validate response
if (!isset($response_data['choices'][0]['message']['content'])) {
    die("❌ OpenAI did not return valid feedback. Response: <pre>" . print_r($response_data, true) . "</pre>");
}

$feedback_raw = $response_data['choices'][0]['message']['content'];

// ✅ Extract all scores and average them
preg_match_all('/score\s*[:\-]?\s*(\d+)/i', $feedback_raw, $matches);
$scores = array_map('intval', $matches[1]);
$score = count($scores) > 0 ? round(array_sum($scores) / count($scores)) : 0;

// ✅ Store in DB
$conn = new mysqli("localhost", "root", "", "talentscout");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("
    UPDATE candidates 
    SET answers = ?, feedback = ?, score = ?, 
        evaluation_status = 'Under Evaluation', 
        submission_status = 'Submitted' 
    WHERE id = ?
");

if (!$stmt) {
    die("SQL prepare failed: " . $conn->error);
}

$stmt->bind_param("ssii", $answers_combined, $feedback_raw, $score, $candidate_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "✅ Answers submitted and evaluated!";
    header("Location: candidate_dashboard.php");
    exit();
} else {
    echo "❌ Error saving data: " . $stmt->error;
}
?>
