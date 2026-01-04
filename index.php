<?php
require_once 'db_connect.php';
require_once 'classes.php';

$now = new DateTime();

$user = new User(
    id: 1,
    username: 'bro',
    email: 'bro@example.com',
    password_hash: '123',
    createdAt: $now
);

$photo = new Photo(
    id: 1,
    user_id: 1,
    title: 'My First Photo',
    file_path: 'uploads/test.jpg',  
    createdAt: $now
);

$photo->setUser($user);
$user->addPhoto($photo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhotoSphere Gallery</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background: #f0f0f0; padding: 50px; }
        .photo-card {
            background: white;
            width: 600px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        img { max-width: 100%; border-radius: 10px; }
        h1 { color: #333; }
        .info { margin-top: 15px; color: #555; font-size: 18px; }
    </style>
</head>
<body>
    <h1>Welcome to PhotoSphere!</h1>

    <div class="photo-card">
        <?php if (file_exists($photo->getFilePath())): ?>
            <img src="<?= $photo->getFilePath() ?>" alt="<?= $photo->getTitle() ?>">
            <div class="info">
                <strong><?= $photo->getTitle() ?></strong><br>
                Uploaded by: <strong><?= $photo->getUser()->getUsername() ?></strong><br>
                Success! The photo is now visible! 🚀
            </div>
        <?php else: ?>
            <p style="color: red;"> Image not found! Check if 'uploads/test.jpg' exists.</p>
            <p>Put a real image in the <code>uploads</code> folder and name it <strong>test.jpg</strong></p>
        <?php endif; ?>
    </div>
</body>
</html>