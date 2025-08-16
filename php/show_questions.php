<?php
session_start();

if (!isset($_SESSION['test_id'])) {
    header("Location: candidate_dashboard.php");
    exit();
}

$candidate_id = intval($_SESSION['test_id']);

$conn = new mysqli("localhost", "root", "", "talentscout");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch tech stack
$stmt = $conn->prepare("SELECT tech_stack FROM candidates WHERE id = ?");
$stmt->bind_param("i", $candidate_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $tech_stack = $row['tech_stack'];
} else {
    echo "Candidate not found.";
    exit();
}
$stmt->close();
$conn->close();

// Generate questions using Python
$command = escapeshellcmd("python generate_questions.py \"$tech_stack\"");
$output = shell_exec($command);

// Handle failure
if (!$output) {
    $questions_html = "❌ Error generating questions. Please try again.";
} else {
    // Split questions into array (assumes 1. 2. 3. format)
    $question_lines = preg_split('/\n+/', trim($output));
    $questions_html = "";
    foreach ($question_lines as $index => $q) {
        $q_clean = htmlspecialchars($q);
        $questions_html .= "<p><strong>$q_clean</strong></p>";
        $questions_html .= "<textarea name='answer$index' rows='4' cols='80' placeholder='Your answer here...' required></textarea><br><br>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Interview Questions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Your Interview Questions</h2>

    <div id="loader">Generating questions, please wait...</div>

    <div id="questions" style="padding: 20px; background: #f0f0f0; border-radius: 10px; display: none;">
        <form action="submit_answers.php" method="POST">
            <h3>Generated Questions:</h3>
            <?php echo $questions_html; ?>

            <!-- Pass all questions as hidden (to be used by evaluator) -->
            <input type="hidden" name="questions_raw" value="<?php echo htmlspecialchars($output); ?>">

            <br>
            <button type="submit">Submit Answers</button>
        </form>
    </div>

    <script>
        document.getElementById("loader").style.display = "none";
        document.getElementById("questions").style.display = "block";
    </script>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Your Interview Questions</title>
    <link rel="stylesheet" href="styles.css">
    <style>
   /* General Reset and Body Styling */
body {
  margin: 0;
  padding: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: url('https://www.shutterstock.com/shutterstock/photos/2307970235/display_1500/stock-vector-question-mark-background-illustration-of-question-or-curiosity-image-2307970235.jpg') no-repeat center center fixed;
  background-size: cover;
  color: #2c3e50;
}

/* Header */
h2 {
  text-align: center;
  margin-top: 40px;
  font-size: 28px;
  color: #2c3e50;
}

h3 {
  margin-bottom: 20px;
  color: #2c3e50;
}

/* Loader */
#loader {
  text-align: center;
  font-size: 18px;
  margin-top: 30px;
  color: #888;
}

/* Questions Container */
#questions {
  width: 90%;
  max-width: 1000px;
  margin: 40px auto;
  background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
  padding: 40px;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.2);
  box-sizing: border-box;
}

#questions p {
  font-size: 18px;
  margin-bottom: 10px;
}

textarea {
  width: 100%;
  max-width: 100%;
  box-sizing: border-box;
  padding: 14px;
  font-size: 16px;
  border-radius: 8px;
  border: 1px solid #ccc;
  resize: vertical;
  background-color: #fff;
  margin-bottom: 20px;
}

textarea:focus {
  border-color: #3498db;
  outline: none;
}

button[type="submit"] {
  background-color: #3498db;
  color: white;
  padding: 14px 24px;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  cursor: pointer;
  margin-top: 20px;
  transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
  background-color: #2980b9;
}

</style>
</head>
<body>

    <div id="loader">Generating questions, please wait...</div>

    <div id="questions" style="padding: 20px; background: #f0f0f0; border-radius: 10px; display: none;">
        <form action="submit_answers.php" method="POST">
            <h3>Generated Questions:</h3>
            <?php echo $questions_html; ?>

            <!-- Pass all questions as hidden (to be used by evaluator) -->
            <input type="hidden" name="questions_raw" value="<?php echo htmlspecialchars($output); ?>">

            <br>
            <button type="submit">Submit Answers</button>
        </form>
    </div>

    <script>
        document.getElementById("loader").style.display = "none";
        document.getElementById("questions").style.display = "block";
    </script>
</body>
</html>
