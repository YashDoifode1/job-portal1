function sendMessage() {
    var userInput = document.getElementById("userInput").value;

    if (userInput === "") {
        alert("Please enter a message.");
        return;
    }

    // Display user message
    var chatlogs = document.getElementById("chatlogs");
    var userMessage = `<div class="message user"><strong>You:</strong> ${userInput}</div>`;
    chatlogs.innerHTML += userMessage;

    // Clear input field
    document.getElementById("userInput").value = "";

    // Send user message to the server using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "chatbot.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Get the bot's reply and display it
            var botReply = `<div class="message bot"><strong>Bot:</strong> ${xhr.responseText}</div>`;
            chatlogs.innerHTML += botReply;

            // Scroll to the bottom
            chatlogs.scrollTop = chatlogs.scrollHeight;
        }
    };
    xhr.send("message=" + userInput);
}

// Toggle chat visibility
// Toggle chat visibility
function toggleChat() {
    var chatbox = document.getElementById("chatbox");

    // Toggle the visibility of the chatbox
    if (chatbox.style.display === "none" || chatbox.style.display === "") {
        chatbox.style.display = "block";
    } else {
        chatbox.style.display = "none";
    }
}

// Trigger predefined commands when button is clicked
function triggerCommand(command) {
    var predefinedResponses = {
        "contact": "📞 Contact us at: skidde7@@gmail.com ",
        "address": "📍 Our address is:  Riaan Tower ,sadar, Nagpur 440045 Maharashtra",
        "uptime": "⏱ Mon to Sat 8:00 Pm to 10 Pm",
        "help": "❓ Available commands: contact, address, uptime, help"
    };

    // Display predefined response
    if (predefinedResponses[command]) {
        displayMessage(command, 'user');
        displayMessage(predefinedResponses[command], 'bot');
    } else {
        displayMessage("Command not recognized", 'bot');
    }
}

// Function to handle user input
function sendMessage() {
    var userInput = document.getElementById("userInput").value.trim();
    if (userInput === "") return; // Ignore empty input

    // Display user message
    displayMessage(userInput, 'user');

    // Handle commands typed by user
    triggerCommand(userInput.toLowerCase());

    // Clear input field
    document.getElementById("userInput").value = "";
}

// Function to display messages in chatbox
// function displayMessage(message, sender) {
//     var chatlogs = document.getElementById("chatlogs");
//     var messageElement = document.createElement("div");

//     // Add message content and sender styling
//     messageElement.classList.add("message", sender);
//     messageElement.textContent = message;

//     // Append message to chat logs
//     chatlogs.appendChild(messageElement);
//     chatlogs.scrollTop = chatlogs.scrollHeight;
// }

// Function to display messages in chatbox
function displayMessage(message, sender) {
    var chatlogs = document.getElementById("chatlogs");
    var messageElement = document.createElement("div");

    // Add message content and sender styling
    messageElement.classList.add("message", sender);
    messageElement.textContent = message;

    // Append message to chat logs
    chatlogs.appendChild(messageElement);
    
    // Scroll to the bottom of the chat logs after adding new message
    chatlogs.scrollTop = chatlogs.scrollHeight;
}
// Function to display messages in chatbox
function displayMessage(message, sender) {
    var chatlogs = document.getElementById("chatlogs");
    var messageElement = document.createElement("div");

    // Add message content and sender styling (user or bot)
    messageElement.classList.add("message", sender);
    messageElement.textContent = message;

    // Append message to chat logs
    chatlogs.appendChild(messageElement);
    chatlogs.scrollTop = chatlogs.scrollHeight; // Scroll to bottom
}
