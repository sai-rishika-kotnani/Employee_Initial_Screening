<?php
if (!isset($_GET['candidate_id']) || empty($_GET['candidate_id'])) {
    die("Error: candidate_id not provided.");
}

$candidate_id = $_GET['candidate_id'];
$conn = new mysqli("localhost", "root", "", "talentscout");

// Check for DB connection errors
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get candidate data
$query = "SELECT * FROM candidates WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $candidate_id);
$stmt->execute();
$result = $stmt->get_result();
$candidate = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Feedback for <?php echo htmlspecialchars($candidate['name']); ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #d7e1ec, #f6f9fc);
            margin: 0;
            padding: 50px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #f4f6f9;
            color: #2c3e50;
            font-weight: 600;
        }

        textarea {
            width: 100%;
            height: 160px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            font-size: 14px;
            resize: vertical;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        .submit-row {
            text-align: center;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<h2>Edit Feedback for <?php echo htmlspecialchars($candidate['name']); ?></h2>

<form method="POST" action="save_feedback.php">
    <input type="hidden" name="candidate_id" value="<?php echo $candidate['id']; ?>">

    <table>
        <tr>
            <th>Candidate Name</th>
            <td><?php echo htmlspecialchars($candidate['name']); ?></td>
        </tr>
        <!-- Removed the Tech Stack field -->
        <tr>
            <th>AI Feedback</th>
            <td>
                <textarea name="overall_feedback" placeholder="Edit or enhance the AI feedback..."><?php echo htmlspecialchars($candidate['feedback']); ?></textarea>
            </td>
        </tr>
        <tr class="submit-row">
            <td colspan="2">
                <input type="submit" value="Update Feedback">
            </td>
        </tr>
    </table>
</form>

</body>
</html>
