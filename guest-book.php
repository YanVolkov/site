<?php 

session_start();
require_once 'config.php';
require_once 'functions.php';

if($_POST['submit']){
    $stmt = addPost($_POST['name'], $_POST['email'], $_POST['msg']);

    if($stmt){
        $_SESSION['res'] = $stmt;
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }else{
        $_SESSION['name'] = clearDataClient($_POST['name']);
        $_SESSION['email'] = clearDataClient($_POST['email']);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Guest-book</title>
    <meta charset="utf-8"><meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

    <!-- Header -->
        <header id="header">
            <div class="inner">
                <a href="index.html" class="logo">Yan Volkov</a>
                <nav id="nav"><a href="index.html">Главная</a>
                    <a href="development.html">Разработчик</a>
                     <a href=" ">Гостевая книга</a>
                    <a href="tests.html">Тесты</a>
                </nav>
            </div>
        </header>
        <a href="#menu" class="navPanelToggle"><span class="fa fa-bars"></span></a>

        <section id="main"><div class="inner">
        <form action="guest-book.php" method="POST" id="gb_container">
            <div class="login-form">
                <div class="form-title">
                    Гостевая книга
                </div>
                <div class="form-input">
                    <label for="username">Имя</label>
                    <input type="text" id="username" name="name" required>
                </div>
                <div class="form-input">
                    <label for="E-Mail">E-Mail</label>
                    <input type="email" id="E-Mail" name="email" required>
                </div>
                <div class="captcha">
                    <label for="captcha-input">Введите капчу</label>
                    <div class="preview"></div>
                    <div class="captcha-form">
                        <input type="text" id="captcha-form" placeholder="Введите капчу" required>
                        <button type="button" class="captcha-refresh">
                        <i class="fa fa-refresh"></i>
                        </button>
                    </div>
                    <div class="form-input">
                        <button type="button" id="login-btn">Проверить капчу</button>
                    </div>
                    <div class="message" id="message"></div>
                </div>
                <div class="msg">
                    <textarea class="msg" name="msg" required></textarea>
                </div>
                <div class="send">
                    <div class="hover-block" title="Введите капчу">
                        <input type="submit" id="send-btn" name="submit" value="Отправить" disabled /> <!-- Кнопка отправки изначально заблокирована -->
                    </div>
                </div>
                <div class="msgblock"></div>
                    <?php
                    $commentsPerPage = 5; // Количество комментариев на одной странице
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Установим начальное значение 1, если параметр не задан
                    $start = ($page - 1) * $commentsPerPage; // Вычисляем смещение для SQL запроса
                    $stmt = "SELECT id, name, email, post, LEFT(date, 16) AS date FROM post  ORDER BY date DESC LIMIT  $start, $commentsPerPage"; // Подготовим SQL запрос
                    $result = $pdo->query($stmt); // Выполним запрос к базе данных
                    $posts = $result->fetchAll();
                    if(is_array($posts) && !empty($posts)){
                        foreach($posts as $post){
                            ?>
                            <div class="msg_container">
                                <div class="msg_header">
                                    <b><?php echo clearDataClient($post['name'])?> </b><?php echo clearDataClient($post['email'])?>
                                </div>
                                <div class="msg_body">
                                    <?php echo nl2br(clearDataClient($post['post']))?>
                                </div>
                                <div class="msg_footer">
                                    Коментарий добавлен: <?php echo $post ['date']?><b>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<span style="font-size: 20px;">Нет сообщений для отображения.</span>';
                    }
                    // Получим общее количество комментариев
                    $totalComments = $pdo->query("SELECT COUNT(*) FROM post")->fetchColumn();
                    $pages = ceil($totalComments / $commentsPerPage); // Вычислим количество страниц

                    // Выведем ссылки для пагинации
                    for ($i = 1; $i <= $pages; $i++) {
                        // Проверим, является ли текущая страница активной
                        $active = $page == $i ? 'style="font-weight: bold;"' : '';
                        echo "<a href='?page=$i' $active>$i</a> ";
                    }
                ?>
            </div>
            </div>
        </section>

<?php
    
    unset ($_SESSION['res']);
    unset($_SESSION['name']);
    unset($_SESSION['email']);

?>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script><script src="assets/js/skel.min.js"></script><script src="assets/js/util.js"></script><script src="assets/js/main.js"></script><script src="Script.js"></script>
    <script src="sweetalert.min.js"></script>
</body>
</html>
