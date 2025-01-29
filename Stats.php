<?php
// Start the session
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure session variables are set
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['workout_type'])) {
        die("Session variables 'user_id' or 'workout_type' are not set.");
    }

    // Retrieve data from the form
    $user_id = $_SESSION['user_id'];
    $workout_type = $_SESSION['workout_type'];
    $makes = $_POST['makes'];
    $misses = $_POST['misses'];
    $total_shots = $makes + $misses;
    $average = ($total_shots > 0) ? ($makes / $total_shots) * 100 : 0;

    try {
        // Insert the new workout data
        $sql = $conn->prepare("
            INSERT INTO drill_stats (user_id, workout_type, makes, misses, total_shots, average)
            VALUES (:user_id, :workout_type, :makes, :misses, :total_shots, :average)
        ");
	$sql->execute([
            ':user_id' => $user_id,
            ':workout_type' => $workout_type,
            ':makes' => $makes,
            ':misses' => $misses,
            ':total_shots' => $total_shots,
            ':average' => $average,
        ]);

	echo "Data saved successfully!";
    } catch (PDOException $e) {
        die("Database error during insert: " . $e->getMessage());
    }
}

try {
    // Fetch all stats for the current user_id
    $sql = $conn->prepare("SELECT * FROM drill_stats WHERE user_id = :user_id");
    $sql->execute([':user_id' => $_SESSION['user_id']]);
    $results = $sql->fetchAll();
} catch (PDOException $e) {
    die("Database error during fetch: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basketball Stats Tracker</title>
    <style>
	body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

	/* Header Styling */
        .header {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

	.header h1 {
            font-size: 24px;
            margin: 0;
            flex-grow: 1;
            text-align: center;
        }

	.header button {
            background-color: white;
            color: #007bff;
            border: none;
            padding: 8px 15px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

	.header button:hover {
            background-color: #0056b3;
            color: white;
        }

	.container {
            max-width: 800px;
            width: 100%;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

	h1 {
            font-size: 24px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

	form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }

	.form-group {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 400px;
            margin-bottom: 10px;
        }

	input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

	button.submit {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 15px;
        }

	button.submit:hover {
            background-color: #0056b3;
        }

	table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

	th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

	th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Swish Stats</h1>
        <form action="RETURN6.php" method="post" style="margin: 0;">
            <button type="submit">Logout</button>
        </form>
    </div>

    <div class="container">
        <h1>Basketball Stats Tracker</h1>
        <form action="STATS5.php" method="post">
            <div class="form-group">
                <label for="makes">Number of Makes</label>
                <input type="number" id="makes" name="makes" placeholder="Enter the number of successful shots" required>
            </div>
            <div class="form-group">
                <label for="misses">Number of Misses</label>
                <input type="number" id="misses" name="misses" placeholder="Enter the number of missed shots" required>
            </div>
            <button class="submit" type="submit">Submit Stats</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Workout Type</th>
                    <th>Makes</th>
                    <th>Misses</th>
                    <th>Total Shots</th>
                    <th>Accuracy (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['workout_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['makes']); ?></td>
                    <td><?php echo htmlspecialchars($row['misses']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_shots']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($row['average'], 2)); ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

