<?php
session_start();
$file = 'data/datachat.txt';
$userFile = 'data/userdata.txt';
if (!is_dir('data')) {
    mkdir('data');
}
if (!is_file($file)) {
    touch($file);
    chmod($userFile,777);
}
if (!isset($_POST['user']) || $_POST['user'] == "" || $_POST['user'] == "user") {
    echo "Invalid User!"; return;
} else {
    $userData = $_POST['user'];
    $userId = str_replace('user',"",$userData);

    $sessionId = session_id();
    $userData = file_get_contents($userFile);
    $userDataArr = unserialize($userData);
    if (!isset($userDataArr[$sessionId]) || $userId != $userDataArr[$sessionId]) {
        echo "Invalid User!"; return;
    }

    if (isset($_POST['val']) && $_POST['val'] != "") {
        $chatData = file_get_contents($file);
        $chatDataArr = explode("\n",$chatData);
        if (count($chatDataArr) > 30) {
            array_shift($chatDataArr);
            file_put_contents($file,implode("\n",$chatDataArr));
        }
        $data = $userId.':'.str_replace("\n","",$_POST['val'])."\n";
        file_put_contents($file,$data,FILE_APPEND);
        echo 'success';
    }

}