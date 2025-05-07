<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "NotesModel.php";

class NotesController {
    private $model;
    
    public function __construct() {
        $this->model = new NotesModel();
    }
    
    public function index() {
        // Get all notes
        $notes = $this->model->getNotes();
        
        // Get categories to display category names
        require_once 'Database.php';
        $db = new Database();
        $categoriesQuery = "SELECT id, name, color FROM categories";
        $categoryResult = $db->query($categoriesQuery);
        $categories = [];
        
        if ($categoryResult) {
            while ($row = $categoryResult->fetch_assoc()) {
                $categories[$row['id']] = $row;
            }
        }
        
        // Display notes with cute styling
        if (empty($notes)) {
            echo "<div style='text-align: center; padding: 30px; background-color: #ffddee; border-radius: 10px; margin: 20px 0;'>";
            echo "<p style='font-size: 18px; margin-bottom: 20px;'>You don't have any notes yet!</p>";
            echo "<p>Click the '+ Create Note' button to get started.</p>";
            echo "</div>";
        } else {
            foreach ($notes as $note) {
                echo "<div class='note'>";
                echo "<h3 class='note-title'>" . htmlspecialchars($note['title']) . "</h3>";
                
                // Display category if assigned
                if (!empty($note['category_id']) && isset($categories[$note['category_id']])) {
                    $category = $categories[$note['category_id']];
                    echo "<div class='note-category' style='background-color: " . htmlspecialchars($category['color']) . "; color: white;'>";
                    echo htmlspecialchars($category['name']);
                    echo "</div>";
                }
                
                // Display note content
                echo "<div style='margin: 10px 0;'>" . nl2br(htmlspecialchars($note['content'])) . "</div>";
                
                // Add created_at date if available
                if (isset($note['created_at'])) {
                    echo "<div style='font-size: 12px; color: #888; margin-top: 5px;'>";
                    echo "Created: " . htmlspecialchars($note['created_at']);
                    echo "</div>";
                }
                
                // Add actions for edit, delete
                echo "<div class='note-actions'>";
                echo "<a href='edit.php?id=" . urlencode($note['id']) . "'>Edit</a> | ";
                echo "<a href='delete.php?id=" . urlencode($note['id']) . "' onclick=\"return confirm('Are you sure you want to delete this note?');\">Delete</a>";
                echo "</div>";
                
                echo "</div>";
            }
        }
    }
    
    public function create() {
        // Handle note creation
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $category_id = trim($_POST['category_id'] ?? '');
            
            if ($title === '' || $content === '') {
                return ['error' => 'Title and content are required.'];
            }
            
            $success = $this->model->createNote($title, $content, $category_id);
            if (!$success) {
                return ['error' => 'Failed to create note.'];
            }
            
            header('Location: index.php');
            exit;
        }
        
