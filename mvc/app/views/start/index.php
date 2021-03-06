<?php

//header('Content-type: application/xhtml+xml');

define('ROOT_PATH','/');
$root_path=ROOT_PATH;

$image_list=[
    'https://fc02.deviantart.net/fs71/f/2012/291/a/4/yoonsic_pink_edit_by_helena_10-d5i6hws.jpg',
    'https://upload.wikimedia.org/wikipedia/commons/4/4a/Adelie_chicks_in_antarctica_and_Ms_Explorer.jpg',
    'https://upload.wikimedia.org/wikipedia/commons/5/52/Bread-band-oct1970.jpg',
    'https://upload.wikimedia.org/wikipedia/commons/4/4f/Second_aliyah_Pioneers_in_Migdal_1912_in_kuffiyeh.jpg',
    'http://www.jisc.ac.uk/sites/default/files/ideas-campaign.jpg',
    'https://fc03.deviantart.net/fs71/i/2013/215/9/9/false_memory_by_scottman2th-d6gg85l.jpg',
    'https://upload.wikimedia.org/wikipedia/commons/5/59/Spencer_Tunick_Nude_Installation.jpg'
];

$main_img=$image_list[rand(0,sizeof($image_list)-1)];

$content=<<<_END
<!DOCTYPE html>
<html lang="en">

<head>
    <title>ditup::start</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=600" />
    <link rel="stylesheet" href="/css/start/start.css" />
    <link rel="stylesheet" type="text/css" href="/fonts/font-awesome-4.3.0/css/font-awesome.min.css" />
</head>

<body>
<div class="container">
    <div id="main" class="content">
        <header class="title">
            <h1 class="title">ditup : do it together</h1>
            <p>online platform for real world collaboration</p>
        </header>
        <div class="start-image-info">
        <div class="start-image"><img class="flip-vertical" title="random picture" src="{$main_img}"></img>
        The picture is random. If you don\'t like it, reload the page.
        </div>
            <div class="start-info">
            <hr />
            <ul>
                <li>We are developing</li>
                <li><a href="/">demo</a> (unstable, work in progress)</li>
                <li><a href="../we-want-you">Join our team!</a> <a target="_blank" href="http://mrkvon.org">(contact)</a></li>
                <li><a href="/development">Check the development process</a></li>
                <li><a href="/signup">Create your profile</a> (unstable)</li>
                <!--li><a href="../subscribe">Subscribe for news</a> (work in progress)</li-->
            </ul>
            <hr />
            <div id="social">
                <a title="GitHub" target="_blank" href="https://github.com/ditup/ditup"><span class="fa fa-github-square fa-3x"></span></a>
                <a title="google+" target="_blank" href="" rel="publisher"><span class="fa fa-google-plus-square fa-3x"></span></a>
                <a title="twitter" target="_blank" id="twitter_img" href=""><span class="fa fa-twitter-square fa-3x"></span></a>
                <a title="RSS" target="_blank" href="{$root_path}rss"><span class="fa fa-rss-square fa-3x"></span></a>

                <!--div id="github"><a title="GitHub" target="_blank" href="https://github.com/ditup/ditup"><img src="{$root_path}img/github-icon.png" alt="github icon" width="30" height="30" /></a></div>
                <div id="google-plus"><a title="google+" target="_blank" href="" rel="publisher"><img src="{$root_path}img/google-plus-icon.png" alt="google+ icon" width="30" height="30" /></a></div>
                <div id="twitter"><a title="twitter" target="_blank" id="twitter_img" href=""><img src="https://abs.twimg.com/a/1378701295/images/resources/twitter-bird-white-on-blue.png" alt="twitter link" width="30" height="30" /></a></div>
                <div id="rss"><a title="RSS" target="_blank" href="{$root_path}rss"><img src="{$root_path}img/rss-icon.png" alt="rss icon" width="30" height="30" /></a></div-->
            </div>
            <hr />
            <p>ditup.org is a free network for collaboration on anything. The name stands for Do It Together -- start it UP.
            Explore the best practices of collaborating in the real world. Connect with people and work together on stuff that matters. Develop a good environment for that.</p>
        </div>
      </div>
    </div>
</div>
</body>
</html>
_END;

echo $content;
