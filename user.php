<?php
session_start();
$userFile = 'data/userdata.txt';
if (!is_dir('data')) {
    mkdir('data');
}
if (!is_file($userFile)) {
    touch($userFile);
    chmod($userFile,777);
}

$userData = file_get_contents($userFile);
$sessionId = session_id();

$userDataArr = array();
if ($userData != "") {
    $userDataArr = unserialize($userData);

    if (is_array($userDataArr)) {
        $lastUserId = end($userDataArr);
        if (!isset($userDataArr[$sessionId]) || $userDataArr[$sessionId] == "")
            $userDataArr[$sessionId] = ++$lastUserId;

    } else {
        $userDataArr[$sessionId] = 1;
    }
} else {
    $userDataArr[$sessionId] = 1;
}
file_put_contents($userFile,serialize($userDataArr));
echo $userDataArr[$sessionId];
