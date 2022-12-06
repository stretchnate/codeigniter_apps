var show = 0;
var money = 1000;
var win_count = 0;
var double_down = 0;
var face = new Array(2,3,4,5,6,7,8,9,10,"J","Q","K","A");
var suit = new Array("&spades;","&hearts;","&clubs;","&diams;");
var blackjack_count = 0;
var hands_dealt = 0;
var multiplier = 1000;
var dealer_total;

$(document).ready(function() {
	$("#pot").html("$"+money);
	$("select[name=bet_options], #dealBlackJack").removeAttr('disabled');
	$(".buttons button").attr('disabled','disabled').show();
	$("select[name=bet_options]").change(function() {
		if(!validBet()) {
			$(this).removeAttr('selected');
			$("#dealBlackJack").attr('disabled','disabled');
			disableBetOptions();
			alert('not enough money to make that bet');
		} else {
			$("#dealBlackJack").removeAttr('disabled');
		}
	});
	$("html").click(function() {
		$("#player").focus();
	});
	$("#container").keyup(function(e) {
		//hit
		if(e.keyCode == 65 || e.charCode == 65 || e.keyCode == 97 || e.keyCode == 97) {
			//click players hit
			if(!$("#player").attr("disabled")) {
				$("#player.hit").click();
			} else {
				$("#dealerTrash").html('You can\'t hit, it\'s my turn');
			}
		} else if(e.keyCode == 83 || e.charCode == 83 || e.keyCode == 115 || e.keyCode == 115) { //stay
			//click stay button
			if(!$("#playerStay").attr('disabled')) {
				showDealer(true);
			} else {
				$("#dealerTrash").html('You can\'t stay more than once per hand');
			}
		} else if(e.keyCode == 70 || e.charCode == 70 || e.keyCode == 102 || e.keyCode == 102) { //double down
			//click double down
			if(!$("#doubleDown").attr('disabled')) {
				$("#doubleDown").click();
			} else {
				$("#dealerTrash").html('You can\'t double down after the first hit');
			}
			
		} else if(e.keyCode == 68 || e.charCode == 68 || e.keyCode == 100 || e.keyCode == 100) {
			if(!$("#dealBlackJack").attr('disabled')) {
				$("#dealBlackJack").click();
			} else {
				$("#dealerTrash").html('You can\'t deal in the middle of a hand!');
			}
		} else if(e.keyCode == 32 || e.charCode == 32) {
			if(money < 25) {
				atm();
			} else {
				$("#dealerTrash").html("Don't run to the ATM yet, you might win it back");
			}
		}
	});
	$("#dealBlackJack").click(function() {
		if(validBet()) {
			var data = deck('deal');
			var j = 1;
			var player_total = 0;
			var player_cards = new Array();
			var dealer_total = 0;
			var dealer_cards = new Array();
			var color = 'black';
			hands_dealt++;
			$("#hands-dealt span").html(hands_dealt);
			$("select[name=bet_options]").attr('disabled','disabled'); //add #dealer if can't get dealerPlay() to work.
			$("#playerCards, #dealerCards, #playerScore, #dealerScore, #message, #playerAlert").html('&nbsp;');
			$("#dealerScore, #playerAlert").hide();
			$("input[name=player_cards], input[name=dealer_cards]").val('');
			$("#message").css("color","blue");
			show = 0;
			if((parseFloat($("select[name=bet_options]").val()) * 2) > money) {
				$("#doubleDown").attr('disabled','disabled');
			}
			for(var i = 0;i < 4;i++) {
				color = getColor(data[i].suit);
				if(i % 2 == 0) {
					$("#playerCards").append("<span style='color:"+color+";'>"+data[i].num+data[i].suit+"</span>");
					player_cards[i] = data[i].num + data[i].suit;
					if(data[i].num == "J" || data[i].num == "Q" || data[i].num == "K") {
						player_total = player_total + 10;
					} else if(data[i].num == "A") {
						if(player_total > 10) {
							player_total = player_total + 1;
						} else {
							player_total = player_total + 11;
						}
					} else {
						player_total = player_total + data[i].num;
					}
	
					$("#playerTotal").val(player_total);
					$("#playerScore").html(player_total);
				} else {
					$("#dealerCards").append('<span  style="color:'+color+';" id="_'+j+'">'+data[i].num+data[i].suit+"</span>");
					dealer_cards[j] = data[i].num + data[i].suit;
					if(data[i].num == "J" || data[i].num == "Q" || data[i].num == "K") {
						dealer_total = dealer_total + 10;
					} else if(data[i].num == "A") {
						if(dealer_total > 10) {
							dealer_total = dealer_total + 1;
						} else {
							dealer_total = dealer_total + 11;
						}
					} else {
						dealer_total = dealer_total + data[i].num;
					}
	
					$("#dealerTotal").val(dealer_total);
					j++;
				}
				$("input[name=player_cards]").val(player_cards.join());
				$("input[name=dealer_cards]").val(dealer_cards.join());
			}
			$("#_2").hide();
			$("#dealerCards").append("<span id='_100'><a href='javascript:void(null)'>&spades;&hearts;&clubs;&diams;</a></span>");
			$("#player, #playerStay, #doubleDown").removeAttr('disabled');
			$("#dealBlackJack").attr('disabled','disabled');
			takeOutTheTrash();
			if(hands_dealt == 1) {
				$("#dealerTrash").html('I hope you wore your big boy undies.');
			}
			if(player_total == 21) {
				blackjack_count++;
				$("#playerScore").html("BlackJack "+player_total);
				blackJack(player_total,dealer_total);
			} else {
				blackjack_count = 0;
			}
			$("#player").focus();
		} else {
			$("#dealerTrash").html("you can't afford that bet");
		}
	});

	$(".hit").click(function() {
		var target = $(this).attr('id');
		var target_total = parseInt($("#"+target+"Total").val());
		var target_cards = $("input[name="+target+"_cards]").val().split(",");
		var index = target_cards.length;
		var allCards = getAllCards();
		var data = deck('hit');
		var color = getColor(data[0].suit);
		$("#doubleDown").attr("disabled","disabled");
		$("#"+target+"Cards").append("<span style='color:"+color+"'>"+data[0].num+data[0].suit+"</span>");
		target_cards[index] = data[0].num + data[0].suit;
		if(data[0].num == "J" || data[0].num == "Q" || data[0].num == "K") {
			target_total = target_total + 10;
		} else if(data[0].num == "A") {
			if(target_total > 10) {
				target_total = target_total + 1;
			} else {
				target_total = target_total + 11;
			}
		} else {
			target_total = target_total + data[0].num;
		}

		$("#"+target+"Total").val(target_total);
		$("input[name="+target+"_cards]").val(target_cards.join());
		$("#"+target+"Score").html(target_total);

		if(target_total == 21) {
			if(target == "player") {
				showDealer(true);
			}
		} else if(target_total > 21) {
			//need to search for Aces
			target_total = recalculateTotals(target);
			$("#"+target+"Total").val(target_total);
			$("#"+target+"Score").html(target_total);
//			if(target_total > 21 || (target == 'dealer' && target_total == 17)) {
			if(target == 'player' && target_total > 21) {
				calculateTotals();
			}
		} else if(target_total > 16 && target_total < 21 && target == 'player') {
			calculateTotals();
		}
		if(target == "player" && double_down == 1) {
			showDealer(true);
		}
		$("#player").focus();
	});
	$("#doubleDown").click(function() {
		var bet = parseFloat($('select[name=bet_options]').val());
		if(bet * 2 <= money) {
			double_down = 1;
			$("#playerAlert").html("Double Down!").show();
			$("#player.hit").click();
		} else {
			$("#dealerTrash").html("You can't afford to double down");
		}
	});
	$("#dealBlackJack").focus();
});

