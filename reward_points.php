<?php
// Include the file that establishes the database connection
require_once 'controllerUserData.php';

// Fetch user ID
$email = $_SESSION['email'];
$password = $_SESSION['password'];

if ($email != false && $password != false) {
    $userIdQuery = "SELECT id FROM usertable WHERE email = '$email'";
    $userIdResult = mysqli_query($con, $userIdQuery);

    if ($userIdResult) {
        $userIdRow = mysqli_fetch_assoc($userIdResult);
        $userId = $userIdRow['id'];

        // Fetch total points from points table
        $totalPointsQuery = "SELECT total_points FROM points WHERE user_id = $userId";
        $totalPointsResult = mysqli_query($con, $totalPointsQuery);

        if ($totalPointsResult && mysqli_num_rows($totalPointsResult) > 0) {
            $totalPointsRow = mysqli_fetch_assoc($totalPointsResult);
            $totalPoints = $totalPointsRow['total_points'];
        } else {
            // Handle case where user has no points record
            $totalPoints = 0;
        }
    } else {
        // Handle error if user ID cannot be retrieved
        echo "Failed to retrieve user ID";
        exit();
    }
} else {
    header('Location: login-user.php');
    exit();
}

// Handle points earning
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['earn-points-button'])) {
    $item = $_POST['item'];
    $points = 0;

    // Determine points based on item
    if ($item == 'bottles') {
        $points = 10;
    } elseif ($item == 'plastics') {
        $points = 20;
    }

    // Update points table
    $updateUserPointsQuery = "
        INSERT INTO points (user_id, total_points)
        VALUES ($userId, $points)
        ON DUPLICATE KEY UPDATE total_points = total_points + VALUES(total_points)
    ";
    mysqli_query($con, $updateUserPointsQuery);

    // Refresh total points
    $totalPoints += $points;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reward Points</title>
</head>
<body>
    <div class="header">
        <h1>Welcome to Your Reward Points Dashboard</h1>
    </div>
    <div class="navbar">
    <a href="index.html"><span class="fa fa-home"> Home </span></a>
        <a href="reward_points.php">Reward Points</a>
        <a href="redeem_points.php">Redeem Points</a>
        <a href="welcome.php"><span class="fa fa-edit"> Preview Complain </span></a>
        <a href="contact.php"><span class="fa fa-edit"> Contact Us </span></a>
        <a href="logout-user.php">Logout</a>
    </div>
    <div class="container">
        <div class="points-box">
            <h2>Your Reward Points: <?php echo isset($totalPoints) ? $totalPoints : '0'; ?></h2>
        </div>
        <div class="form-container">
            <form action="reward_points.php" method="post">
                <label for="item-select">Select Item to Earn Points:</label>
                <select id="item-select" name="item">
                    <option value="bottles">Bottles - 10 Points</option>
                    <option value="plastics">Plastics - 20 Points</option>
                </select>
                <button type="submit" name="earn-points-button">Earn Points</button>
            </form>
        </div>
        <div class="info-section">
            <h3>How to Earn Points</h3>
            <p>Earn points by recycling items. Each type of item gives you different points. Select an item from the list above and click 'Earn Points' to add points to your account.</p>
            <p>Points can be redeemed for rewards such as discounts, gift cards, and more. Check back often for new ways to earn and redeem points!</p>
        </div>
    </div>
</body>
</html>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
    }
    .header {
        background-color: #4CAF50;
        color: white;
        padding: 15px 0;
        text-align: center;
    }
    .navbar {
        display: flex;
        justify-content: center;
        background-color: #333;
    }
    .navbar a {
        color: white;
        padding: 14px 20px;
        text-decoration: none;
        text-align: center;
    }
    .navbar a:hover {
        background-color: #ddd;
        color: black;
    }
    .container {
        width: 100%;
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .points-box {
        background-color: #e8f5e9;
        border: 1px solid #4CAF50;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
    }
    .points-box h2 {
        margin: 0;
        font-size: 24px;
        color: #4CAF50;
    }
    .form-container {
        margin-bottom: 20px;
    }
    .form-container label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
    }
    .form-container select, .form-container button {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .form-container button {
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }
    .form-container button:hover {
        background-color: #45a049;
    }
    .info-section {
        margin-top: 20px;
    }
    .info-section h3 {
        color: #333;
        margin-bottom: 10px;
    }
    .info-section p {
        color: #666;
        line-height: 1.6;
    }
</style>
