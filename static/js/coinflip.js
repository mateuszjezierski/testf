jQuery(document).ready(function ($) {
  $("#double").click(function () {
    const betAmount = $("#betAmount").val();
    if (betAmount !== "") {
      const newAmount = 2 * parseFloat(betAmount);
      $("#betAmount").val(newAmount.toFixed(2));
    }
  });

  $("#half").click(function () {
    const betAmount = $("#betAmount").val();
    if (betAmount !== "") {
      const newAmount = 0.5 * parseFloat(betAmount);
      $("#betAmount").val(newAmount.toFixed(2));
    }
  });
  function coinFlip(flipResult) {
    $("#coinflip").removeClass();
    setTimeout(function () {
      if (flipResult === "BTC") {
        $("#coinflip").addClass("heads");
      } else {
        $("#coinflip").addClass("tails");
      }
    }, 100);
  }
  $(".bet-btn").on("click", function () {
    $("#betBTC").attr("disabled", "disabled");
    $("#betETH").attr("disabled", "disabled");
    var betAmount = $("#betAmount").val();
    var coin = $(this).attr("data-coin");
	$.ajax({
		type: 'POST',
		url: site_url + '/system/ajax.php',
		data: {a: 'coinFlip', token: token, betAmount: betAmount, coin: coin},
		dataType: 'json',
		success: function(data) {
			if(data.status == 200) {
				coinFlip(data.result);
				$("#result").html('<div class="alert text-center alert-info"><i class="fas fa-circle-notch fa-spin"></i> Please wait...</div>');
				setTimeout(function () {
				 $("#result").html('<div class="alert text-center alert-'+data.type+'">'+data.message+'</div>');
				 const coinflipHistory = $("#coinflipHistory").html();
				 const recentDice = '<tr><td>'+data.id+'</td><td>'+data.betAmount+'</td><td>'+data.coin+'</td><td>'+data.result+'</td><td>'+data.profit+'</td></tr>';
				 $("#coinflipHistory").html(recentDice + coinflipHistory);
				 $("#betBTC").removeAttr("disabled");
				 $("#betETH").removeAttr("disabled");
				 $("#sidebarCoins").html(data.userCoins);
				}, 3000);
			} else {
				$("#result").html('<div class="alert text-center alert-'+data.type+'">'+data.message+'</div>');
				$("#betBTC").removeAttr("disabled");
				$("#betETH").removeAttr("disabled");
			}
		}
	});
	
	return false;
  });
});