function trashTalk() {
	var dealer_trash = new Array("Why don't you just give me your money?",
								"Oh man, I hope you're not betting your paycheck.",
								"Wow, you're really bad at this",
								"Maybe BlackJack isn't your game",
								"You've got to know when to hold 'em, know when to fold 'em, know when to walk away, and know when to run.",
								"You can't buy a hand");
	var player_trash = new Array("I'm Rollin' now!",
								"Woo, I'm bringin down the house",
								"I hope this place has enough money to pay me",
								"Don't Cry, it's embarrassing",
								"That's right, keep dealin' me the good cards");
	var player_complaints = new Array("This table has gone cold",
										"this is my hand I can feel it");
	var dealer_complaints = new Array("Did I shuffle this deck?",
										"I don't think I shuffled this deck",
										"Oh crap I'm gonna get fired",
										"I'm the worst BlackJack dealer ever",
										"Mommy!");
	var trash = '';
	switch(win_count) {
		case -10:
			$("#dealerTrash").html("Maybe you should take up crochet");
			break;
		case -9:
		case -7:
		case -3:
			trash = player_complaints[Math.floor(Math.random() * player_complaints.length)];
			$("#playerTrash").html(trash);
		case -5:
			trash = dealer_trash[Math.floor( Math.random() * dealer_trash.length )];
			$("#dealerTrash").html(trash);
			break;
		case 1:
		case 2:
		case 3:
		case 4:
			trash = player_trash[Math.floor( Math.random() * player_trash.length )];
			$("#playerTrash").html(trash);
			break;
		case 5:
		case 7:
		case 9:
		case 10:
			trash = dealer_complaints[Math.floor(Math.random() * dealer_complaints.length)];
			$("#dealerTrash").html(trash);
			break;
		default:
			if(money < 100) {
				$("#dealerTrash").html("please stop, this is embarrassing");
			}
			break;
	}
}

