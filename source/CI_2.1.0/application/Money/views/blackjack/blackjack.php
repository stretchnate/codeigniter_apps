<?php
//	require_once('Class/config.php');
//	if($_POST['func'] == 'blackjack') {
//		$blackjack = new BlackJack();
//		$cards = $blackjack->dealblackjack();
//		echo json_encode($cards);
//		exit();
//	}
//Blackjack v.1.0 = 21 no bets
//Blackjack v.1.1 = 21 $25 bets
//Blackjack v.1.2 = 21 bets up to $1k
//Blackjack v.1.3 = smoother game play
//Blackjack v.1.4 = cards dealt horizontally (and kinda resemble actual cards), keystrokes now play (no need for mouse), dealer hits on his own.
$title = "BlackJack";
$version = "v.1.4";
?>

<html>
	<head>
		<title><?php echo $title." ".$version; ?></title>
		<link href="<?php echo CSS_PATH; ?>blackjack.css" media="screen" rel="stylesheet" type="text/css" >
		<script type="text/javascript" src="/javascript/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="/javascript/js_blackjack.js"></script>
	</head>
	<body>
		<div id="container">
			<h1><?php echo $title; ?> <span><?php echo $version; ?></span></h1>
			<div id="rules">
				<ul>
					<li>&spades;</li>
					<li>Dealer Hits on soft 17</li>
					<li class="error">&hearts;</li>
					<li>BlackJack pays 3 to 2</li>
					<li>&clubs;</li>
					<li>BlackJack Beats 21</li>
					<li class="error">&diams;</li>
				</ul>
			</div>
			<div class="clear">&nbsp;</div>
			<div id="message">&nbsp;</div>
			<div id="hands-dealt">Hands Dealt: <span></span></div>
			<div id="average-winnings">Average Winnings: <span></span></div>
			<div id="dealer_hand">
				<h3>Dealer</h3>
				<button id="dealBlackJack">Deal New Hand [d]</button>
				<div id="playerAlert"></div>
				<div class="clear">&nbsp;</div>
				<div id="dealerTrash">&nbsp;</div>
				<input type="hidden" id="dealerTotal" value="" />
				<input type="hidden" name="dealer_cards" value="" />
				<div class="buttons">
					<div id="dealer" class="hit">&nbsp;</div>
				</div>
				<div class="clear">&nbsp;</div>
				<div class="accordian">
					<div id="dealerCards"></div>
					<div id="dealerScore"></div>
				</div>
			</div>
			<div id="cards">
				<h3>Player</h3>
				<div id="money">
					<div id="purse">Purse: <span id="pot"></span></div>
					<div id="bet">
						Current Bet:
						<select name="bet_options">
							<option value='25'>$25</option>
							<option value='50'>$50</option>
							<option value='75'>$75</option>
							<option value='100'>$100</option>
							<option value='150'>$150</option>
							<option value='250'>$250</option>
							<option value='500'>$500</option>
							<option value='750'>$750</option>
							<option value='1000'>$1000</option>
						</select>
					</div>
				</div>
				<div class="clear">&nbsp;</div>
				<div id="playerTrash">&nbsp;</div>
				<input type="hidden" id="playerTotal" value="" />
				<input type="hidden" name="player_cards" value="" />
				<div class="buttons">
					<button id="player" class="hit">Hit [a]</button>
					<button id="playerStay" onclick="showDealer(true)">Stay [s]</button>
					<button id="doubleDown">Double Down [f]</button>
				</div>
				<div class='accordian'>
					<div id="playerCards"></div>
					<div id="playerScore"></div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</body>
</html>