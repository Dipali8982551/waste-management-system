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

        // Handle points redemption
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['redeem-points-button'])) {
            $redeemPoints = intval($_POST['redeem_points']);
            if ($redeemPoints <= $totalPoints) {
                // Deduct points from points table
                $updateUserPointsQuery = "
                    UPDATE points
                    SET total_points = total_points - $redeemPoints
                    WHERE user_id = $userId
                ";
                mysqli_query($con, $updateUserPointsQuery);

                // Insert redemption record into redemption_history table
                $insertRedemptionHistoryQuery = "
                    INSERT INTO redemption_history (user_id, points_redeemed, redemption_date)
                    VALUES ($userId, $redeemPoints, Now())
                ";
                mysqli_query($con, $insertRedemptionHistoryQuery);

                // Refresh total points
                $totalPoints -= $redeemPoints;

                // Success message
                $message = "Successfully redeemed $redeemPoints points!";
            } else {
                // Error message for insufficient points
                $message = "You do not have enough points to redeem!";
            }
        }

        // Fetch recent redemptions
        $recentRedemptionsQuery = "SELECT points_redeemed, redemption_date FROM redemption_history WHERE user_id = $userId ORDER BY redemption_date DESC LIMIT 5";
        $recentRedemptionsResult = mysqli_query($con, $recentRedemptionsQuery);

        // Fetch user name (if needed for the profile section)
        $userNameQuery = "SELECT name FROM usertable WHERE id = $userId";
        $userNameResult = mysqli_query($con, $userNameQuery);
        $userName = $userNameResult ? mysqli_fetch_assoc($userNameResult)['name'] : 'User';
    } else {
        // Handle error if user ID cannot be retrieved
        echo "Failed to retrieve user ID";
        exit();
    }
} else {
    header('Location: login-user.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redeem Points</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #4CAF50;
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
        .hero-section {
            color: white;
            padding: 40px 0;
            text-align: center;
        }
        .hero-section h1 {
            font-size: 3em;
            margin: 0;
        }
        .hero-section p {
            font-size: 1.5em;
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
        .profile-section {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-section img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            margin-right: 20px;
        }
        .profile-section h2 {
            margin: 0;
            font-size: 24px;
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
        .form-container input, .form-container button {
            width: 96%;
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
        .recent-redemptions {
            margin-bottom: 20px;
        }
        .recent-redemptions h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }
        .recent-redemptions ul {
            list-style-type: none;
            padding: 0;
        }
        .recent-redemptions li {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .faq-section {
            margin-bottom: 20px;
        }
        .faq-section h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }
        .faq-section p {
            margin-bottom: 10px;
        }
        .promotional-banner {
            background-color: #ffeb3b;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Your Redeem Points Dashboard</h1>
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
        <div class="profile-section">
            <img src="https://t4.ftcdn.net/jpg/01/97/15/87/360_F_197158744_1NBB1dEAHV2j9xETSUClYqZo7SEadToU.jpg" alt="Profile Picture">
            <h2>Hello, <?php echo htmlspecialchars($userName); ?>!</h2>
        </div>
        <div class="points-box">
            <h2>Your Reward Points: <?php echo isset($totalPoints) ? $totalPoints : '0'; ?></h2>
            <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
        </div>
        <div class="form-container">
            <form action="redeem_points.php" method="post">
                <label for="redeem-points">Enter Points to Redeem:</label>
                <input type="number" id="redeem-points" name="redeem_points" min="1" required>
                <button type="submit" name="redeem-points-button">Redeem Points</button>
            </form>
        </div>
        <div class="recent-redemptions">
            <h3>Recent Redemptions</h3>
            <ul>
                <?php
                if ($recentRedemptionsResult) {
                    while ($row = mysqli_fetch_assoc($recentRedemptionsResult)) {
                        echo "<li>{$row['points_redeemed']} points on " . date('F j, Y, g:i a', strtotime($row['redemption_date'])) . "</li>";
                    }
                } else {
                    echo "<li>No recent redemptions.</li>";
                }
                ?>
            </ul>
        </div>
        <div class="faq-section">
            <h3>How Does It Work?</h3>
            <p>Redeeming points is easy! Simply enter the number of points you want to redeem, and we’ll handle the rest. Your points will be deducted, and you’ll be notified of the successful redemption.</p>
            <h3>FAQs</h3>
            <p><strong>Q:</strong> Can I redeem points for any product?</p>
            <p><strong>A:</strong> Yes, you can redeem points for any product listed in our rewards catalog.</p>
            <p><strong>Q:</strong> What happens if I don't have enough points?</p>
            <p><strong>A:</strong> You will receive an error message indicating insufficient points.</p>
        </div>
        <div class="promotional-banner">
            <p>Special Offer: Redeem 100 points and get an extra 20% discount on your next purchase!</p>
        </div>
    </div>
</body>
</html>