function recalculateTotals(hand) {
	var new_total = 0;
	var aces = 0;
	if(hand == "player") {
		hand = $("input[name=player_cards]").val().split(',');
		var total = parseInt($("#playerTotal").val());
	} else {
		hand = $("input[name=dealer_cards]").val().split(',');
		var total = parseInt($("#dealerTotal").val());
	}
	for(var i = 0;i < hand.length;i++) {
		if(hand[i].substring(0,1) == "A") {
			new_total = new_total + 11;
			aces++;
		} else if(hand[i].substring(0,1) == "J" || hand[i].substring(0,1) == "Q" || hand[i].substring(0,1) == "K") {
			new_total = new_total + 10;
		} else if(hand[i].substring(0,1) > 0) { 
			new_total = new_total + parseInt(hand[i]);
		}
	}
	for(var j = 0;j < aces;j++) {
		new_total = new_total - 10;
		if(new_total < 22) {
			break;
		}
	}
	return new_total;
}

function getAllCards() {
	var player = $("input[name=player_cards]").val();
	var dealer = $("input[name=dealer_cards]").val();
	var allCards = player + dealer;
	return allCards;
}

function getColor(suit) {
	if(suit == "&diams;" || suit == "&hearts;") {
		return "red";
	} else {
		return "black";
	}
}

function checkDealerTotal() {
	dealer_total = parseInt($("#dealerTotal").val());
	if(dealer_total < 17) {
		return false;
	} else if(dealer_total == 17) {
		if(checkSoft17()) {
			return false;
		}
	}
	return true;
}
function dealerPlay() {
	while(!checkDealerTotal()) {
		$("#dealer.hit").click();
	}
	return true;
}

function blackJack(player,dealer) {
	var dealer_shout = "Winner Winner Chicken Dinner!";
	showDealer(false);
	if(dealer == 21) {
		$("#dealerScore").html("BlackJack "+dealer);
		$("#message").html("PUSH");
	} else {
		var bet = parseFloat($("select[name=bet_options]").val());
		if(double_down == 1) {
			bet = bet * 2;
		}
		if(blackjack_count > 1) {
			money = money + (bet * 3);
			dealer_shout += " double blackjack pays triple";
		} else {
			money = money + (bet * 1.5);
		}
		$("#message").html("BlackJack YOU WIN");
		$("#pot").html("$"+money);
		if(win_count > 0) {
			win_count++;
		} else {
			win_count = 1;
		}
		$("#dealerTrash").html(dealer_shout);
//		gameReset(false);
	}
	gameReset(false);
	$("select[name=bet_options]").removeAttr('disabled');
}

