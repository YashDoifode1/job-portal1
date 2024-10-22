<div id="chat-icon" onclick="toggleChat()">
    <img src="chatbot/chat-icon.png" alt="Chat Icon" style="width: 50px; height: 50px;">
</div>

<!-- Chatbox -->
<div class="chatbox" id="chatbox" style="display: none;">
    <div class="chatbox-header">
        <img src="chatbot/logo.png" alt="Logo" class="chatbox-logo">
        <div class="chatbox-info">
            <div class="chatbox-website">Udaan [ Job portal ]</div>
            <div class="chatbox-email">skidde7@gmail.com</div>
        </div>
        <div class="chatbox-close" onclick="toggleChat()">&#x2715;</div>
    </div>
    <div class="chatlogs" id="chatlogs"></div>
    <div class="chat-widgets">
        <button onclick="triggerCommand('contact')">📞 Contact</button>
        <button onclick="triggerCommand('address')">📍 Address</button>
        <button onclick="triggerCommand('uptime')">⏱ Uptime</button>
        <button onclick="triggerCommand('help')">❓ Help</button>
    </div>
    <div class="chat-input">
        <input type="text" id="userInput" placeholder="Type your message...">
        <button onclick="sendMessage()">Send</button>
    </div>
</div>

<script src="chatbot/script.js"></script>