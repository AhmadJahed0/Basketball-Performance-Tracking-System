<?php
if (isset($_POST['submit_info'])) {
    // Collect input data
    $name = $_POST["name"];
    $user_id = $_POST["user_id"];
    $position = $_POST["position"];
    $major = $_POST["major"];
    $height = $_POST["height"];
    $weight = $_POST["weight"];
    $dob = $_POST["dob"];

    // Debugging - Print input data
    echo "Form data to be saved:";
    echo $name . "|";
    echo $user_id . "|";
    echo $position . "|";
    echo $major . "|";
    echo $height . "|";
    echo $weight . "|";
    echo $dob . "|";

    // Database connection
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
        $sql = $conn->prepare("
            INSERT INTO player_info (name, user_id, position, major, height, weight, dob)
            VALUES (:name, :user_id, :position, :major, :height, :weight, :dob)
        ");
	echo "PDO connection object created";

        // Bind and execute
        $data_array = [
            ':name' => $name,
            ':user_id' => $user_id,
            ':position' => $position,
            ':major' => $major,
            ':height' => $height,
            ':weight' => $weight,
            ':dob' => $dob,
        ];

	$sql->execute($data_array);
        echo "Data inserted successfully";

        // Redirect after successful insertion
        header("Location: ../project1/ATTENDANCE3.php");
        exit;
    } catch (PDOException $e) {
        echo 'Error executing query: ' . $e->getMessage();
        // Log errors to a file
        error_log($e->getMessage(), 3, 'errors.log');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Information</title>
    <style>
	body {
            background-color: orange;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
	h1 {
            margin-top: 20px;
        }
	.form-section {
            margin-top: 20px;
        }
	input[type="text"], input[type="number"], input[type="date"] {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #333;
            border-radius: 5px;
        }
	img {
            margin-top: 20px;
            width: 150px;
            height: auto;
        }
	button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
	button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Swish Stats</h1>
    <img src="https://wallpapercave.com/wp/wp5809172.jpg" alt="Basketball">
    <div class="form-section">
        <form action="PROFILE2.php" method="post">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" placeholder="Enter your name" required><br>

            <label for="user_id">Wesleyan ID:</label><br>
            <input type="text" id="user_id" name="user_id" placeholder="Enter your student ID" required><br>

            <label for="position">Position Played:</label><br>
            <input type="text" id="position" name="position" placeholder="Enter your position" required><br>

            <label for="major">Major of Study:</label><br>
            <input type="text" id="major" name="major" placeholder="Enter your major" required><br>

            <label for="height">Height:</label><br>
            <input type="text" id="height" name="height" placeholder="Enter your height" required><br>

            <label for="weight">Weight:</label><br>
            <input type="text" id="weight" name="weight" placeholder="Enter your weight" required><br>

            <label for="dob">DOB:</label><br>
            <input type="text" id="dob" name="dob" placeholder="YYYY-MM-DD" required><br>

            <button type="submit" name="submit_info">Submit</button>
        </form>
    </div>
</body>
</html>

