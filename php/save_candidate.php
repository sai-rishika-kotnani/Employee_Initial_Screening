<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $experience = $_POST["experience"];
    $position = $_POST["position"];
    $location = $_POST["location"];
    $tech_stack = $_POST["tech_stack"];

    // OpenAI API call
    $api_key = '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';
    $url = "https://api.openai.com/v1/chat/completions";

    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'user', 'content' => "Generate 3 technical interview questions for a candidate skilled in: $tech_stack"]
        ],
        'max_tokens' => 200
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        exit();
    }

    curl_close($ch);
    $response_data = json_decode($response, true);

    // Validate OpenAI response
    if (isset($response_data['choices'][0]['message']['content'])) {
        $questions = $response_data['choices'][0]['message']['content'];
    } else {
        echo "<pre>";
        print_r($response_data);
        echo "</pre>";
        die("Error: Failed to get questions from OpenAI.");
    }

    // Database connection
    $conn = new mysqli("localhost", "root", "", "talentscout");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // OPTIONAL: Add 'questions' column to your table if not yet added
   $stmt = $conn->prepare("INSERT INTO candidates (name, phone, experience, position, location, tech_stack, submission_status, auth_id) 
                        VALUES (?, ?, ?, ?, ?, ?, 'Not Started', ?)");
$stmt->bind_param("ssisssi", $name, $phone, $experience, $position, $location, $tech_stack, $_SESSION['auth_id']);


    if ($stmt->execute()) {
        $candidate_id = $stmt->insert_id;

        // Save data in session
        $_SESSION['test_id'] = $candidate_id;
     

        // Redirect to show_questions.php
        header("Location: show_questions.php");
        exit();
    } else {
        echo "Error saving data: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
