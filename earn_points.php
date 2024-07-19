<?php
// Include the file that establishes the database connection
require_once 'controllerUserData.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item'])) {
    // Retrieve the item value from the form data
    $item = $_POST['item'];
    
    // Determine the points to add based on the selected item
    $pointsToAdd = ($item === 'bottles') ? 10 : 20;

    // Insert the earned points into the database
    $email = $_SESSION['email'];
    $userIdQuery = "SELECT id FROM usertable WHERE email = '$email'";
    $userIdResult = mysqli_query($con, $userIdQuery);
    if ($userIdResult) {
        $userIdRow = mysqli_fetch_assoc($userIdResult);
        $userId = $userIdRow['id'];
        $insertSql = "INSERT INTO points (user_id, item, points) VALUES (?, ?, ?)";
        $insertStmt = $con->prepare($insertSql);
        $insertStmt->bind_param("isi", $userId, $item, $pointsToAdd);
        $insertStmt->execute();
        // Redirect the user back to the reward points page
        header('Location: reward_points.php');
        exit();
    } else {
        // Handle error if user ID cannot be retrieved
        // For example, display an error message or redirect the user to an error page
        echo "Failed to retrieve user ID";
        exit();
    }
} else {
    // Handle invalid or missing form submission
    // For example, display an error message or redirect the user to an error page
    echo "Invalid form submission";
    exit();
}
?>
