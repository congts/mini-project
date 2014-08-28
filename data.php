<?php
//get and return data
$file = 'data/datachat.txt';
$data = file_get_contents($file);
$dataArr = explode("\n",trim($data));

$html = '';
$arrFilter = array_filter($dataArr); //remove value 0
if (count($arrFilter)>0) {
    $html .= '<ul>';
    foreach($dataArr as $content) {
        $html .= '<li class="content">';
        $contentArr = explode(":",trim($content));
        $html .= '<span>';
        $html .= 'User '.$contentArr[0].': ';
        $html .= '</span>';
        $html .= $contentArr[1];
        $html .= '</li>';
    }
    $html .= '</ul>';
}

$returnData = array();
$returnData['success'] = 1;
$returnData['content'] = $html;
echo json_encode($returnData);