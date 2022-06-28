var el = document.getElementById("rollNumber");
var od = new Odometer({
  el: el,
  auto: false,
  format: "(dd).dd",
});
window.odometerOptions = {};
(function ($) {
  "use strict";
  let firstGame = true;
  function updateGame() {
    setTimeout(() => {
      const multi = $("#multiplier").val();
      const multi2 = 100 / multi;
      const multi3 = multi2 * ((100 - edge) /100);
      const betAmount = $("#betAmount").val();
      const grossProfit = multi3 * betAmount;
      const profit2 = grossProfit - betAmount;
      $("#profit").val(profit2.toFixed(2));
    }, 50);
  }

  function updateMultiplier() {
    const mulmultiplier = $("#multiplier").val();
    $("#rollLo").html(`Roll under ${mulmultiplier}`);
    $("#rollHi").html(`Roll over ${100 - mulmultiplier}`);
  }

  $("#double").click(function () {
    const betAmount = $("#betAmount").val();
    if (betAmount !== "") {
      const newAmount = 2 * parseFloat(betAmount);
      $("#betAmount").val(newAmount.toFixed(2));
      updateGame();
    }
  });

  $("#half").click(function () {
    const betAmount = $("#betAmount").val();
    if (betAmount !== "") {
      const newAmount = 0.5 * parseFloat(betAmount);
      $("#betAmount").val(newAmount.toFixed(2));
      updateGame();
    }
  });

  $("#betAmount").keydown(function () {
    setTimeout(() => {
      updateGame();
    }, 50);
  });

  $("#betAmount").keyup(function () {
    updateGame();
  });

  $("#betAmount").change(function () {
    updateGame();
  });

  $("#multiplier").keydown(function () {
    updateMultiplier();
    updateGame();
  });

  $("#multiplier").keyup(function () {
    updateMultiplier();
    updateGame();
  });

  $("#multiplier").change(function () {
    updateMultiplier();
    updateGame();
  });

  function alertDice() {}

  $("#rollHi").click(function () {
    rollDice("rollHi");
  });
  $("#rollLo").click(function () {
    rollDice("rollLo");
  });

  function rollDice(rollType) {
    $("#diceHistory").html();
    $("#rollHi").attr("disabled", "disabled");
    $("#rollLo").attr("disabled", "disabled");
    var multiplier = $("#multiplier").val();
    var betAmount = $("#betAmount").val();
	$.ajax({
		type: 'POST',
		url: site_url + '/system/ajax.php',
		data: {a: 'rollDice', token: token, multiplier: multiplier, betAmount: betAmount, rollType: rollType},
		dataType: 'json',
		success: function(data) {
			if(data.status == 200) {
				$("#hashRoll").text(data.proof);
				const recentHistory = data.recent;
				od.update(recentHistory.roll);
				$("#result").html('<div class="alert text-center alert-info"><i class="fas fa-circle-notch fa-spin"></i> Please wait...</div>');
				setTimeout(function () {
					$("#rollHi").removeAttr("disabled");
					$("#rollLo").removeAttr("disabled");
					$("#sidebarCoins").html(data.userCoins);
					$("#result").html('<div class="alert text-center alert-'+data.type+'">'+data.message+'</div>');

					const diceHistory = $("#diceHistory").html();
					const recentDice = '<tr><th scope="row" title="'+recentHistory.secret+'">'+recentHistory.id+'</th><td>'+recentHistory.target+'</td><td>'+recentHistory.bet+'</td><td>'+recentHistory.roll+'</td><td>'+recentHistory.profit+'</td><td><button type="button" class="btn btn-info btn-sm" onclick="showRound('+recentHistory.id+');">Verify</button></td></tr>';
					$("#diceHistory").html(recentDice + diceHistory);
				}, 2000);
			} else {
				$("#result").html('<div class="alert text-center alert-'+data.type+'">'+data.message+'</div>');
				$("#rollHi").removeAttr("disabled");
				$("#rollLo").removeAttr("disabled");
			}
		}
	});
  }

  updateMultiplier();
  updateGame();
})(jQuery);

function showRound(id) {
	if(id > 0) {
		$('#secret').val('');
		$('#roll').val('');
		$('#rollID').html('');
		$('#verifyResult').html('');
		$.ajax({
			type: "GET",
			url: "system/ajax.php",
			data: {a: 'getRollDice', token: token, id: id},
			dataType: "json",
			success: function(data) {
				if(data.status == 200) {
					$('#secret').val(data.secret);
					$('#roll').val(data.roll);
					$('#rollID').html('#'+id);
				}
			}
		});
	}

	$('#verifyModal').modal({backdrop:'static',keyboard:false,show:true});
}

$('#verifyRoll').on('click',function(){
	var secret = $('#secret').val();
	var roll = $('#roll').val();
	$('#verifyResult').html(waitMsg);
	$.ajax({
		type: "GET",
		url: "system/ajax.php",
		data: {a: 'getRollDiceHash', roll: roll, secret: secret},
		dataType: "json",
		success: function(data) {
			if(data.status == 200) {
				$('#verifyResult').html(data.message);
			}
		}
	});

	return false;
});