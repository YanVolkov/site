(function() {
    const fonts = ["cursive", "sans-serif", "serif", "momspace"];
    let captchaValue = "";

    function generateCaptcha() {
        let value = btoa(Math.random() * 1000000000);
        value = value.substring(0, 5 + Math.random() * 5);
        captchaValue = value;
    }

    function setCaptcha() {
        let html = captchaValue.split("").map((char) => {
            const rotate = -20 + Math.trunc(Math.random() * 30);
            const font = Math.trunc(Math.random() * fonts.length);
            return `<span style="transform:rotate(${rotate}deg); font-family:${fonts[font]}">${char}</span>`;
        }).join("");
        document.querySelector(".login-form .captcha .preview").innerHTML = html;
    }

    function initCaptcha() {
        const refreshButton = document.querySelector(".captcha-refresh");
        const refreshIcon = refreshButton.querySelector(".fa-refresh");
        
        refreshButton.addEventListener("click", function() {
            // Генерируем новую капчу и устанавливаем её
            generateCaptcha();
            setCaptcha();

            // Удаляем класс 'rotating', если он есть, для перезапуска анимации
            refreshIcon.classList.remove('rotating');

            // Используем requestAnimationFrame для перезапуска анимации
            requestAnimationFrame(() => {
                refreshIcon.classList.add('rotating');
            });
        });
        
        generateCaptcha();
        setCaptcha();
    }
    initCaptcha();

    const captchaInput = document.querySelector(".login-form .captcha input[type='text']");
    const submitButton = document.querySelector(".login-form input[type='submit']");

    document.getElementById("login-btn").addEventListener("click", function() {
        const messageDiv = document.getElementById("message");
        // Проверяем, совпадает ли введенное значение с капчей
        if (captchaInput.value === captchaValue) {
            submitButton.disabled = false; // разблокируем кнопку отправки
            messageDiv.className = "message success"; // Устанавливаем класс для успешного сообщения
            messageDiv.textContent = "Капча введена правильно!"; // Сообщение о правильности капчи
            messageDiv.style.display = "block"; // Показываем сообщение
            
            // Скрываем сообщение через 3 секунды
            setTimeout(hideMessage, 3000);
        } else {
            submitButton.disabled = true; // блокируем кнопку, если капча неверная
            messageDiv.className = "message error"; // Устанавливаем класс для сообщения об ошибке
            messageDiv.textContent = "Неверная капча, попробуйте еще раз."; // Сообщение об ошибке
            messageDiv.style.display = "block"; // Показываем сообщение
            
            // Скрываем сообщение через 3 секунды
            setTimeout(hideMessage, 3000);
        }
    });
    
    // Предотвращаем отправку формы, если капча неверная
    document.getElementById("gb_container").addEventListener("submit", function(event) {
        if (captchaInput.value !== captchaValue) {
            event.preventDefault(); // предотвращаем отправку формы
            const messageDiv = document.getElementById("message");
            messageDiv.className = "message error"; // Устанавливаем класс для сообщения об ошибке
            messageDiv.textContent = "Пожалуйста, введите правильную капчу."; // показываем сообщение об ошибке
            messageDiv.style.display = "block"; // Показываем сообщение
            
            // Скрываем сообщение через 3 секунды
            setTimeout(hideMessage, 3000);
        }
    });
})();

function hideMessage() {
    const messageDiv = document.getElementById("message");
    messageDiv.classList.add("fade-out"); // Добавляем класс fade-out для анимации

    // Устанавливаем display: none после завершения анимации
    setTimeout(() => {
        messageDiv.style.display = "none"; // Скрываем элемент
        messageDiv.classList.remove("fade-out"); // Убираем класс для следующего использования
    }, 100); 
}