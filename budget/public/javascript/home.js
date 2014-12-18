$(document).ready(function() {
	$("#accounts-tabs").tabs();

	$("a.tabs-link").click(function() {
		var account_to_show = $(this).attr("id").replace('-tab', '');
		$(".account-container").hide();
		$("#"+account_to_show).show();
	});

	$(".book h3").click(function() {
		var child = $(this).parent(".book").children(".book-content").get(0);
		if( $(child).css("display") != "none") {
			$(child).slideUp("slow");
		} else {
			$(child).slideDown("slow");
		}
	});

	$("#expand-contract-all").click(function() {
		var selected;
		$("#accounts-tabs ul li").each(function() {
			if( $(this).attr("class").match(/ui-tabs-selected/) ) {
				selected = $(this).children("a").eq(0).text().toLowerCase().replace(/[\s]/g, "_");
				return false;
			}
		});

		if( $(this).text() == "Expand All Categories") {
			$("#"+selected+" div.book div.book-content").slideDown("slow");
			$(this).text("Collapse All Categories");
		} else {
			$("#"+selected+" div.book div.book-content").slideUp("slow");
			$(this).text("Expand All Categories");
		}
	});

	$("a.tabs-link").click(function() {
		var selected = $(this).text().toLowerCase().replace(" ", "_")+" div.book div.book-content";
		var open = false;

		$("div#"+selected).each(function() {
			if( $(this).css("display") != "none" ) {
				open = true;
			}
		});

		if(open === false) {
			$("#expand-contract-all").text("Expand All Categories");
		} else {
			$("#expand-contract-all").text("Collapse All Categories");
		}
	});
});