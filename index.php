<?php
	$FirstName = $_POST['FirstName'];
	$LastName = $_POST['LastName'];
	$Email = filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL);
	$Mobile = $_POST['Mobile'];
	$Password = $_POST['Password'];

	if (!empty($FirstName) || !empty($LastName) || !empty($Email) || !empty($Mobile) || !empty($Password)) {
		$host = 'localhost';
		$username = 'root';
		$password = '';
		$db = 'findit';

		$conn = new mysqli($host, $username, $password, $db);
		if ($conn->connect_error) {
			die("Connection failed: " . mysqli_connect_error());
		} else {
			// Prepare the SELECT statement
			$SELECT = "SELECT Email FROM registration WHERE Email = ? AND Password = ? LIMIT 1";
			$stmt = $conn->prepare($SELECT);
			$stmt->bind_param("ss", $Email, $Password);
			$stmt->execute();
			$stmt->bind_result($Email);
			$stmt->store_result();

			// If the email and password already exist in the database, don't allow the user to register again
			if ($stmt->num_rows > 0) {
				echo "<script type='text/javascript'>alert('Email and Password already exist in the database!'); window.location='index.html'</script>";
				$stmt->close();
			} else {
				// Prepare the INSERT statement
				$sql = "INSERT INTO registration (FirstName, LastName, Email, Mobile, Password) VALUES (?, ?, ?, ?, ?)";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("sssis", $FirstName, $LastName, $Email, $Mobile, $Password);

				// Execute the INSERT statement and check for errors
				if ($stmt->execute()) {
					echo "<script type='text/javascript'>alert('Registration Successfull!'); window.location='result.html'</script>";
				} else {
					echo "Error: " . $sql . '<br>' . $conn->error;
				}

				$stmt->close();
			}
		}
		$conn->close();
	}
?>