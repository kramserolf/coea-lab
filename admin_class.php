<?php
session_start();
ini_set('display_errors', 1);

class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function login()
	{

		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '" . $username . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'password' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			if ($_SESSION['login_type'] != 1) {
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				return 2;
				exit;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}

	function save_user()
	{
		extract($_POST);

		$type = isset($type) ? $type : 1;

		$user = $this->db->query("SELECT * FROM users WHERE id = '$id'")->fetch_assoc();

		// Check if the current password matches the one stored in the database
		if (!empty($password) && md5($current_password) !== $user['password']) {
			return "Current password is incorrect.";
		}

		$chk = $this->db->query("SELECT * FROM users WHERE username = '$username' AND id != '$id'")->num_rows;

		if ($chk > 0) {
			return 2;
		}

		$data = "name = '$name', username = '$username'";

		if (!empty($password)) {
			$data .= ", password = '" . md5($password) . "'";
		}

		$data .= ", type = '$type'";

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users SET " . $data);
		} else {
			$save = $this->db->query("UPDATE users SET " . $data . " WHERE id = " . $id);
		}

		if ($save) {
			return 1;
		} else {
			$error_message = $this->db->error;
			error_log("SQL Error: $error_message");
			return 0;
		}
	}
	function save_borrowers()
	{
		extract($_POST);

		// Check if a file is uploaded
		if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
			$target_dir = "borrower_images/";
			$target_file = $target_dir . basename($_FILES["image"]["name"]);

			// Move the uploaded file to the target directory
			if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
				// If file upload successful, update the $image variable with the file path
				$image = $target_file;
			} else {
				// If file upload failed, return error or handle accordingly
				return "Error uploading image.";
			}
		}

		// Construct the data for database update
		$data = " name = '$name' ";
		if (isset($image)) {
			$data .= ", image = '$image' ";
		}
		$data .= ", id_number = '$id_number' ";
		$data .= ", contact_number = '$contact_number' ";

		// Perform database query
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO borrowers SET " . $data);
		} else {
			$save = $this->db->query("UPDATE borrowers SET " . $data . " WHERE id=" . $id);
		}

		// Return success or error message
		if ($save) {
			return 1;
		} else {
			return "Error saving data to database.";
		}
	}

	function save_inventory()
	{
		extract($_POST);

		// Check if a file is uploaded
		if (isset($_FILES['item_image']['name']) && !empty($_FILES['item_image']['name'])) {
			$target_dir = "equipment_images/";
			$target_file = $target_dir . basename($_FILES["item_image"]["name"]);

			// Move the uploaded file to the target directory
			if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
				// If file upload successful, update the $item_image variable with the file path
				$item_image = $target_file;
			} else {
				// If file upload failed, return error or handle accordingly
				return "Error uploading file.";
			}
		}

		// Construct the data for database update
		$data = " item_name = '$item_name' ";
		if (isset($item_image)) {
			$data .= ", item_image = '$item_image' ";
		}
		$data .= ", description = '$description' ";
		$data .= ", equipment_id = '$equipment_id' ";

		// Perform database query
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO inventory SET " . $data);
		} else {
			$save = $this->db->query("UPDATE inventory SET " . $data . " WHERE id=" . $id);
		}

		// Return success or error message
		if ($save) {
			return 1;
		} else {
			return "Error saving data to database.";
		}
	}

	function borrow_equipment()
	{
		extract($_POST);

		// Set the timezone to Philippines
		date_default_timezone_set('Asia/Manila');

		// Check if the borrower exists
		$borrower_check_query = "SELECT * FROM borrowers WHERE id_number = ?";
		$borrower_statement = $this->db->prepare($borrower_check_query);
		$borrower_statement->bind_param("s", $id_number);
		$borrower_statement->execute();
		$borrower_result = $borrower_statement->get_result();

		if ($borrower_result->num_rows == 0) {
			return "Borrower with ID number '$id_number' does not exist.";
		}

		// Check if equipment is already borrowed
		$borrowed_check_query = "SELECT * FROM borrowed WHERE equipment_id = ? AND returned_date IS NULL";
		$borrowed_statement = $this->db->prepare($borrowed_check_query);
		$borrowed_statement->bind_param("i", $equipment_id);
		$borrowed_statement->execute();
		$borrowed_result = $borrowed_statement->get_result();

		if ($borrowed_result->num_rows > 0) {
			return "Equipment is already borrowed.";
		}

		// Fetch current borrowed date
		$borrowed_timestamp = date("Y-m-d H:i:s");

		// Insert borrowing record with empty returned_date
		$borrow_query = "INSERT INTO borrowed (equipment_id, id_number, borrowed_date) VALUES (?, ?, ?)";
		$borrow_statement = $this->db->prepare($borrow_query);
		$borrow_statement->bind_param("iss", $equipment_id, $id_number, $borrowed_timestamp);
		$borrow_statement->execute();

		if (!$borrow_statement->affected_rows) {
			return "Error borrowing equipment: " . $this->db->error;
		}

		// Update status to zero in the inventory table
		$update_status_query = "UPDATE inventory SET status = 0 WHERE equipment_id = ?";
		$update_status_statement = $this->db->prepare($update_status_query);
		$update_status_statement->bind_param("i", $equipment_id);
		$update_status_statement->execute();

		if ($update_status_statement->affected_rows == 0) {
			return "Error updating equipment status: " . $this->db->error;
		}

		return 1; // Success
	}

	function return_equipment()
	{
		extract($_POST);

		// Perform database query to return the equipment
		$return_query = "UPDATE borrowed SET returned_date = CURRENT_TIMESTAMP() WHERE equipment_id = ? AND returned_date IS NULL";
		$return_statement = $this->db->prepare($return_query);
		$return_statement->bind_param("i", $equipment_id);
		$return_statement->execute();

		if ($return_statement->affected_rows == 0) {
			return "Error returning equipment: " . $this->db->error;
		}

		// Update status to one in the inventory table
		$update_status_query = "UPDATE inventory SET status = 1 WHERE equipment_id = ?";
		$update_status_statement = $this->db->prepare($update_status_query);
		$update_status_statement->bind_param("i", $equipment_id);
		$update_status_statement->execute();

		if ($update_status_statement->affected_rows == 0) {
			return "Error updating equipment status: " . $this->db->error;
		}

		return 1; // Success
	}

	function archive_inventory()
	{
		extract($_POST);
		$archive = $this->db->query("UPDATE inventory SET archived = 1 WHERE id = " . $id);
		if ($archive) {
			return 1;
		}
	}

	function unarchive_inventory()
	{
		extract($_POST);
		$unarchive = $this->db->query("UPDATE inventory SET archived = 0 WHERE id = " . $id);
		if ($unarchive) {
			return 1;
		}
	}

	function archive_borrower()
	{
		extract($_POST);
		$archive = $this->db->query("UPDATE borrowers SET archived = 1 WHERE id = " . $id);
		if ($archive) {
			return 1;
		}
	}

	function unarchive_borrower()
	{
		extract($_POST);
		$unarchive = $this->db->query("UPDATE borrowers SET archived = 0 WHERE id = " . $id);
		if ($unarchive) {
			return 1;
		}
	}
}
