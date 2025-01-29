<?php
// Start the session
session_start();

if (isset($_POST['submit'])) {
    $user_id = $_POST["user_id"];
    $workout_type = $_POST["workout_type"];

    // Validate input
    if (empty($user_id) || empty($workout_type)) {
        die("Please provide both user ID and workout type.");
    }

    // Store data in session variables
    $_SESSION['user_id'] = $user_id;
    $_SESSION['workout_type'] = $workout_type;

    // Redirect to STATS.php
    header("Location: STATS5.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Page</title>
    <style>
	/* General Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

	/* Container */
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
            padding: 20px 30px;
            text-align: center;
        }

	/* Page Title */
        .container h1 {
            font-size: 24px;
            color: #333333;
            margin-bottom: 20px;
        }

	/* Subtitle */
        .container p {
            font-size: 16px;
            color: #666666;
            margin-bottom: 30px;
        }

	/* Form Styling */
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

	/* Labels */
        label {
            font-size: 14px;
            font-weight: bold;
            color: #333333;
            display: block;
            margin-bottom: 5px;
        }

	/* Input Fields */
        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            max-width: 400px;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #dddddd;
            border-radius: 5px;
            outline: none;
        }

	input[type="text"]:focus,
        input[type="date"]:focus,
        select:focus {
            border-color: #007bff;
        }

	/* Submit Button */
        button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

	button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Workout Attendance</h1>
        <p>Please fill out the information below to start your workout.</p>
        <form action="ATTENDANCE3.php" method="post">
            <label for="user_id">User ID</label>
            <input type="text" id="user_id" name="user_id" placeholder="Enter your User ID" required>

            <label for="date">Workout Date</label>
            <input type="date" id="date" name="date" required>

            <label for="workout_type">Workout Type</label>
            <select id="workout_type" name="workout_type" required>
                <option value="" disabled selected>Choose a workout type</option>
                <option value="three-point-shots">Three-Point Shots</option>
                <option value="midrange-shots">Midrange Shots</option>
                <option value="layups">Layups</option>
                <option value="free-throws">Free Throws</option>
                <option value="floaters">Floaters</option>
            </select>

            <button type="submit" name="submit" id="submit">Start Workout</button>
        </form>
    </div>
</body>
</html>

