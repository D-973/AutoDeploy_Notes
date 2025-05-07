<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Make sure config is included
require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Create Note</title>
    <style>
        body { 
            font-family: 'Comic Sans MS', 'Segoe UI', Tahoma, sans-serif; 
            margin: 20px auto; 
            max-width: 600px; 
            background-color: #fff0f5; /* Light pink background */
            color: #4a4a4a;
        }
        .container {
            background-color: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(238, 130, 238, 0.2);
        }
        h1 {
            color: #ff69b4; /* Hot pink */
            text-align: center;
            margin-bottom: 20px;
        }
        input, textarea, select { 
            width: 100%; 
            margin-bottom: 15px; 
            padding: 10px; 
            box-sizing: border-box; 
            border: 2px solid #ffb6c1; /* Light pink border */
            border-radius: 8px;
            font-family: inherit;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #ff69b4; /* Hot pink border when focused */
            box-shadow: 0 0 5px rgba(255, 105, 180, 0.5);
        }
        button { 
            padding: 12px 25px; 
            background-color: #ff69b4; /* Hot pink button */
            color: white; 
            border: none; 
            border-radius: 25px;
            cursor: pointer; 
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover { 
            background-color: #ff1493; /* Deeper pink on hover */
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
        .success { 
            background-color: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
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
        small {
            display: block; 
            margin-top: 5px; 
            color: #ff69b4;
            font-style: italic;
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
    </style>
</head>
<body>
    <div class="container">
        <h1><3 Create Note <3</h1>
        <a href="index.php" class="back-link"> < Back to notes list</a>
        
        <?php
        // Include directly instead of using the controller
        require_once "NotesModel.php";
        
        // Include a class for categories to get the dropdown options
        class CategoriesModel {
            private $db;
            
            public function __construct() {
                require_once "Database.php";
                $this->db = new Database();
            }
            
            public function getCategories() {
                $sql = "SELECT * FROM categories ORDER BY name ASC";
                $result = $this->db->query($sql);
                
                if (!$result) {
                    error_log("Get categories failed: " . $this->db->con->error);
                    return [];
                }
                
                $categories = [];
                while ($row = $result->fetch_assoc()) {
                    $categories[] = $row;
                }
                return $categories;
            }
        }
        
        $model = new NotesModel();
        $categoriesModel = new CategoriesModel();
        $categories = $categoriesModel->getCategories();
        $error = '';
        
        // Check if the form has been submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form submission
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $category_id = trim($_POST['category_id'] ?? '');
            
            if (empty($title) || empty($content)) {
                $error = "Title and content are required.";
            } else {
                // Try to create the note directly with the model
                $success = $model->createNote($title, $content, $category_id);
                
                if ($success) {
                    // Redirect on success
                    header('Location: index.php');
                    exit;
                } else {
                    $error = "Failed to create note. Check the PHP error log for details.";
                }
            }
        }
        
        // Display error if any
        if (!empty($error)) {
            echo "<div class='message error'>Error: " . htmlspecialchars($error) . "</div>";
        }
        ?>
        
        <!-- Form for creating a note -->
        <form action="create.php" method="POST">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" placeholder="Enter note title" required />
            </div>
            
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea id="content" name="content" placeholder="Enter note content" rows="8" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="category_id">Category:</label>
                <select id="category_id" name="category_id">
                    <option value="">-- Select Category (Optional) --</option>
                    <?php
                    // Display categories in dropdown
                    foreach ($categories as $category) {
                        echo '<option value="' . htmlspecialchars($category['id']) . '">' . 
                             htmlspecialchars($category['name']) . 
                             ' <span style="color:' . htmlspecialchars($category['color']) . '">¦</span>' . 
                             '</option>';
                    }
                    ?>
                </select>
                <small>Select a category for your note, or leave blank if unsure.</small>
            </div>
            
            <div class="submit-btn">
                <button type="submit">Create Note</button>
            </div>
        </form>
    </div>
</body>
</html>