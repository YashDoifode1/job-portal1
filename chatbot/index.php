<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Chatbot</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>

    <!-- Floating chat icon -->
    <div id="chat-icon" onclick="toggleChat()">
        <img src="chat-icon.png" alt="Chat Icon"style="width: 50px; height: 50px;" >
        <!--  -->
    </div>

    <!-- Chatbox -->
    <div class="chatbox" id="chatbox" style="display: none;">
        <!-- Header with website name, logo, and close button -->
        <div class="chatbox-header">
            <img src="logo.png" alt="Logo" class="chatbox-logo">
            <div class="chatbox-info">
                <div class="chatbox-website"> Udaan [] Job portal ]</div>
                <div class="chatbox-email">skidde7@gmail.com</div>
            </div>
            <div class="chatbox-close" onclick="toggleChat()">&#x2715;</div>
        </div>

        <!-- Chat logs (messages) -->
        <div class="chatlogs" id="chatlogs"></div>

        <!-- Predefined command buttons -->
        <div class="chat-widgets">
            <button onclick="triggerCommand('contact')">📞 Contact</button>
            <button onclick="triggerCommand('address')">📍 Address</button>
            <button onclick="triggerCommand('uptime')">⏱ Uptime</button>
            <button onclick="triggerCommand('help')">❓ Help</button>
        </div>

        <!-- Chat input -->
        <div class="chat-input">
            <input type="text" id="userInput" placeholder="Type your message...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
