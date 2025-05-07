<?php
require_once "Database.php";
class NotesModel {
    private $db;
    public function __construct() {
        $this->db = new Database();
        // Debug: Check if database connection is successful
        if (!$this->db->con) {
            error_log("Database connection failed in NotesModel");
        } else {
            error_log("Database connection successful in NotesModel");
        }
    }
    
    public function createNote($title, $content, $category_id) {
        $id = uniqid();
        // Escape inputs to prevent SQL injection
        $title = $this->db->con->real_escape_string($title);
        $content = $this->db->con->real_escape_string($content);
        
        // Handle category_id - check if it exists in categories table first
        if (empty($category_id)) {
            // No category ID provided, don't include it in the query
            $sql = "INSERT INTO notes (id, title, content) VALUES ('$id', '$title', '$content')";
        } else {
            // Verify the category_id exists before trying to insert it
            $category_id = $this->db->con->real_escape_string($category_id);
            $check_sql = "SELECT id FROM categories WHERE id = '$category_id'";
            $result = $this->db->query($check_sql);
            
            if ($result && $result->num_rows > 0) {
                // Category exists, include it in the insert
                $sql = "INSERT INTO notes (id, title, content, category_id) VALUES ('$id', '$title', '$content', '$category_id')";
            } else {
                // Category doesn't exist, insert without category_id
                error_log("Category ID not found: $category_id - Creating note without category");
                $sql = "INSERT INTO notes (id, title, content) VALUES ('$id', '$title', '$content')";
            }
        }
        
        // Debug: Log the SQL query
        error_log("SQL Query: " . $sql);
        
        $result = $this->db->query($sql);
        if (!$result) {
            error_log("Create note failed: " . $this->db->con->error);
            return false;
        }
        return true;
    }
    
    public function getNotes() {
        $sql = "SELECT * FROM notes ORDER BY id DESC";
        $result = $this->db->query($sql);
        
        if (!$result) {
            error_log("Get notes failed: " . $this->db->con->error);
            return [];
        }
        
        $notes = [];
        while ($row = $result->fetch_assoc()) {
            $notes[] = $row;
        }
        return $notes;
    }
    
    public function getNoteById($id) {
        $id = $this->db->con->real_escape_string($id);
        $sql = "SELECT * FROM notes WHERE id='$id'";
        $result = $this->db->query($sql);
        
        if (!$result || $result->num_rows === 0) {
            return null;
        }
        
        return $result->fetch_assoc();
    }
    
    public function updateNote($id, $title, $content, $category_id) {
        $id = $this->db->con->real_escape_string($id);
        $title = $this->db->con->real_escape_string($title);
        $content = $this->db->con->real_escape_string($content);
        
        if (empty($category_id)) {
            $sql = "UPDATE notes SET title='$title', content='$content', category_id=NULL WHERE id='$id'";
        } else {
            $category_id = $this->db->con->real_escape_string($category_id);
            $sql = "UPDATE notes SET title='$title', content='$content', category_id='$category_id' WHERE id='$id'";
        }
        
        if (!$this->db->query($sql)) {
            error_log("Update note failed: " . $this->db->con->error);
            return false;
        }
        return true;
    }
    
    public function deleteNote($id) {
        $id = $this->db->con->real_escape_string($id);
        $sql = "DELETE FROM notes WHERE id='$id'";
        if (!$this->db->query($sql)) {
            error_log("Delete note failed: " . $this->db->con->error);
            return false;
        }
        return true;
    }
}
?>