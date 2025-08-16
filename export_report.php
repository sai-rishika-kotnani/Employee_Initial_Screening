<?php
$conn = new mysqli("localhost", "root", "", "talentscout");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$candidateFile = tempnam(sys_get_temp_dir(), 'candidates_') . '.csv';
$recruiterFile = tempnam(sys_get_temp_dir(), 'recruiters_') . '.csv';

// Write candidate data
$candOut = fopen($candidateFile, 'w');
fputcsv($candOut, ['ID', 'Name', 'Email', 'Tech Stack', 'Answers', 'Score', 'Feedback']);
$candidateQuery = "SELECT c.id, c.name, ca.email, c.tech_stack, c.answers, c.score, c.feedback 
                   FROM candidates c
                   LEFT JOIN candidate_auth ca ON ca.id = c.auth_id";

$candidateResult = $conn->query($candidateQuery);
while ($row = $candidateResult->fetch_assoc()) {
    fputcsv($candOut, $row);
}
fclose($candOut);

$recOut = fopen($recruiterFile, 'w');
fputcsv($recOut, ['ID', 'Recruiter Name', 'Email']);
$recruiterQuery = "SELECT id, name, email FROM recruiters";
$recruiterResult = $conn->query($recruiterQuery);
while ($r = $recruiterResult->fetch_assoc()) {
    fputcsv($recOut, $r);
}
fclose($recOut);

$zipName = "talentscout_report_" . date("Y-m-d_H-i-s") . ".zip";
$zipPath = sys_get_temp_dir() . '/' . $zipName;

$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
    $zip->addFile($candidateFile, "Candidate_Report.csv");
    $zip->addFile($recruiterFile, "Recruiter_Report.csv");
    $zip->close();

    // Output zip file to browser
    header('Content-Type: application/zip');
    header("Content-Disposition: attachment; filename=$zipName");
    readfile($zipPath);

    // Cleanup
    unlink($candidateFile);
    unlink($recruiterFile);
    unlink($zipPath);
    exit();
} else {
    echo "❌ Failed to create ZIP file.";
}
?>
