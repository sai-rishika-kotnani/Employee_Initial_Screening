<?php
session_start();
if (!isset($_SESSION['recruiter'])) {
    header("Location: login_recruiter.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "talentscout");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "
    SELECT c.name, ca.email, c.tech_stack, c.answers, c.score, c.feedback, c.evaluation_status, c.id
    FROM candidates c
    LEFT JOIN candidate_auth ca ON ca.id = c.auth_id
    WHERE c.submission_status = 'Submitted'
";


$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recruiter Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #ecf0f3, #f5f9fc);
            padding: 0;
            margin: 0;
        }

        header {
            background-color: #2980b9;
            color: white;
            padding: 25px 0;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin: 30px 0 10px;
        }

        .table-wrapper {
            max-width: 1100px;
            margin: auto;
            overflow-x: auto;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(6px);
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:hover {
            background-color: #f4f9fd;
        }

        pre {
            max-height: 100px;
            overflow-y: auto;
            background: #f3f3f3;
            padding: 10px;
            border-radius: 5px;
            white-space: pre-wrap;
        }

        .done {
            color: #27ae60;
            font-weight: bold;
        }

        .edit-btn {
            display: inline-block;
            margin-top: 6px;
            font-size: 14px;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
        }

        .edit-btn:hover {
            text-decoration: underline;
        }

        .logout {
            text-align: center;
            margin: 40px 0;
        }

        .logout a button {
            padding: 10px 24px;
            font-size: 16px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .logout a button:hover {
            background-color: #c0392b;
        }

        p.centered {
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>

<header>Recruiter Dashboard</header>
<h2>Submitted Candidate Tests</h2>

<div class="table-wrapper">
<?php
if ($result && $result->num_rows > 0) {
    echo "<table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Tech Stack</th>
                    <th>Answers</th>
                    <th>Score</th>
                    <th>Feedback</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>";

    while ($row = $result->fetch_assoc()) {
        $candidate_id = $row['id'];
        $score = $row['score'];
        $feedback = $row['feedback'];
        $evaluation_status = $row['evaluation_status'];

        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['tech_stack']) . "</td>";
        echo "<td><pre>" . nl2br(htmlspecialchars($row['answers'])) . "</pre></td>";
        echo "<td>" . ($score !== null ? htmlspecialchars($score) : '<em>Pending</em>') . "</td>";
        echo "<td><pre>" . ($feedback !== null ? htmlspecialchars($feedback) : '<em>Pending</em>') . "</pre></td>";

        echo "<td>";
        if ($evaluation_status !== 'Done') {
            echo "<form method='POST' action='php/finalize_evaluation.php' style='display:inline;'>
                    <input type='hidden' name='candidate_id' value='$candidate_id'>
                    <button type='submit'>Mark as Done</button>
                  </form>";
        } else {
            echo "<span class='done'>✅ Done</span><br>";
        }

        echo "<a href=\"provide_feedback.php?candidate_id={$candidate_id}\" class='edit-btn'>Edit Feedback</a>";
        echo "</td></tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p class='centered'>No submitted candidates yet.</p>";
}
?>
</div>

<div class="logout">
    <a href="logout.php"><button>Logout</button></a>
</div>

</body>
</html>

<?php $conn->close(); ?>
