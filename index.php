<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Notes Application</title>
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
        a { 
            text-decoration: none; 
            color: #ff69b4;
            font-weight: bold;
        }
        a:hover { 
            text-decoration: underline; 
            color: #ff1493;
        }
        .header { 
            margin-bottom: 30px;
            text-align: center;
        }
        .create-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff69b4;
            color: white;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
            margin: 10px 5px;
        }
        .create-btn:hover {
            background-color: #ff1493;
            text-decoration: none;
        }
        .categories-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #9370db; /* Medium purple */
            color: white;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
            margin: 10px 5px;
        }
        .categories-btn:hover {
            background-color: #8a2be2; /* Blue violet */
            text-decoration: none;
        }
        .note {
            border: 2px solid #ffb6c1;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: white;
            transition: transform 0.2s;
        }
        .note:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(255, 105, 180, 0.3);
        }
        .note-title {
            color: #ff69b4;
            margin-top: 0;
            border-bottom: 1px solid #ffb6c1;
            padding-bottom: 8px;
        }
        .note-category {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 12px;
            margin-bottom: 10px;
        }
        .note-actions {
            text-align: right;
            margin-top: 10px;
        }
        .note-actions a {
            margin-left: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1> <3 My Notes <3 </h1>
            <a href="create.php" class="create-btn">+ Create Note</a>
            <a href="categories.php" class="categories-btn">View Categories</a>
        </div>
        <div>
            <?php
            require_once 'NotesController.php';
            $controller = new NotesController();
            
            // Modify the controller's index method to render notes with our new style
            // or implement an alternative rendering method here if needed
            $controller->index();
            ?>
        </div>
    </div>
</body>
</html>