function showDealer(calculate) {
	$("#_100").hide();
	$("#player, #doubleDown, #playerStay").attr('disabled','disabled');
	$("#_2, #dealerScore").show();
//	$("#dealer").removeAttr('disabled');
	$("#dealerScore").html($("#dealerTotal").val());
	show++;
	if(calculate === true) {
		dealerPlay();
		calculateTotals();
	}
}

function calculateTotals() {
	dealer_total = parseInt($("#dealerTotal").val());
	var blackjack = false;
	var player_total = parseInt($("#playerTotal").val());
	var bet = parseFloat($("select[name=bet_options]").val());
	var message = '';
	var win = 3;
	if(dealer_total > 21) {
		message += " Dealer Bust " + dealer_total;
		win = 1;
	} else if(player_total > 21) {
		$("#message").css("color","red");
		message += " BUST " + player_total;
		showDealer(false);
		win = 1;
	}

	if(player_total == 21) {
		showDealer(false);
		message += " You have 21!";
	}

	if(dealer_total == 21 && show > 1) {
		blackjack = checkDealerBlackJack();
		if(blackjack) {
			message += " Dealer has Blackjack";
		} else {
			message += "Dealer has 21";
		}
	}

	if(show > 0) {
		if(dealer_total > 16 || player_total > 21) {
			var soft_17 = false;
			if(player_total < 22 && dealer_total == 17) {
				soft_17 = checkSoft17();
			}
			if(!soft_17) {
				if((player_total > dealer_total && player_total < 22) || (dealer_total > 21 && player_total < 22)) {
					message += " YOU WIN!";
					win = 1;
					if(win_count > 0) {
						win_count++;
					} else {
						win_count = 1;
					}
				} else if(player_total == dealer_total && !blackjack) {
					message += " PUSH!";
					win = 2;
				} else {
					$("#message").css("color","red");
					message += " YOU LOSE!";
					win = 0;
					if(win_count < 0) {
						win_count--;
					} else {
						win_count = -1;
					}
				}
				if(double_down == 1) {
					bet = bet * 2;
				}
				switch(win) {
					case 0:
//						alert("subtracting " + bet + " dealer_total = " + dealer_total);
						money = money - bet;
						gameReset(true);
						break;
					case 1:
//						alert("adding " + bet + " dealer_total = " + dealer_total);
						money = money + bet;
						gameReset(true);
						break;
					case 2:
						gameReset(false);
						break;
				}
			}
		}
	}

	if(money < 25) {
		if(hands_dealt < 50) {
			$("#dealerTrash").html('take what you have left, and go home or go find an ATM, or a loan shark, or a pawn shop and hock your wedding ring.'+ 
					' But if you come back you better leave that weak stuff home.');
		} else if(hands_dealt > 49 && hands_dealt < 100) {
			$("#dealerTrash").html("Well you lasted as long as you could");
		} else if(hands_dealt > 99 && hands_dealt < 200) {
			$("#dealerTrash").html('Not bad, you hung in there for quite a while.')
		} else {
			$("#dealerTrash").html('WOW! you lasted '+hands_dealt+' hands, you\'re still broke, but nice job!');
		}
		message += "<br /><button id='atm' onclick='atm()'>ATM [space]</button>";
		$("#dealBlackJack").attr('disabled','disabled');
		$("select[name=bet_options]").focus();
	}
	$("#pot").html("$"+money);
	$("#dealerScore").html($("#dealerTotal").val());
	disableBetOptions();
	if(message.length > 0)
		$("#message").html(message);
}

