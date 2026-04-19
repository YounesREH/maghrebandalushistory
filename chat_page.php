<?php include "header.php" ?>

<div class="chat-container">

    <div class="chat-box" id="messages"></div>

    <div class="chat-input">
        <input type="text" id="userInput" placeholder="اكتب سؤالك...">
        <button onclick="sendMessage()">ارسال</button>
    </div>

</div>

<?php include "footer.php" ?>
<script src="script.js"></script>