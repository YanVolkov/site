<?php 

include 'config.php';

//очистка данных
function clearData($var){
    $var = trim (mysql_real_escape_string($var));
    return $var;
}

//очистка данных для клиента
function clearDataClient($var){
    $var = htmlspecialchars($var);
    return $var;
}

//добавление сообщения
function addPost($name, $email, $msg){
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO post (name, email, post)  VALUES (:name, :email, :msg)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':msg', $msg);
    $stmt->execute();
}

//выборка сообщений
function selectAll(){
    global $pdo;
    $stmt = $pdo->query('SELECT id, name, email, post, LEFT(date, 16) AS date FROM post ORDER BY date DESC');
    $data = $stmt->fetchAll();
    return $data;
}

?>