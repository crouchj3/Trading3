<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<link  href="/css/site.css" rel="stylesheet" type="text/css" />
	<link href="/css/msg.css" rel="stylesheet" type="text/css" />
	<script src="/js/jquery-1.3.2.min.js"></script>
	<script src="/js/msg.js"></script>
	<script src="/js/dropdown.js"></script>
</head>
<body>

<div class="container">
	<div class="header">
		<div class="siteTitle">
			Title #013783 Callibri Is Not A Web Font
		</div>
		<div class="siteLogo">
			Logo
		</div>
		<div style="clear:both;"></div>
	</div>
	
	<div class="body">
		<div class="leftColumn">
			<ul>
				<li><a href="/s/home/index">Home</a></li>
				<?php if($isLoggedIn) {?>
					<li><a href="/s/game/index">Games</a></li>
					<li><a href="/s/game/newGame">New Game</a></li>
					<li><a href="/s/account/logout">Logout</a></li>	
				<?php } else { ?>
					<li><a href="/s/account/login">Login</a></li>
					<li><a href="/s/account/register">Register</a></li>
				<?php } ?>
				<?php if($adminMenu) { ?>
					<li class="nav_right" onmouseover="change('dropdown_1');" onmouseout="change('dropdown_1');"><a href="#">Admin<div class="nav_right"></div></a>
						<div id="dropdown_1">
							<ul>
								<?php if($menuPerm) { ?><li><a href="/s/admin/perm">Permissions</a></li><?php } ?>
								<?php if($menuSettings) { ?><li><a href="/s/admin/settings">Site Settings</a></li><?php } ?>
								<?php if($menuUsers) { ?><li><a href="/s/admin/users">Site Users</a></li><?php } ?>
								<?php if($menuUpdateGame) { ?><li><a href="/s/game/updateTrades">Trade Updater</a></li><?php } ?>
								<?php if($menuUpdateGame) { ?><li><a href="/s/game/updatePortfolios">Portfolio Updater</a></li><?php } ?>
								<?php if($menuUpdateGame) { ?><li><a href="/s/game/updateNightly">Status Updater</a></li><?php } ?>
							</ul>
						</div>
					</li>
				<?php } ?>
			</ul>
		</div>
		<div class="content">

<?php
	/*if(userClass::checkLogin()) {
		echo "<br /><br />";
		echo "My Games: ";
		$mine = gameClass::getPlayerGames(sessionClass::getId());
		foreach($mine as $value) {
			list($uri,$name) = gameClass::getInfoById($value);
			echo "<a href=\"/one/s/game/info/".$uri."\">".$name."</a>  ";
		}
	}*/
?>