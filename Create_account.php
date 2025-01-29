<?php
if (isset($_POST['create_account'])) {
    $role = $_POST["role"];
    $email = $_POST["email"];
    $passwordDB = $_POST["password"];
    $user_id = $_POST["user_id"];

    // Hash the password
    $hashed_password = password_hash($passwordDB, PASSWORD_DEFAULT);



    echo "Form data to be saved:";
    echo $email . "|";
    echo $passwordDB . "|";
    echo $role . "|";
    echo $user_id . "|";

    $servername = "localhost";
    $database = "PROJ04";
    $username = "proj04";
    $password = "csc4341";
    $dsn = "mysql:host=$servername;dbname=$database";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
	$conn = new PDO($dsn, $username, $password, $options);
        $sql = $conn->prepare("INSERT INTO sign_up (user_id, email, password, role) VALUES (:user_id, :email, :password, :role)");

        echo "PDO connection object created";
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }

    $data_array = array(':user_id' => $user_id, ':email' => $email, ':password' => $hashed_password, ':role' => $role);

    print_r($data_array);
    try {
	$sql->execute($data_array);
        echo "Data inserted successfully";
    } catch (PDOException $e) {
        echo 'Error executing query: ' . $e->getMessage();
    }
    header("Location: ../project1/PROFILE2.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account</title>
    <style>
	body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

	.container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
            text-align: center;
        }

	h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333333;
        }

	p {
            font-size: 14px;
            color: #666666;
            margin-bottom: 20px;
        }

	form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

	label {
            font-size: 14px;
            color: #333333;
            margin-bottom: 5px;
            text-align: left;
        }

	select, input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: #f9fafb;
        }

	select:focus, input:focus {
            border-color: #6c63ff;
            outline: none;
            box-shadow: 0 0 5px rgba(108, 99, 255, 0.3);
        }

	button {
            background-color: #6c63ff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

	button:hover {
            background-color: #4a45a3;
        }

	.footer {
            margin-top: 20px;
            font-size: 12px;
            color: #999999;
        }

	.footer a {
            color: #6c63ff;
            text-decoration: none;
        }

	.footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create an Account</h1>
        <p>Let's get started by filling out the form below.</p>
        <form action="/~ajahed/csc4341/project1/CREATEACC.php" method="post">
            <label for="role">Select your role:</label>
            <select id="role" name="role" required>
                <option value="" disabled selected>Select your role</option>
                <option value="Coach">Coach</option>
                <option value="Player">Player</option>
            </select>

            <label for="user_id">ID NUM</label>
            <input type="text" id="user_id" name="user_id" placeholder="Enter your ID number" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>

            <button type="submit" name="create_account">Create Account</button>
        </form>
        <div class="footer">
            <p>Already have an account? <a href="/~ajahed/csc4341/project1/RETURN6.php">Sign In here</a></p>
        </div>
    </div>
</body>
</html>

