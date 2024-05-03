<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if ($action == 'login') {
	$login = $crud->login();
	if ($login)
		echo $login;
}
if ($action == 'logout') {
	$logout = $crud->logout();
	if ($logout)
		echo $logout;
}

if ($action == 'save_user') {
	$save = $crud->save_user();
	if ($save)
		echo $save;
}
if ($action == "save_inventory") {
	$save = $crud->save_inventory();
	if ($save) {
		echo $save;
	}
}
if ($action == "save_borrowers") {
	$save = $crud->save_borrowers();
	if ($save) {
		echo $save;
	}
}
if ($action == "borrow_equipment") {
	$save = $crud->borrow_equipment();
	if ($save) {
		echo $save;
	}
}
if ($action == "return_equipment") {
	$save = $crud->return_equipment();
	if ($save) {
		echo $save;
	}
}
if ($action == "archive_inventory") {
	$save = $crud->archive_inventory();
	if ($save) {
		echo $save;
	}
}
if ($action == "unarchive_inventory") {
	$save = $crud->unarchive_inventory();
	if ($save) {
		echo $save;
	}
}
if ($action == "archive_borrower") {
	$save = $crud->archive_borrower();
	if ($save) {
		echo $save;
	}
}
if ($action == "unarchive_borrower") {
	$save = $crud->unarchive_borrower();
	if ($save) {
		echo $save;
	}
}

ob_end_flush();
?>