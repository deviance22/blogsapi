<?php

/**
*
* Retrieves all the comments in the DB, ordered by their ID
*
**/
$app->get('/comments', function() {
	$result = $this->db->query("SELECT * FROM comments ORDER BY comment_id");
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
* Retrieves all the comments in the DB, given a comment_id
*
**/
$app->get('/comments/{comment_id}', function ($request) {
	$comment_id = $request->getAttribute('comment_id');
	$result  = $this->db->query("SELECT * FROM comments WHERE comment_id = $comment_id");
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
 * Inserts one comment given a comment_post_id, comment_user_id and message
 * 
 */

$app->post('/comments', function ($request) {
	$insert_data     = $request->getParsedBody();
	$comment_post_id = $insert_data['comment_post_id'];
	$comment_user_id = $insert_data['comment_user_id'];
	$message         = $insert_data['message'];
	$insert_query    = "INSERT INTO comments (comment_post_id, comment_user_id, message) VALUES (?, ?, ?)";
	$stmt            = $this->db->prepare($insert_query);

	$stmt->bind_param("iis", $comment_post_id, $comment_user_id, $message);
	if ($stmt->execute()) {
		header('Content-Type: application/json');
		echo json_encode(['code' => 200, 'msg' => 'Successfully inserted comment: ' . $message]);
	} else {
		header('Content-Type: application/json');
		echo json_encode(['code' => 500, 'msg' => 'Server error']);
	}
});

/**
 *
 * Update the comment message given an ID.
 * 
 */

$app->put('/comments/{comment_id}', function ($request) {
	$comment_id  = $request->getAttribute('comment_id');
	$update_data = $request->getParsedBody();
	$message     = $update_data['message'];

	$update_query = "UPDATE comments SET message = ? WHERE comment_id = ?";
	$stmt         = $this->db->prepare($update_query);
	
	$stmt->bind_param("si", $message, $comment_id);
	if ($stmt->execute()) {
		header('Content-Type: application/json');
		echo json_encode(['code' => 200, 'msg' => 'Successfully updated comment: ' . $message]);
	} else {
		header('Content-Type: application/json');
		echo json_encode(['code' => 500, 'msg' => 'Server error']);
	}
});

/**
 *
 * Delete the user given an ID.
 * 
 */

$app->delete('/comments/{comment_id}', function ($request) {
	$comment_id     = $request->getAttribute('comment_id');
	$result         = $this->db->query("SELECT * FROM comments WHERE comment_id = $comment_id");
	$deleted_data[] = $result->fetch_assoc();
	$delete_query   = "DELETE FROM comments WHERE comment_id = ?";
	$stmt           = $this->db->prepare($delete_query);
	
	$stmt->bind_param("i", $comment_id);
	if ($stmt->execute()) {
		header('Content-Type: application/json');
		echo json_encode(['code' => 200, 'msg' => 'Successfully deleted message: ' . $deleted_data[0]['message']]);
	} else {
		header('Content-Type: application/json');
		echo json_encode(['code' => 500, 'msg' => 'Server error']);
	}
});