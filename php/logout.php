<?php
session_start();        // Start the session
session_destroy();      // Destroy all session data
header("Location: ../index.html");  // Redirect to index.html one level up
exit();                 // Always good to call exit after a redirect
?>
