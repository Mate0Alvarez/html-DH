<!doctype html>
<html>
	<head>
		<title><?=HTML::chars($title)?></title>
        <meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, user-scalable=no">
		<meta name="description" content="Botster is an online chatbot artificial intelligence which learns from the conversations it has with users on the Internet." />
		<link rel="shortcut icon" href="<?=URL::site('images/logos/16.png')?>" />
		<link rel="stylesheet" href="<?=URL::site('css/style.css')?>" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript" src="<?=URL::site('javascript/cookies.js')?>"></script>
		<script type="text/javascript" src="<?=URL::site('components/mustache/mustache.min.js')?>"></script>
	</head>
	<body>
        <script>var config = <?=json_encode($config)?>;</script>
		<script type="text/javascript" src="<?=URL::site('javascript/google_analytics.js')?>"></script>
		<header class="main">
            <div class="container">
                <a href="<?=URL::site()?>" class="logo"><img src="<?=URL::site('images/logos/64.png')?>" alt="" /></a>
                <div class="title"><a href="<?=URL::site()?>">Botster</a></div>
                <img src="<?=URL::site('images/icons/information.png')?>" alt="Menu" title="Toggle menu" id="menu-toggle" class="menu-toggle" />
            </div>
		</header>
        <div id="menu" class="menu">
            <div class="about">
                Hello there, my name's Botster and I'm an open-source chatbot artificial intelligence! My goal is to be able to have conversations with humans which are intellectual, useful, and entertaining. I learn from every conversation I have, therefore my responses are constantly improving. I learn by seeing what others reply with to certain messages; I'm a bit of a copy-cat really. After I have more and more example replies to a message, I can then work out which of them are most suitable.
			</div>
            <footer class="main">
                <a href="https://github.com/lentech/botster" target="_blank" class="github-link">
                    <img src="<?=URL::site('images/icons/github.png')?>" alt="GitHub" title="GitHub" />
                </a>
                <a href="http://lentech.org" target="_blank" class="organisation">
                    <img src="<?=URL::site('images/lentech_logo.png')?>" alt="Lentech" title="Lentech" />
                </a>
            </footer>
        </div>
            <?=$page?>
        </div>
        <script type="text/javascript" src="<?=URL::site('javascript/menu.js')?>"></script>
	</body>
</html>
