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
    <title>Categories</title>
    <style>
        body { 
            font-family: 'Comic Sans MS', 'Segoe UI', Tahoma, sans-serif; 
            margin: 20px auto; 
            max-width: 800px; 
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
        table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0; 
            margin-top: 20px; 
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(255, 182, 193, 0.3);
        }
        th, td { 
            padding: 12px 15px; 
            text-align: left; 
            border-bottom: 1px solid #ffb6c1; 
        }
        th { 
            background-color: #ffb6c1; /* Light pink */
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #fff5f7;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover { 
            background-color: #ffddee; 
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
        .color-sample { 
            width: 25px; 
            height: 25px; 
            display: inline-block; 
            border-radius: 50%; 
            border: 1px solid #ccc; 
            vertical-align: middle;
            margin-right: 10px;
        }
        .actions { 
            margin-top: 25px;
            text-align: center; 
        }
        .btn { 
            padding: 10px 20px; 
            background-color: #ff69b4; /* Hot pink button */
            color: white; 
            border: none; 
            border-radius: 25px;
            cursor: pointer; 
            text-decoration: none; 
            display: inline-block;
            font-weight: bold;
            transition: background-color 0.3s;
            margin: 0 5px;
        }
        .btn:hover { 
            background-color: #ff1493; /* Deeper pink on hover */
            text-decoration: none;
        }
        .back-btn {
            background-color: #9370db; /* Medium purple */
        }
        .back-btn:hover {
            background-color: #8a2be2; /* Blue violet */
        }
        .empty-message {
            text-align: center;
            padding: 20px;
            background-color: #ffddee;
            border-radius: 10px;
            margin: 20px 0;
        }
        .back-link {
            display: block;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>? Categories ?</h1>
        <a href="index.php" class="back-link">« Back to notes list</a>
        
        <?php
        // Create a simple model class for categories
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
        
        // Get all categories
        $model = new CategoriesModel();
        $categories = $model->getCategories();
        
        // Display categories
        if (empty($categories)) {
            echo "<div class='empty-message'>No categories found. You need to create categories before assigning them to notes.</div>";
        } else {
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th><th>Color</th><th>Created At</th></tr>";
            
            foreach ($categories as $category) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($category['id']) . "</td>";
                echo "<td>" . htmlspecialchars($category['name']) . "</td>";
                echo "<td><span class='color-sample' style='background-color:" . htmlspecialchars($category['color']) . "'></span> " . htmlspecialchars($category['color']) . "</td>";
                echo "<td>" . htmlspecialchars($category['created_at']) . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
        }
        ?>
        
        <div class="actions">
            <a href="index.php" class="btn back-btn">Back to Notes</a>
            <a href="create.php" class="btn">Create New Note</a>
        </div>
    </div>
</body>
</html>