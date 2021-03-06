<?php

require_once '../app/views/general/PageWithHeader.php';

$page=new PageWithHeader($data['loggedin'], $this->profile);
$page->title('messages::drafts');
$content = '
<div>
    <h1>Draft Messages of User <a href="/user/'.$data['user-me'].'">'.$data['user-me'].'</a></h1>';
foreach($data['messages'] as $msg){
    $content.= '
    <div><a href="/message/'.$msg['from-user']['username'].'/'.$msg['create-time'].'" >message</a></div>';
}
$content.='
</div>';

$page->css('/css/messages/compose.css');

$page->add($content);

echo $page->generate();