        // If not POST request, return empty array (no action needed)
        return [];
    }
    
    public function edit($id) {
        // Get categories for dropdown
        require_once 'Database.php';
        $db = new Database();
        $categoriesQuery = "SELECT id, name, color FROM categories ORDER BY name ASC";
        $categoryResult = $db->query($categoriesQuery);
        $categories = [];
        
        if ($categoryResult) {
            while ($row = $categoryResult->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        
        // Handle note editing
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $category_id = trim($_POST['category_id'] ?? '');
            
            if ($title === '' || $content === '') {
                echo "<div class='message error'>Title and content are required.</div>";
                return false;
            }
            
            $success = $this->model->updateNote($id, $title, $content, $category_id);
            if (!$success) {
                echo "<div class='message error'>Failed to update note.</div>";
                return false;
            }
            
            header('Location: index.php');
            exit;
        } else {
            $note = $this->model->getNoteById($id);
            if (!$note) {
                echo "<div class='message error'>Note not found.</div>";
                return;
            }
            
            // Add CSS for styling the edit form
            echo '<style>
                body { 
                    font-family: "Comic Sans MS", "Segoe UI", Tahoma, sans-serif; 
                    margin: 20px auto; 
                    max-width: 600px; 
                    background-color: #fff0f5; 
                    color: #4a4a4a;
                }
                .container {
                    background-color: white;
                    padding: 25px;
                    border-radius: 15px;
                    box-shadow: 0 4px 10px rgba(238, 130, 238, 0.2);
                }
                h1 {
                    color: #ff69b4;
                    text-align: center;
                    margin-bottom: 20px;
                }
                input, textarea, select { 
                    width: 100%; 
                    margin-bottom: 15px; 
                    padding: 10px; 
                    box-sizing: border-box; 
                    border: 2px solid #ffb6c1;
                    border-radius: 8px;
                    font-family: inherit;
                }
                input:focus, textarea:focus, select:focus {
                    outline: none;
                    border-color: #ff69b4;
                    box-shadow: 0 0 5px rgba(255, 105, 180, 0.5);
                }
                button { 
                    padding: 12px 25px; 
                    background-color: #ff69b4;
                    color: white; 
                    border: none; 
                    border-radius: 25px;
                    cursor: pointer; 
                    font-weight: bold;
                    font-size: 16px;
                    transition: background-color 0.3s;
                }
                button:hover { 
                    background-color: #ff1493;
                }
                a { 
                    text-decoration: none; 
                    color: #ff69b4; 
                    font-weight: bold;
                }
                a:hover { 
                    text-decoration: underline; 
                    color: #ff1493;
                }
                .message { 
                    padding: 12px; 
                    margin-bottom: 20px; 
                    border-radius: 8px; 
                }
                .error { 
                    background-color: #ffddee; 
                    color: #ff1493; 
                    border: 1px solid #ffb6c1; 
                }
                .form-group {
                    margin-top: 15px;
                }
                label {
                    display: block;
                    margin-bottom: 8px;
                    font-weight: bold;
                    color: #ff69b4;
                }
                .back-link {
                    display: block;
                    margin-bottom: 20px;
                    text-align: center;
                }
                .submit-btn {
                    text-align: center;
                    margin-top: 25px;
                }
            </style>';
            
            // Display edit form with improved styling
            echo '<div class="container">';
            echo '<h1>? Edit Note ?</h1>';
            echo '<a href="index.php" class="back-link">« Back to notes list</a>';
            
            echo '<form action="edit.php?id=' . htmlspecialchars(urlencode($id)) . '" method="POST">';
            
            echo '<div class="form-group">';
            echo '<label for="title">Title:</label>';
            echo '<input type="text" id="title" name="title" value="' . htmlspecialchars($note['title']) . '" required>';
            echo '</div>';
            
            echo '<div class="form-group">';
            echo '<label for="content">Content:</label>';
            echo '<textarea id="content" name="content" rows="8" required>' . htmlspecialchars($note['content']) . '</textarea>';
            echo '</div>';
            
            echo '<div class="form-group">';
            echo '<label for="category_id">Category:</label>';
            echo '<select id="category_id" name="category_id">';
            echo '<option value="">-- Select Category (Optional) --</option>';
            
            // Display categories in dropdown
            foreach ($categories as $category) {
                $selected = ($category['id'] == $note['category_id']) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($category['id']) . '" ' . $selected . '>' . 
                     htmlspecialchars($category['name']) . 
                     ' <span style="color:' . htmlspecialchars($category['color']) . '">¦</span>' . 
                     '</option>';
            }
            
            echo '</select>';
            echo '</div>';
            
            echo '<div class="submit-btn">';
            echo '<button type="submit">Update Note ?</button>';
            echo '</div>';
            
            echo '</form>';
            echo '</div>';
        }
    }
    
    public function delete($id) {
        $success = $this->model->deleteNote($id);
        if (!$success) {
            echo "<div style='padding: 12px; margin: 20px 0; border-radius: 8px; background-color: #ffddee; color: #ff1493; border: 1px solid #ffb6c1;'>";
            echo "Failed to delete note.";
            echo "</div>";
            return;
        }
        header('Location: index.php');
        exit;
    }
}
?>