<?php

/**
*
* Retrieves all the users in the DB, ordered by their ID
*
**/
$app->get('/users', function() {
	$result = $this->db->query("SELECT * FROM users ORDER BY user_id");
	while ($row = $result->fetch_assoc()) {
		$data[] = $row;
	}
	if (isset($data) && $data[0] != null) {
		header('Content-Type: application/json');
		echo json_encode(['code' => 200, 'data' => $data]);
	} else {
		header('Content-Type: application/json');
		echo json_encode(['code' => '404', 'msg' => 'No records found']);
	}
});

/**
*
* Retrieves all the users in the DB, given a user_id
*
**/
$app->get('/users/{user_id}', function ($request) {
	$user_id = $request->getAttribute('user_id');
	$result  = $this->db->query("SELECT * FROM users WHERE user_id = $user_id");
	$data[]  = $result->fetch_assoc();

	if (isset($data) && $data[0] != null) {
		header('Content-Type: application/json');
		echo json_encode(['code' => 200, 'data' => $data]);
	} else {
		header('Content-Type: application/json');
		echo json_encode(['code' => '404', 'msg' => 'No records found']);
	}
});

/**
 *
 * Retrieves all the posts made by the user
 * 
 */
$app->get('/users/{user_id}/posts', function ($request) {
	$user_id = $request->getAttribute('user_id');
	$result  = $this->db->query("SELECT * FROM posts WHERE post_user_id = $user_id");
	while ($row = $result->fetch_assoc()) {
		$data[] = $row;
	}
	if (isset($data) && $data[0] != null) {
		header('Content-Type: application/json');
		echo json_encode(['code' => 200, 'data' => $data]);
	} else {
		header('Content-Type: application/json');
		echo json_encode(['code' => '404', 'msg' => 'No records found']);
	}
});

/**
 *
 * Inserts one user given a firstname and lastname
 * 
 */

$app->post('/users', function ($request) {
	$insert_data  = $request->getParsedBody();
	$firstname    = $insert_data['firstname'];
	$lastname     = $insert_data['lastname'];
	$insert_query = "INSERT INTO users (firstname, lastname) VALUES (?, ?)";
	$stmt         = $this->db->prepare($insert_query);

	$stmt->bind_param("ss", $firstname, $lastname);
	if ($stmt->execute()) {
		header('Content-Type: application/json');
		echo json_encode(['code' => 200, 'msg' => 'Successfully inserted user: ' . $firstname . ' ' . $lastname]);
	} else {
		header('Content-Type: application/json');
		echo json_encode(['code' => 500, 'msg' => 'Server error']);
	}
});

/**
 *
 * Update the user given an ID.
 * 
 */

$app->put('/users/{user_id}', function ($request) {
	$update_data  = $request->getParsedBody();
	$user_id      = $request->getAttribute('user_id');
	$user_id = $request->getAttribute('user_id');
	$result  = $this->db->query("SELECT * FROM users WHERE user_id = $user_id");
	$data[]  = $result->fetch_assoc();

	if (isset($data) && $data[0] != null) {
		$firstname    = $update_data['firstname'];
		$lastname     = $update_data['lastname'];
		$update_query = "UPDATE users SET firstname = ?, lastname = ? WHERE user_id = ?";
		$stmt         = $this->db->prepare($update_query);
		
		$stmt->bind_param("ssi", $firstname, $lastname, $user_id);
		if ($stmt->execute()) {
			header('Content-Type: application/json');
			echo json_encode(['code' => 200, 'msg' => 'Successfully updated user: ' . $firstname . ' ' . $lastname]);
		} else {
			header('Content-Type: application/json');
			echo json_encode(['code' => 500, 'msg' => 'Server error']);
		}
	} else {
		header('Content-Type: application/json');
		echo json_encode(['code' => '404', 'msg' => 'User not found']);
	}
});

/**
 *
 * Delete the user given an ID.
 * 
 */

$app->delete('/users/{user_id}', function ($request) {
	$user_id        = $request->getAttribute('user_id');
	$result         = $this->db->query("SELECT * FROM users WHERE user_id = $user_id");
	$deleted_data[] = $result->fetch_assoc();
	if (isset($deleted_data) && $deleted_data[0] != null) {
		$delete_query   = "DELETE FROM users WHERE user_id = ?";
		$stmt           = $this->db->prepare($delete_query);
		
		$stmt->bind_param("i", $user_id);
		if ($stmt->execute()) {
			header('Content-Type: application/json');
			echo json_encode(['code' => 200, 'msg' => 'Successfully deleted user: ' . $deleted_data[0]['firstname'] . ' ' . $deleted_data[0]['lastname']]);
		} else {
			header('Content-Type: application/json');
			echo json_encode(['code' => 500, 'msg' => 'Server error']);
		}
	} else {
		header('Content-Type: application/json');
		echo json_encode(['code' => '404', 'User not found']); 
	}
});