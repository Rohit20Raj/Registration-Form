<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "health_records";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve email ID from the query string
$email = $_GET['email'];

// SQL query to fetch the file path based on the email ID
$query = "SELECT file_path FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // User report found, generate HTML page with link to download the report
        $row = $result->fetch_assoc();
        $filePath = $row['file_path'];
        $html = "<html>
        <head>
            <title>Health Report</title>
        </head>
        <body>
            <h1>Health Report</h1>
            <a href='$filePath' download>Download Health Report</a>
        </body>
        </html>";
    } else {
        // User report not found, display error message
        $errorMessage = "No health report found for the provided email";
    }

// If there is an error message, generate HTML page with the error message
if (isset($errorMessage)) {
    $html = "<html>
    <head>
        <title>Error</title>
    </head>
    <body>
        <h1>Error</h1>
        <p>$errorMessage</p>
    </body>
    </html>";
}

// Set appropriate headers and send the HTML page as the response
header("Content-Type: text/html");
echo $html;
?>