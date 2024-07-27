<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .containers {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.11);
            max-width: 500px;
            width: 100%;
            margin-top: 100px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }
        input, textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        textarea {
            resize: vertical;
        }
        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            width: 100%;
        }
        button:hover {
            background-color: #218838;
        }
        #response {
            margin-top: 20px;
            text-align: center;
            color: #28a745;
        }
    </style>
    <!-- Favicons -->
  <link href="assets/img/clients/Capture.PNG" rel="icon">
  <link href="assets/img/clients/Capture.PNG" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

    <!-- cdn for awesome fonts icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

     <!-- Google Fonts -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css" crossorigin="anonymous" />
</head>
<body>
<header id="header" style="background-color:green";   class="fixed-top ">
    <div class="container d-flex align-items-center" style="background-color:green";>

      <h1 class="logo me-auto"><a href="index.html">Green Cycle Rewards</a></h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.html" class="logo me-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a> -->

      <nav id="navbar" style="background-color:green"; class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="index.html"><span class="fa fa-home"> Home </span></a></li>
          <li><a class="nav-link scrollto" href="phpGmailSMTP/trash.php"><span class="fa fa-trash"> Feedback</span></a></li>
          <li><a class="nav-link scrollto"  href="welcome.php"><span class="fa fa-edit"> Preview Feedback </span></a></li>
          <li><a class="nav-link scrollto" href="reward_points.php"><span class="fa fa-credit-card"> Earn Points</a></li>
          <li><a class="nav-link scrollto" href="redeem_points.php"><span class="fa fa-credit-card"> Redeem Points</a></li>
          <li><a class="nav-link scrollto" href="contact.php"><span class="fa fa-envelope-open"> Contact Us</span></a></li>
          <li><a class="nav-link scrollto" href="logout-user.php"><span class="fas fa-sign-out-alt">Logout</span></a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->
    <div class="containers">
    <h2>Contact Us</h2>
        <form id="contactForm" method="post">
            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="fname" required>
            
            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="lname" required>
            
            <label for="contactEmail">Email:</label>
            <input type="email" id="contactEmail" name="contactEmail" required>
            
            <label for="contactPhone">Phone:</label>
            <input type="text" id="contactPhone" name="contactPhone" required>
            
            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" required></textarea>
            
            <button type="submit" name="submit">Submit</button>
        </form>
        <div id="response">
            <?php
            if (isset($_POST['submit'])) {
                $fname = htmlspecialchars($_POST['fname']);
                $lname = htmlspecialchars($_POST['lname']);
                $contactEmail = htmlspecialchars($_POST['contactEmail']);
                $contactPhone = htmlspecialchars($_POST['contactPhone']);
                $comment = htmlspecialchars($_POST['comment']);
                
                // Database connection
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "wms";
                
                $conn = new mysqli($servername, $username, $password, $dbname);
                
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                
                $sql = "INSERT INTO contact (fname, lname, contactEmail, contactPhone, comment) VALUES ('$fname', '$lname', '$contactEmail', '$contactPhone', '$comment')";
                
                if ($conn->query($sql) === TRUE) {
                    echo "Message sent successfully!";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
                
                $conn->close();
            }
            ?>
        </div>
    </div>
    <script>
        document.getElementById('contactForm').addEventListener('submit', function(event) {
            if (document.querySelector('#response').innerHTML !== '') {
                document.querySelector('#response').innerHTML = '';
            }
        });
    </script>
</body>
</html>
