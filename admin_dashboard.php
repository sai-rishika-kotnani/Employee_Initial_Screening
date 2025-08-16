<?php
session_start();
$conn = new mysqli("localhost", "root", "", "talentscout");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$candidates_query = "SELECT c.id, c.name, ca.email, c.tech_stack, c.answers, c.score, c.feedback 
                     FROM candidates c
                     LEFT JOIN candidate_auth ca ON ca.id = c.auth_id";

$candidate_result = $conn->query($candidates_query);

$recruiters_query = "SELECT * FROM recruiters";
$recruiter_result = $conn->query($recruiters_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - TalentScout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f5;
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .tab-buttons {
            text-align: center;
            margin-bottom: 20px;
        }

        .tab-buttons button {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            background-color: #3498db;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .tab-buttons button:hover {
            background-color: #2980b9;
        }

        .tab-content {
            display: none;
            margin-top: 20px;
        }

        .tab-content.active {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            vertical-align: top;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        pre {
            background: #f8f8f8;
            padding: 10px;
            overflow-x: auto;
            border-radius: 4px;
            white-space: pre-wrap;
        }

        .export-btn {
            display: block;
            margin: 20px auto;
            padding: 12px 24px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .export-btn:hover {
            background-color: #219150;
        }
    </style>
</head>
<body>

<h1>Admin Dashboard - TalentScout</h1>
<a href="export_report.php"><button class="export-btn">📄 Export Full Report</button></a>

<div class="tab-buttons">
    <button onclick="switchTab('candidates')">🧑‍💻 Candidate Info</button>
    <button onclick="switchTab('recruiters')">🧑‍💼 Recruiter Info</button>
</div>

<div id="candidates" class="tab-content active">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Tech Stack</th>
                <th>Answers</th>
                <th>Score</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($candidate_result && $candidate_result->num_rows > 0) {
                while ($row = $candidate_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tech_stack']) . "</td>";
                    echo "<td><pre>" . htmlspecialchars($row['answers']) . "</pre></td>";
                    echo "<td>" . htmlspecialchars($row['score']) . "</td>";
                    echo "<td><pre>" . htmlspecialchars($row['feedback']) . "</pre></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No candidate data found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div id="recruiters" class="tab-content">
    <table>
        <thead>
            <tr>
                <th>Recruiter Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($recruiter_result && $recruiter_result->num_rows > 0) {
                while ($r = $recruiter_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($r['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($r['email']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No recruiter data found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
function switchTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.getElementById(tabId).classList.add('active');
}
</script>

</body>
</html>
<?php $conn->close(); ?>
