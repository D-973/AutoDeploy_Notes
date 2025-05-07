<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Make sure config is included
require_once "config.php";
require_once "NotesController.php";

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect to index if no ID is specified
    header('Location: index.php');
    exit;
}

// Get the note ID from URL
$id = $_GET['id'];

// Initialize the controller
$controller = new NotesController();

// Call the delete method with the ID
$controller->delete($id);

// If execution reaches here, something went wrong with the deletion
// Display error message and link back to index
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Delete Note</title>
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
            text-align: center;
        }
        h1 {
            color: #ff69b4; /* Hot pink */
            text-align: center;
            margin-bottom: 20px;
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
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff69b4;
            color: white;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #ff1493;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Something Went Wrong</h1>
        <p>We couldn't delete your note. Please try again.</p>
        <a href="index.php" class="btn">Back to Notes</a>
    </div>
</body>
</html>