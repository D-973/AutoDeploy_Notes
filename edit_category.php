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
    <title>Edit Category</title>
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
        input, select { 
            width: 100%; 
            margin-bottom: 15px; 
            padding: 10px; 
            box-sizing: border-box; 
            border: 2px solid #ffb6c1; /* Light pink border */
            border-radius: 8px;
            font-family: inherit;
        }
        input:focus, select:focus {
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
        .color-preview {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-block;
            margin-left: 10px;
            vertical-align: middle;
            border: 1px solid #ddd;
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
        .color-field {
            display: flex;
            align-items: center;
        }
        .color-input {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✨ Edit Category ✨</h1>
        <a href="categories.php" class="back-link">← Back to categories</a>
        
        <?php
        // Create a simple model class for categories
        class CategoriesModel {
            private $db;
            
            public function __construct() {
                require_once "Database.php";
                $this->db = new Database();
            }
            
            public function getCategoryById($id) {
                $id = $this->db->con->real_escape_string($id);
                $sql = "SELECT * FROM categories WHERE id='$id'";
                $result = $this->db->query($sql);
                
                if (!$result || $result->num_rows === 0) {
                    return null;
                }
                
                return $result->fetch_assoc();
            }
            
            public function updateCategory($id, $name, $color) {
                $id = $this->db->con->real_escape_string($id);
                $name = $this->db->con->real_escape_string($name);
                $color = $this->db->con->real_escape_string($color);
                
                $sql = "UPDATE categories SET name='$name', color='$color' WHERE id='$id'";
                
                // Debug: Log the SQL query
                error_log("SQL Query: " . $sql);
                
                $result = $this->db->query($sql);
                if (!$result) {
                    error_log("Update category failed: " . $this->db->con->error);
                    return false;
                }
                return true;
            }
        }
        
        $model = new CategoriesModel();
        $error = '';
        $success = '';
        
        // Get the category ID from the URL
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        
        if (empty($id)) {
            $error = "No category ID provided.";
        } else {
            $category = $model->getCategoryById($id);
            
            if (!$category) {
                $error = "Category not found.";
            } else {
                // Check if the form has been submitted
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // Process form submission
                    $name = trim($_POST['name'] ?? '');
                    $color = trim($_POST['color'] ?? '');
                    
                    if (empty($name)) {
                        $error = "Category name is required.";
                    } else {
                        // Try to update the category
                        $updateResult = $model->updateCategory($id, $name, $color);
                        
                        if ($updateResult) {
                            $success = "Category updated successfully!";
                            // Update the category data after successful submission
                            $category['name'] = $name;
                            $category['color'] = $color;
                        } else {
                            $error = "Failed to update category. Check the PHP error log for details.";
                        }
                    }
                }
            }
        }
        
        // Display messages if any
        if (!empty($error)) {
            echo "<div class='message error'>Error: " . htmlspecialchars($error) . "</div>";
            if ($error == "No category ID provided." || $error == "Category not found.") {
                echo "<div class='submit-btn'><a href='categories.php' class='btn'>Back to Categories</a></div>";
                echo "</div></body></html>";
                exit;
            }
        }
        if (!empty($success)) {
            echo "<div class='message success'>" . htmlspecialchars($success) . "</div>";
        }
        
        // Only show the form if we have a valid category
        if (isset($category) && is_array($category)):
        ?>
        
        <!-- Form for editing a category -->
        <form action="edit_category.php?id=<?php echo urlencode($id); ?>" method="POST">
            <div class="form-group">
                <label for="name">Category Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter category name" value="<?php echo htmlspecialchars($category['name']); ?>" required />
                <small>Choose a name that helps organize your notes</small>
            </div>
            
            <div class="form-group">
                <label for="color">Category Color:</label>
                <div class="color-field">
                    <input type="color" id="color" name="color" value="<?php echo htmlspecialchars($category['color']); ?>" class="color-input" />
                    <div id="color-preview" class="color-preview" style="background-color: <?php echo htmlspecialchars($category['color']); ?>"></div>
                </div>
                <small>Select a color to visually identify this category</small>
            </div>
            
            <div class="submit-btn">
                <button type="submit">Update Category</button>
            </div>
        </form>
        <?php endif; ?>
    </div>
    
    <script>
        // Live preview of the selected color
        const colorInput = document.getElementById('color');
        const colorPreview = document.getElementById('color-preview');
        
        if (colorInput && colorPreview) {
            colorInput.addEventListener('input', function() {
                colorPreview.style.backgroundColor = this.value;
            });
        }
    </script>
</body>
</html>