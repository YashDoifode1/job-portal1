<?php
// Connect to the database
$host = 'localhost';
$db   = 'chatbot';
$user = 'root';  // Change this based on your MySQL setup
$pass = '';      // Change this based on your MySQL setup
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Fetch user message from AJAX
$user_message = $_POST['message'];

// Here, define some basic bot responses
$bot_reply = "Sorry, I don't understand.";

if (stripos($user_message, 'hello') !== false) {
    $bot_reply = "Hi there! How can I assist you today?";
} elseif (stripos($user_message, 'bye') !== false) {
    $bot_reply = "Goodbye! Have a nice day.";
} elseif (stripos($user_message, 'name') !== false) {
    $bot_reply = "I'm your helpful chatbot!";
}

// Save messages to the database
$stmt = $pdo->prepare("INSERT INTO messages (user_message, bot_reply) VALUES (?, ?)");
$stmt->execute([$user_message, $bot_reply]);

// Return bot reply
echo $bot_reply;
?>
