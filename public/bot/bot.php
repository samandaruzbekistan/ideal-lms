<?php
require_once "Telegram.php";

$token = "";

// Created at Samandar Sariboyev - samandarsariboyev69@gmail.com - +998 97 567 20 09
$username = "";
$host = "";
$password = "";
$db = "";

$telegram = new Telegram($token);
$data = $telegram->getData();
$message = $data['message'];
$text = $message['text'];
$chat_id = $message['chat']['id'];


$con = mysqli_connect($host, $username, $password, $db);
if (isset($con)) {
    echo "Yes DB";
}
if ($text == '/start') {
    Start();
} elseif ($text == '/restart') {
    SetPage('start');
    sendMessage("<b>Assalomu aleykum!</b>\nIdeal Study NTM - LMS ning rasmiy telegram botiga xush kelibsiz!\nFarzandingiz loginini kiriting ⬇️");
} else {
    $r = mysqli_query($con, "Select * from `bot_users` where `chat_id` = {$chat_id}");
    $p = mysqli_fetch_assoc($r);
    $page = $p['page'];
    switch ($page) {
        case 'start':
            $sql = "UPDATE `bot_users` SET `temp_data`='{$text}' WHERE `chat_id` = {$chat_id};";
            mysqli_query($con, $sql);
            SetPage('getPassword');
            sendMessage('Parolni yuboring');
            break;
        case 'getPassword':
            $r = mysqli_query($con, "SELECT * FROM `students` WHERE `login` = '{$p['temp_data']}' AND `password` = '{$text}';");
            if (mysqli_num_rows($r) > 0) {
                $sql1 = "UPDATE `students` SET `chat_id`={$chat_id} WHERE `login`='{$p['temp_data']}';";
                mysqli_query($con, $sql1);
                SetPage('success');
                sendMessage("🎉 Tabriklaymiz. Bot muvaffaqiyatli ulandi. Farzandingiz baholari shu bot orqali yetkazib turiladi. Botni qayta ishga tushurish uchun /restart komandasini yuboring");
            } else {
                SetPage('start');
                sendMessage("❌ Login yoki parol xato! Tekshirib qayta kiriting.\n\nFarzandingiz loginini kiriting ⬇️");
            }
            break;
        case 'success':
            sendMessage('Bot tizimga ulangan. Qayta ulash uchun /restart komandasini yuboring');
            break;
    }
}



echo 22;


function Start()
{
    global $chat_id, $message, $con, $data;
    $user = mysqli_query($con, "SELECT * FROM  `bot_users` where `chat_id` =  {$chat_id}");
    $dat = json_encode($data);
    if (mysqli_num_rows($user) < 1) {
        $sql = "INSERT INTO `bot_users`(`chat_id`, `name`, `page`, `data`) VALUES ($chat_id, '{$message['from']['first_name']}','start', '{$dat}')";
        $r = mysqli_query($con, $sql);
        SetPage('start');
        sendMessage("<b>Assalomu aleykum!</b>\nIdeal Study NTM - LMS ning rasmiy telegram botiga xush kelibsiz!\nFarzandingiz loginini kiriting ⬇️");
    } else {
        SetPage('start');
        sendMessage("<b>Assalomu aleykum!</b>\nIdeal Study NTM - LMS ning rasmiy telegram botiga xush kelibsiz!\nFarzandingiz loginini kiriting ⬇️");
    }

}


function SetPage($name)
{
    global $chat_id, $con;
    $r = mysqli_query($con, "UPDATE `bot_users` SET `page`='{$name}' WHERE `chat_id` = {$chat_id}");
}


function sendMessage($text)
{
    global $telegram, $chat_id;
    $telegram->sendMessage(['chat_id' => $chat_id, 'reply_markup' => json_encode(['remove_keyboard' => true], true), 'text' => $text, 'parse_mode' => "HTML"]);
}

