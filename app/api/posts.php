<?php

/**
*
* Retrieves all the posts in the DB, ordered by their ID
*
**/
$app->get('/posts', function() {
	$result = $this->db->query("SELECT * FROM posts ORDER BY post_id");
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
* Retrieves all the posts in the DB, given a post_id
*
**/
$app->get('/posts/{post_id}', function ($request) {
	$post_id = $request->getAttribute('post_id');
	$result  = $this->db->query("SELECT * FROM posts WHERE post_id = $post_id");
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
* Retrieves all the posts in the DB, given a post_id
*
**/
$app->get('/posts/{post_id}/comments', function ($request) {
	$post_id = $request->getAttribute('post_id');
	$result  = $this->db->query("SELECT * FROM comments WHERE comment_post_id = $post_id");
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
 * Inserts one comment given a post_user_id, title and body
 * 
 */

$app->post('/posts', function ($request) {
	$insert_data  = $request->getParsedBody();
	$post_user_id = $insert_data['post_user_id'];
	$title        = $insert_data['title'];
	$body         = $insert_data['body'];
	$insert_query = "INSERT INTO posts (post_user_id, title, body) VALUES (?, ?, ?)";
	$stmt         = $this->db->prepare($insert_query);

	$stmt->bind_param("iss", $post_user_id, $title, $body);
	if ($stmt->execute()) {
		header('Content-Type: application/json');
		echo json_encode(['code' => 200, 'msg' => 'Successfully inserted comment: ' . $title . ' - ' . $body]);
	} else {
		header('Content-Type: application/json');
		echo json_encode(['code' => 500, 'msg' => 'Server error']);
	}
});

/**
 *
 * Update the comment title and body given an ID.
 * 
 */

$app->put('/posts/{post_id}', function ($request) {
	$post_id  = $request->getAttribute('post_id');
	$update_data = $request->getParsedBody();
	$title     = $update_data['title'];
	$body     = $update_data['body'];

	$update_query = "UPDATE posts SET title = ?, body = ? WHERE post_id = ?";
	$stmt         = $this->db->prepare($update_query);
	
	$stmt->bind_param("ssi", $title, $body, $post_id);
	if ($stmt->execute()) {
		header('Content-Type: application/json');
		echo json_encode(['code' => 200, 'msg' => 'Successfully updated comment: ' . $title . ' - ' . $body]);
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

$app->delete('/posts/{post_id}', function ($request) {
	$post_id        = $request->getAttribute('post_id');
	$result         = $this->db->query("SELECT * FROM posts WHERE post_id = $post_id");
	$deleted_data[] = $result->fetch_assoc();

	$delete_query   = "DELETE FROM posts WHERE post_id = ?";
	$stmt           = $this->db->prepare($delete_query);
	
	$stmt->bind_param("i", $post_id);
	if ($stmt->execute()) {
		header('Content-Type: application/json');
		echo json_encode(['code' => 200, 'msg' => 'Successfully deleted message: ' . $deleted_data[0]['title']]);
	} else {
		header('Content-Type: application/json');
		echo json_encode(['code' => 500, 'msg' => 'Server error']);
	}
});