function checkSoft17() {
	var cards = $("input[name=dealer_cards]").val().split(",");
	var aces = 0;
	var rest_of_cards = new Array();
	var j = 0;
	var z = 0;
	var rest_total = 0;

	//loops through cards similar to recalculateTotals()
	for(var i = 0;i < cards.length;i++) {
		if(cards[i].substring(0,1) == "A") {
			aces++;
			if(cards.length == 3) {
				return true;
			}
		}
		else if(cards[i].substring(0,1) == "J" || cards[i].substring(0,1) == "Q" || cards[i].substring(0,1) == "K") {
			rest_of_cards[j] = 10;
			j++;
		} else if(cards[i].substring(0,1) > 0) { 
			rest_of_cards[j] = parseInt(cards[i]);
			j++;
		}
	}
	for(z = 0;z < rest_of_cards.length;z++) {
		rest_total = rest_total + rest_of_cards[z];
	}
	switch(aces) {
		case 1:
			if(rest_total == 6) {
				return true;
			}
			break;

		case 2:
			if(rest_total == 5) {
				return true;
			}
			break;
		case 3:
			if(rest_total == 4) {
				return true;
			}
			break;
		case 4:
			if(rest_total == 3) {
				return true;
			}
	}
	return false;
}

function checkDealerBlackJack() {
	var cards = $("input[name=dealer_cards]").val().split(",");
	if(cards.length == 3) {
		return true;
	}
	return false;
}

function gameReset(trash) {
	if(trash == true) {
		trashTalk();
	}
	shuffle();
	double_down = 0;
	$(".buttons button").attr('disabled','disabled');
	$("#dealerScore").show();
	$("#dealBlackJack, select[name=bet_options]").removeAttr('disabled');
	disableBetOptions();
	computeAverage();
	$("#dealBlackJack").focus();
}

function shuffle() {
	face.sort(function() { return 0.5 - Math.random(); });
	suit.sort(function() { return 0.5 - Math.random(); });
}

function disableBetOptions() {
	$("select[name=bet_options]").children().each(function(index) {
		if($(this).val() > money) {
			$(this).attr('disabled','disabled');
		} else {
			$(this).removeAttr('disabled');
		}
	});
}

function deal(used_cards) {
	var card = new Array();
	card['num'] = face[Math.floor( Math.random() * face.length )];
	card['suit'] = suit[Math.floor( Math.random() * suit.length )];
	var new_card = card['num'] + card['suit'];
	var is_used = used_cards.indexOf(new_card);
	while(is_used != -1) {
		card['num'] = face[Math.floor( Math.random() * face.length )];
		card['suit'] = suit[Math.floor( Math.random() * suit.length )];
		new_card = card['num'] + card['suit'];
		is_used = used_cards.indexOf(new_card);
	}
	return card;
}

function deck(process) {
	var used_cards = new Array();
	var cards = new Array();

	if(process == 'hit') {
		cards[0] = new Array();
		used_cards = getAllCards().split(",");
		var response = deal(used_cards);
		cards[0]['num'] = response['num'];
		cards[0]['suit'] = response['suit'];
	} else {
		for(var i = 0; i < 4; i++) {
			cards[i] = new Array();
			var response = deal(used_cards); 
			cards[i]['num'] = response['num'];
			cards[i]['suit'] = response['suit'];
			used_cards[i] = response['num'] + response['suit'];
		}
	}
	return cards;
}

function validBet() {
	var bet = parseFloat($("select[name=bet_options]").val());
	if(bet > money) {
		return false;
	}
	return true;
}

function atm() {
	money = 1000;
	multiplier = multiplier + 1000;
	$("#pot").html("$"+money);
	$("#message, #playerTrash").html('&nbsp;');
	$("#dealerTrash").html('Back for more huh?');
	$("#hands-dealt span").html('');
	gameReset(false);
}

function takeOutTheTrash() {
	$("#dealerTrash, #playerTrash").html('&nbsp;');
}

function computeAverage() {
	var avg = 0;
	var total_cash = money - multiplier;
//	var dealt = hands_dealt - 1;
	if(money != multiplier) {
		avg = (total_cash / hands_dealt) * 1000;
		avg = Math.round(avg);
		avg = avg/1000;
	}
	$("#average-winnings span").html("$"+avg);
}
disableBetOptions();
shuffle();