<?php

$conn = new mysqli("localhost", "root", "", "talentscout");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1: Get duplicate auth_ids
$duplicates = $conn->query("
    SELECT auth_id 
    FROM candidates 
    GROUP BY auth_id 
    HAVING COUNT(*) > 1
");

while ($dup = $duplicates->fetch_assoc()) {
    $auth_id = $dup['auth_id'];

    // Step 2: Fetch the two rows
    $stmt = $conn->prepare("SELECT * FROM candidates WHERE auth_id = ?");
    $stmt->bind_param("i", $auth_id); // Use 'i' for integer binding
    $stmt->execute();
    $result = $stmt->get_result();
    
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    if (count($rows) !== 2) continue; // Only handle 2 rows

    $merged = [];
    foreach ($rows[0] as $key => $value) {
        $merged[$key] = !empty($rows[0][$key]) ? $rows[0][$key] : $rows[1][$key];
        if (empty($merged[$key]) && !empty($rows[1][$key])) {
            $merged[$key] = $rows[1][$key];
        }
    }

    // Step 3: Keep first row, update with merged data
    $id_to_keep = $rows[0]['id'];
    $id_to_delete = $rows[1]['id'];

    $stmt_update = $conn->prepare("
        UPDATE candidates SET name=?, phone=?, experience=?, position=?, location=?, tech_stack=?, 
        answers=?, feedback=?, score=?, submission_status=?, evaluation_status=?, feedback_status=? 
        WHERE id = ?
    ");
    $stmt_update->bind_param(
        "ssisssssssssi",
        $merged['name'],
        $merged['phone'],
        $merged['experience'],
        $merged['position'],
        $merged['location'],
        $merged['tech_stack'],
        $merged['answers'],
        $merged['feedback'],
        $merged['score'],
        $merged['submission_status'],
        $merged['evaluation_status'],
        $merged['feedback_status'],
        $id_to_keep
    );
    $stmt_update->execute();

    // Step 4: Delete the second row
    $stmt_delete = $conn->prepare("DELETE FROM candidates WHERE id = ?");
    $stmt_delete->bind_param("i", $id_to_delete); // Use 'i' for integer binding
    $stmt_delete->execute();
}

echo "✅ Merging complete.";

$conn->close();
?>
