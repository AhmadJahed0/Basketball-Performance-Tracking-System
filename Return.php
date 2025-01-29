<?php
session_start(); // Start the session

$error = ""; // Initialize the error message

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $servername = "localhost";
    $database = "PROJ04";
    $username = "proj04";
    $password_db = "csc4341";
    $dsn = "mysql:host=$servername;dbname=$database";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
	$conn = new PDO($dsn, $username, $password_db, $options);

        // Check if the email exists in the database
        $qry = $conn->prepare("SELECT * FROM sign_up WHERE email = :email");
        $qry->execute([':email' => $email]);
        $row = $qry->fetch();

        if ($row) {
            // Verify the password
            if (password_verify($password, $row['password'])) {
                // Successful login
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['role'] = $row['role'];

                // Log the login event in the `login` table
                $sql = $conn->prepare("INSERT INTO login (email, login_time) VALUES (:email, NOW())");
                $sql->execute([':email' => $email]);

                // Redirect to the attendance page
                header("Location: ../project1/ATTENDANCE3.php");
                exit();
            } else {
                // Invalid password
                $error = "Invalid email or password. Please try again.";
            }
	} else {
            // Email not found
            $error = "Invalid email or password. Please try again.";
        }
    } catch (PDOException $e) {
        // Log the error to a file for debugging
        error_log($e->getMessage(), 3, 'errors.log');
        $error = 'An error occurred. Please try again later.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Page</title>
    <style>
	body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
	.signin-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
	input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
	input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
	input[type="submit"]:hover {
            background-color: #45a049;
        }
	.error-message {
            color: red;
            margin-top: 10px;
            font-size: 0.9em;
        }
	.forgot-password {
            display: block;
            margin-top: 10px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="signin-container">
        <h2>Sign In</h2>
        <form action="RETURN6.php" method="post">
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="submit" value="Sign In">
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <a href="#" class="forgot-password">Forgot your password?</a>
        </form>
    </div>
</body>
</html>


