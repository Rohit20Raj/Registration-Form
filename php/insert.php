<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "health_records";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$createDBQuery = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($createDBQuery) === FALSE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create the table if it doesn't exist
$createTableQuery = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    age INT(3) NOT NULL,
    weight FLOAT NOT NULL,
    email VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL
)";
if ($conn->query($createTableQuery) === FALSE) {
    die("Error creating table: " . $conn->error);
}

// Retrieve form data
$name = $_POST['name'];
$age = $_POST['age'];
$weight = $_POST['weight'];
$email = $_POST['email'];

// Check if user with the same email already exists
$checkQuery = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($checkQuery);
if ($result->num_rows > 0) {
    $errorMessage = "User with the email '$email' already exists";
} else {
    // File handling
$file = $_FILES['healthReport'];
$fileName = $file['name'];
$fileTmpName = $file['tmp_name'];
$fileType = $file['type'];

// Check if the file is a PDF
if ($fileType !== 'application/pdf') {
    $errorMessage = "Only PDF files are allowed";
} else {
    // Generate a new file name using the user's email ID and the .pdf extension
    $newFileName = $email . ".pdf";

    // Set the destination path with the new file name
    $destination = '../user_health_reports/' . $newFileName;

    // Move the uploaded file to the destination directory with the new file name
    if (move_uploaded_file($fileTmpName, $destination)) {
        // File moved successfully
        $successMessage = "File uploaded successfully";

        // SQL query to insert user details and file path
        $insertQuery = "INSERT INTO users (name, age, weight, email, file_path) VALUES ('$name', $age, $weight, '$email', '$destination')";
        if ($conn->query($insertQuery) === TRUE) {
            $successMessage .= "<br>User details inserted successfully";
        } else {
            $errorMessage = "Error: " . $insertQuery . "<br>" . $conn->error;
        }
    } else {
        // Error in file upload
        $errorMessage = "Error uploading the file";
    }
}
}


// Close the connection
$conn->close();



// Generate HTML page with the messages
$html = "<html>
<head>
    <title>Form Submission Result</title>
    <link rel='icon' type='image/x-icon' href='../images/favicon.ico'>
    <link rel='stylesheet' type='text/css' href='../css/message.css'>
</head>
<body>
    <h1>Form Submission Result</h1>";

// Display success message
if (isset($successMessage)) {
    $html .= "<div class='error-box'>
    <h2>Success</h2>
    <p>$successMessage</p>
    </div>";

}

// Display error message within a box
if (isset($errorMessage)) {
    $html .= "<div class='error-box'>
        <h2>Error</h2>
        <p>$errorMessage</p>
    </div>";
}

$html .= "</body>
</html>";

// Set appropriate headers and send the HTML page as the response
header("Content-Type: text/html");
echo $html;