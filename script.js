const toggle = document.querySelector(".menu-toggle");
const nav = document.querySelector(".nav-links");

if(toggle) {
    toggle.addEventListener("click", () => {
        nav.classList.toggle("activate");
    });
}

// تفعيل زر البحث في الهاتف
const searchToggle = document.querySelector(".search-toggle");
const searchBox = document.querySelector(".search-box");

if(searchToggle) {
    searchToggle.addEventListener("click", () => {
        searchBox.classList.toggle("active");
    });
}

// كود الشات — يعمل فقط في صفحة الشات
window.onload = function () {
    let chatBox = document.getElementById("messages");
    if(!chatBox) return;

    let welcome = document.createElement("div");
    welcome.classList.add("message", "bot");
    welcome.innerText = "👋 مرحبا، أنا الوجيه. اسألني عن تاريخ المغرب الإسلامي والأندلس.";
    chatBox.appendChild(welcome);
};

function sendMessage() {
    let input = document.getElementById("userInput");
    if(!input) return;
    let message = input.value.trim();
    if(message === "") return;

    let chatBox = document.getElementById("messages");

    let userMsg = document.createElement("div");
    userMsg.classList.add("message", "user");
    userMsg.innerText = message;
    chatBox.appendChild(userMsg);

    input.value = "";

    let typing = document.createElement("div");
    typing.classList.add("message", "bot");
    typing.innerText = "⌛ يكتب الآن...";
    chatBox.appendChild(typing);

    chatBox.scrollTop = chatBox.scrollHeight;

    fetch("chat.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message: message })
    })
    .then(res => res.json())
    .then(data => {
        typing.remove();
        let botMsg = document.createElement("div");
        botMsg.classList.add("message", "bot");
        botMsg.innerText = data.reply;
        chatBox.appendChild(botMsg);
        chatBox.scrollTop = chatBox.scrollHeight;
    })
    .catch(error => {
        typing.remove();
        let errorMsg = document.createElement("div");
        errorMsg.classList.add("message", "bot");
        errorMsg.innerText = "حدث خطأ ❌";
        chatBox.appendChild(errorMsg);
    });
}