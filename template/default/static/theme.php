<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
?>
@import url("https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900");
@import url("https://fonts.googleapis.com/css2?family=Wallpoet&display=swap");
html,body{margin: 0;padding:0;min-height:100%}
body{font-family:"Poppins", Helvetica, Arial, sans-serif;background:#6e6e6e url(images/background.jpg) repeat}
.navbar{background-color:#<?php echo $config['bg_color']; ?>;box-shadow: 0px 2px 5px 0px #00000024;}
.navbar .navbar-brand{color:#ecf0f1}
.navbar .navbar-brand:hover,.navbar .navbar-brand:focus{color:#d9d9d9}
.navbar .navbar-nav .nav-link{color:#fafafa;border-radius:.25rem;margin:0 .25em}
.navbar .navbar-nav .nav-link:not(.disabled):hover,.navbar .navbar-nav .nav-link:not(.disabled):focus{color:#d9d9d9}
.navbar .navbar-nav .nav-item.active .nav-link,.navbar .navbar-nav .nav-item.active .nav-link:hover,.navbar .navbar-nav .nav-item.active .nav-link:focus,.navbar .navbar-nav .nav-item.show .nav-link,.navbar .navbar-nav .nav-item.show .nav-link:hover,.navbar .navbar-nav .nav-item.show .nav-link:focus{color:#000;background-color:#d9d9d9}
.navbar .navbar-toggle{border-color:#d9d9d9}
.navbar .navbar-toggle:hover,.navbar .navbar-toggle:focus{background-color:#d9d9d9}
.navbar .navbar-toggle .navbar-toggler-icon{color:#ecf0f1}
.navbar .navbar-collapse,.navbar .navbar-form{border-color:#ecf0f1}
.navbar .navbar-link{color:#ecf0f1}
.navbar .navbar-link:hover{color:#000}
.bottom-border{border-bottom:1px solid #fff;}
.top-border{border-top:1px solid #fff;}
#dashboard-info{background:#<?php echo $config['bg_color']; ?>;padding:10px;color:#fff;min-width:100%;font-size:15px;text-shadow:1px 1px 0 rgba(0,0,0,0.4);border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px}
#dashboard-info h1{font-size:19px;text-align:center}
#grey-box{display:block;background:#<?php echo $config['bg_color']; ?>;color:#fff;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}
#grey-box .title{background:#<?php echo $config['title_color']; ?>;font-size:18px;font-weight:700;text-transform:uppercase;letter-spacing:1px;text-align:center;padding:5px 10px;width:100%;border-top:1px solid #<?php echo $config['bg_color']; ?>;margin:0 auto 10px;text-shadow:-1px 0 #<?php echo $config['bg_color']; ?>,0 1px #<?php echo $config['bg_color']; ?>,1px 0 #<?php echo $config['bg_color']; ?>,0 -1px #<?php echo $config['bg_color']; ?>;-webkit-border-top-left-radius: 4px;-webkit-border-top-right-radius: 4px;-moz-border-radius-topleft: 4px;-moz-border-radius-topright: 4px;border-top-left-radius: 4px;border-top-right-radius: 4px}
#grey-box .infobox{background:#fff;display:block;clear:both;padding:6px;width:100%;font-size:14px;color:#000;margin-bottom:15px;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}
#grey-box .content{padding:25px 30px;line-height:22px}
#grey-box .content h1{font-size:26px;font-weight:400;line-height:32px;text-align:center;margin-top:0;margin-bottom:5px}
#home-box{display:block;background:#<?php echo $config['bg_color']; ?>;color:#fff;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;}
#home-box .content{padding:25px 30px;line-height:22px}
#home-statistics{color:#fff;padding:20px 5px;text-align:center;font-size:18px;margin-left:-16px;margin-right:-16px;position:relative;z-index:1;-webkit-box-shadow:0 -3px 4px rgba(50,50,50,0.5),0 3px 4px rgba(50,50,50,0.5);-moz-box-shadow:0 -3px 4px rgba(50,50,50,0.5),0 3px 4px rgba(50,50,50,0.5);box-shadow:0 -3px 4px rgba(50,50,50,0.5),0 3px 4px rgba(50,50,50,0.5)}
#home-bottom-box{display:block;background:#<?php echo $config['bg_color']; ?>;color:#fff;-webkit-border-bottom-right-radius:3px;-webkit-border-bottom-left-radius:3px;-moz-border-radius-bottomright:3px;-moz-border-radius-bottomleft:3px;border-bottom-right-radius:3px;border-bottom-left-radius:3px}
#home-bottom-box .content{padding:25px 30px}
#home-bottom-box .content h2{font-size:26px;font-weight:400;line-height:32px;text-align:center;margin-top:0;margin-bottom:5px}
#home-box .content h1,#home-bottom-box .content h1{font-size:26px;font-weight:400;line-height:32px;text-align:center;margin-top:0;margin-bottom:5px}
#home-info-box{background:#<?php echo $config['bg_color']; ?>;color:#fff;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}
#home-info-box .content{padding:25px 30px}
#home-info-box .content h2{color:#fff;font-size:26px;font-weight:400;line-height:32px;text-align:center;margin-top:0;margin-bottom:5px}
#home-info-box .content hr{border:0;height:1px;margin:10px 0;background:-webkit-linear-gradient(left,rgba(255,255,255,0),rgba(255,255,255,0.75),rgba(255,255,255,0));background:-moz-linear-gradient(left,rgba(255,255,255,0),rgba(255,255,255,0.75),rgba(255,255,255,0));background:-ms-linear-gradient(left,rgba(255,255,255,0),rgba(255,255,255,0.75),rgba(255,255,255,0));background:-o-linear-gradient(left,rgba(255,255,255,0),rgba(255,255,255,0.75),rgba(255,255,255,0))}
.home-stats-block {
    background-color: #fff;border: 5px solid #<?php echo $config['bg_color']; ?>;
    -webkit-border-radius:40px;-moz-border-radius:40px;border-radius:40px;
    color: #<?php echo $config['bg_color']; ?>;margin: 0 0 15px 0;  box-shadow: 0px 2px 5px 0px #00000024;
}
.home-stats{padding:15px 5px 14px;text-align:center;font-size:14px;font-weight:500;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;}
.home-stats span{font-size:26px;font-weight:600}
.homebox{background:#6d6d6d;display:block;clear:both;border:1px solid #efefef;padding:25px 12px;width:98%;font-size:14px;color:#efefef;margin-top:20px;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px}
.homebox h2{color:#93c52f;margin:4px 2px 12px;font-size:22px;text-align:center;font-family:arial;text-shadow:0 1px 0 rgba(12,12,12,0.6)}
.animated {-webkit-animation-duration: 1s;-moz-animation-duration: 1s;-o-animation-duration: 1s;animation-duration: 1s;-webkit-animation-fill-mode: both;-moz-animation-fill-mode: both;-o-animation-fill-mode: both;animation-fill-mode: both}
@-webkit-keyframes shake {
	0%, 100% {-webkit-transform: translateX(0);}
	10%, 30%, 50%, 70%, 90% {-webkit-transform: translateX(-10px);}
	20%, 40%, 60%, 80% {-webkit-transform: translateX(10px);}
}
@-moz-keyframes shake {
	0%, 100% {-moz-transform: translateX(0);}
	10%, 30%, 50%, 70%, 90% {-moz-transform: translateX(-10px);}
	20%, 40%, 60%, 80% {-moz-transform: translateX(10px);}
}
@-o-keyframes shake {
	0%, 100% {-o-transform: translateX(0);}
	10%, 30%, 50%, 70%, 90% {-o-transform: translateX(-10px);}
	20%, 40%, 60%, 80% {-o-transform: translateX(10px);}
}
@keyframes shake {
	0%, 100% {transform: translateX(0);}
	10%, 30%, 50%, 70%, 90% {transform: translateX(-10px);}
	20%, 40%, 60%, 80% {transform: translateX(10px);}
}
.bg-dark {background-color: #001926!important;}
.shake {-webkit-animation-name: shake;-moz-animation-name: shake;-o-animation-name: shake;animation-name: shake}
.modal-login{color:#636363;}
.modal-login .modal-content{padding:18px 10px 6px;border:none;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px}
.modal-login .modal-header{border-bottom:none;padding:0;position:relative;justify-content:center}
.modal-login h4{text-align:center;font-size:26px}
.modal-login .form-group{position:relative}
.modal-login i{position:absolute;left:13px;top:11px;font-size:18px}
.modal-login .form-control{padding-left:40px}
.modal-login .form-control:focus{border-color:#00ce81}
.modal-login .form-control,.modal-login .btn{min-height:40px;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}
.modal-login .hint-text{text-align:center;padding-top:10px}
.modal-login .close{position:absolute;top:-5px;right:3px;}
.modal-login .btn{background:#00ce81;border:none;line-height:normal}
.modal-login .btn:hover,.modal-login .btn:focus{background:#00bf78}
.modal-login .modal-footer{background:#ecf0f1;border-color:#dee4e7;text-align:center;margin:0 -10px -6px;font-size:13px;justify-content:center;-webkit-border-bottom-right-radius:5px;-webkit-border-bottom-left-radius:5px;-moz-border-radius-bottomright:5px;-moz-border-radius-bottomleft:5px;border-bottom-right-radius:5px;border-bottom-left-radius:5px}
.modal-login .modal-footer a{color:#999}
.affiliate-url .form-group{width:100%;position:relative}
.affiliate-url i{position:absolute;left:13px;top:11px;font-size:18px}
.affiliate-url .form-control{padding-left:40px}
.affiliate-url .form-control:focus{border-color:#00ce81}
.static-bottom{right:0;bottom:0;left:0;position:relative;border-width:1px 1px 0}
.footer_copyright{text-decoration:none;color:#ecf0f1}
.footer_copyright:hover,.footer_copyright:focus{text-decoration:none;color:#d9d9d9}
.border-bottom{border-bottom:1px solid #e5e5e5}
.box-shadow{box-shadow:0 .25rem .75rem rgba(0,0,0,.1)}
.box-style{border:1px solid #ccc}
.copyright{font-size:12px}
.membership-option{background:#212529;color:#fff}
.membership-block{font-weight:300;margin:-10px 0 10px;padding:3px 2px;font-size:21px;color:#000;padding:8px 6px;display:inline-block;background:#efefef;border: 1px solid #ccc;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}
.membership-block b{font-weight:600}
.bitcoin-value{font-size:21px;font-weight:600}
#aff-block{margin:5px 0 20px;display:block;padding:8px 10px 5px;background-color:#fff;color:#0e6083;font-size:13px;text-align:left;border:1px solid #08546b;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px}
#aff-block .title{display:block;font-weight:700;text-align:center;font-size:18px;background-color:#08546b;color:#fff;margin:-23px auto 0;text-shadow:1px 1px 1px rgba(0,0,0,0.6);border-radius:6px;-moz-border-radius:6px;-webkit-border-radius:6px}
#aff-block .aff_block_p{margin-bottom:0;margin-top:10px;display:block;text-align:center;font-size:11px}
#aff-block .aff_content_bottom{font-size:14px;font-weight:700;text-align:center;margin-top:14px}
#aff-block .aff_block_p2{display:block;background-color:#bedee2;margin:9px 5px 0;padding:4px 0;text-align:center;color:#0d5675;font-size:18px;border:1px solid #84afba;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;text-decoration:none}
#aff-block .aff_block_p2 a{color:#0b516f}
#aff-block .aff_block_p2:hover{background-color:#c2e2e6;border-color:#93c2cf}
#aff-banner{padding:4px 10px;background-color:#fff;color:#0e6083;border:1px solid #08546b;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px}
.aff-banner-title{display:block;margin:0 auto;font-weight:700;text-align:center;font-size:18px;padding:1px;background-color:#08546b;color:#fff;text-shadow:1px 1px 1px rgba(0,0,0,0.6);border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px}
.winner_block{display:block;position:relative;text-align:left;float:left;}
.winner_block .inside{border:1px solid #b6b6b6;position:relative;z-index:1;margin-bottom:20px;text-shadow:0 1px 0 #fff;color:#77787b;background:#efefef;-webkit-box-shadow:inset 0 1px 0 0 rgba(255,255,255,0.5);-moz-box-shadow:inset 0 1px 0 0 rgba(255,255,255,0.5);box-shadow:inset 0 1px 0 0 rgba(255,255,255,0.5);border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px}
.winner_block .inside p,h1,h2,h3,h4{color:#77787b}
.winner_block .inside .winner{font-size:16px;background:#6dbd4c;padding:1px 4px 4px;text-shadow:0;color:#fff;margin:-1px -1px 10px;-webkit-border-top-left-radius: 4px;-webkit-border-top-right-radius: 4px;-moz-border-radius-topleft: 4px;-moz-border-radius-topright: 4px;border-top-left-radius: 4px;border-top-right-radius: 4px;}
.ribbon-green{background:#5bab3a;height:32px;right:8px;color:#fff;text-shadow:0 1px 0 #4e8636;margin:-1px 0 -10px;position:absolute;width:40px;padding-top:4px;font-size:18px;text-align:center}
.ribbon-green:after,.ribbon-green:before{border-top:15px solid #5bab3a;content:'';height:0;position:absolute;top:100%;width:0}
.ribbon-green:after{border-left:20px solid transparent;right:0}
.ribbon-green:before{border-right:20px solid transparent;left:0}
#loterry_stats{text-shadow:0 1px 1px rgba(0,0,0,0.85);font-size:25px;font-weight:700;line-height:39px;color:#fff;font-family:arial;padding:1px 30px;margin-bottom:10px;background:#506377;background:-moz-linear-gradient(top,#506377 0%,#<?php echo $config['bg_color']; ?> 100%);background:-webkit-linear-gradient(top,#506377 0%,#<?php echo $config['bg_color']; ?> 100%);background:linear-gradient(to bottom,#506377 0%,#<?php echo $config['bg_color']; ?> 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#506377',endColorstr='#<?php echo $config['bg_color']; ?>',GradientType=0);border:2px solid #<?php echo $config['bg_color']; ?>;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px}
#loterry_stats_header{display:table;text-shadow:0 1px 1px rgba(0,0,0,0.85);font-size:14px;line-height:19px;padding:5px 12px;color:#fff;font-family:arial;margin:0 auto;text-align:center;background:#<?php echo $config['bg_color']; ?>;-webkit-border-top-left-radius:5px;-webkit-border-top-right-radius:5px;-moz-border-radius-topleft:5px;-moz-border-radius-topright:5px;border-top-left-radius:5px;border-top-right-radius:5px}
.lottery_sidebar_link,.lottery_sidebar_link:hover{font-size:9px;font-weight:700;text-decoration:none;color:#fff}
.lotteryTitleBox{display:table;text-align:center;font-size:18px;font-weight:700;color:#fff;padding:6px 15px;background-color:#506377;margin:0 auto;border:2px solid #33433B;border-bottom:0;-webkit-border-top-left-radius:4px;-webkit-border-top-right-radius:4px;-moz-border-radius-topleft:4px;-moz-border-radius-topright:4px;border-top-left-radius:4px;border-top-right-radius:4px}
.lotteryCountdownBox{text-align:center;padding:15px 10px 0;background:#506377;background:-moz-linear-gradient(top,#506377 0%,#33433B 100%);background:-webkit-linear-gradient(top,#506377 0%,#33433B 100%);background:linear-gradient(to bottom,#506377 0%,#33433B 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#506377',endColorstr='#33433B',GradientType=0);margin:0 auto 25px;border:2px solid #33433B;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px}
.lotteryCountdownBox .timer .timer-wrapper{display:inline-block;padding: 10px 5px;width:100px}
.lotteryCountdownBox .timer .timer-wrapper .time{font-size:48px;font-weight:700;color:#fff}
.lotteryCountdownBox .timer .timer-wrapper .text{font-size:14px;color:#fff}
.lotteryTop {background-color:#<?php echo $config['bg_color']; ?>;padding:7px 10px 7px;color:#fff;-webkit-border-bottom-right-radius: 3px;-webkit-border-bottom-left-radius: 3px;-moz-border-radius-bottomright: 3px;-moz-border-radius-bottomleft: 3px;border-bottom-right-radius: 3px;border-bottom-left-radius: 3px;box-shadow: 0 1px 3px 0 rgba(0,0,0,.2), 0 1px 1px 0 rgba(0,0,0,.14), 0 2px 1px -1px rgba(0,0,0,.12);}
.sidebarLottery{text-align:center;background-color:rgba(0,0,0,0.6);margin:0 auto 5px;border:1px solid #999;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px}
.sidebarLottery .timer .timer-wrapper{display:inline-block;margin:0 9px}
.sidebarLottery .timer .timer-wrapper .time{font-size:28px;font-weight:700;color:#fff}
.sidebarLottery .timer .timer-wrapper .text{font-size:11px;color:#fff}
.marquee{float:right;width:80%;padding:0 0 5px;overflow:hidden}
.marquee ul li{display:inline-block;margin-right:25px}
.marquee ul li span{vertical-align:4px}
.marquee ul li span.user{font-weight:700}
.marquee ul li span.method{vertical-align:-2px}
#sidebar-block{background:#<?php echo $config['bg_color']; ?>;padding:5px 5px 0;width:100%;border:1px solid #<?php echo $config['bg_color']; ?>;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;}
#sidebar-block hr{border:0;height:1px;margin:10px 0;background:-webkit-linear-gradient(left,rgba(255,255,255,0),rgba(255,255,255,0.75),rgba(255,255,255,0));background:-moz-linear-gradient(left,rgba(255,255,255,0),rgba(255,255,255,0.75),rgba(255,255,255,0));background:-ms-linear-gradient(left,rgba(255,255,255,0),rgba(255,255,255,0.75),rgba(255,255,255,0));background:-o-linear-gradient(left,rgba(255,255,255,0),rgba(255,255,255,0.75),rgba(255,255,255,0))}
#sidebar-block .menu{padding:7px 2px}
#sidebar-block .content{padding:5px 3px;margin:4px 0 3px;color:#fff;font-size:12px;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}
#sidebar-block .inner{padding:8px 3px 1px;margin-bottom:5px;-webkit-border-bottom-right-radius:4px;-webkit-border-bottom-left-radius:4px;-moz-border-radius-bottomright:4px;-moz-border-radius-bottomleft:4px;border-bottom-right-radius:4px;border-bottom-left-radius:4px}
#sidebar-block .manager{display:block;margin:1px 2px 8px}
#sidebar-block .block{width:100%;background:#f8f9fa;border-radius:3px;padding: 2px 3px 5px;color:#505050}
#sidebar-block .block .data{width:100%;padding: 2px 3px 5px;font-size:14px;line-height:14px;text-decoration:none;color:#505050}
#sidebar-block .data{width:100%;padding: 2px 3px 5px;font-size:14px;line-height:14px;text-decoration:none;color:#fff}
#sidebar-block .level{width:100%;background:#f8f9fa;border-radius:3px;margin-top:5px;padding: 4px;font-size:14px;line-height:14px;text-decoration:none;color:#505050}
#sidebar-block .user{background:#f8f9fa;padding:5px 5px 1px;margin:-6px -6px 0;color:#fff;color:#000;-webkit-border-top-left-radius: 4px;-webkit-border-top-right-radius: 4px;-moz-border-radius-topleft: 4px;-moz-border-radius-topright: 4px;border-top-left-radius: 4px;border-top-right-radius: 4px;}
#sidebar-block .user .info{vertical-align:middle;font-size:16px;font-weight:700;text-shadow:2px 2px 2px rgba(255,255,255,0.7)}
#sidebar-block .user .info span{display:inline-block;vertical-align:middle;text-shadow:2px 2px 2px rgba(255,255,255,0.7)}
#sidebar-block .user .logout{font-size:11px;margin-top:-10px}
#sidebar-block .user .logout a{color:#505050}
#sidebar-block .title{background:#<?php echo $config['title_color']; ?>;margin:-5px -5px 2px;padding:6px 5px 5px;color:#fff;border-bottom:1px solid #ccc;font-weight:700}
#sidebar-footer{background:#fff!important;font-size: 11px;margin-top:-2px;padding:2px 5px;text-align: center;width:100%;border:1px solid #<?php echo $config['bg_color']; ?>;-webkit-border-bottom-right-radius:4px;-webkit-border-bottom-left-radius:4px;-moz-border-radius-bottomright:4px;-moz-border-radius-bottomleft:4px;border-bottom-right-radius:4px;border-bottom-left-radius:4px}
#sidebar-ads{background:#<?php echo $config['bg_color']; ?>;padding:2px 0;width:100%;border:1px solid #<?php echo $config['bg_color']; ?>;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;}
.sidebar-stats{margin-top:-2px;background:#fff;color:#<?php echo $config['bg_color']; ?>;padding:4px;text-align:center;font-size:18px;font-weight:500;border:2px solid #<?php echo $config['bg_color']; ?>;-webkit-border-bottom-right-radius: 4px;-webkit-border-bottom-left-radius: 4px;-moz-border-radius-bottomright: 4px;-moz-border-radius-bottomleft: 4px;border-bottom-right-radius: 4px;border-bottom-left-radius: 4px;}
.sidebar-stats .badge {background:#113449;color:#fff;}
.sidebar-stats span{font-size:16px;font-weight:600}
.price_block{display:block;background:#f8f9fa;padding:10px;margin:10px 0;font-size:14px;color:#000;vertical-align:middle;border:1px solid #9e9e9e;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}
.price_block .text{display:inline-block;margin-top:5px}
.pay_block{display:inline-block;margin:0;float:right}
.box{background:#f8f9fa;padding:10px;color:#000;margin-bottom:10px;border:1px solid #<?php echo $config['title_color']; ?>;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}
#countdown{font-size:16px;padding:5px 5px 13px}
.footer{margin-top:60px}
.btn-signup{font-weight: 600}
.hidden{display:none}
hr.global{border:0;height:1px;margin:10px 0;background:-webkit-linear-gradient(left,rgba(255,255,255,0),rgba(255,255,255,0.75),rgba(255,255,255,0));background:-moz-linear-gradient(left,rgba(255,255,255,0),rgba(255,255,255,0.75),rgba(255,255,255,0));background:-ms-linear-gradient(left,rgba(255,255,255,0),rgba(255,255,255,0.75),rgba(255,255,255,0));background:-o-linear-gradient(left,rgba(255,255,255,0),rgba(255,255,255,0.75),rgba(255,255,255,0))}
.borderless td,.borderless th{border:none}
#luckyNumber{display:block;min-height:60px;font-size:70px;text-align:center;margin:10px 0 2px;color:#4d63bc}
pre{background:#f8f9fa;padding:6px;border:1px solid #ccc;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}
.website_block{background:#f5f5f5;overflow:hidden;position:relative;float:left;width:230px;border:2px solid #<?php echo $config['title_color']; ?>;margin:0 0 6px 7px;padding:3px;text-align:center;color:#666;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}
.website_block .website_title{background:#<?php echo $config['title_color']; ?>;color:#fff;padding:3px 2px 6px;display:block;margin:-3px -3px 3px}
.website_block .website_bottom{background:#08546b;color:#fff;padding:1px 3px;display:block;font-size:12px;text-align:right;margin:3px -2px -3px;-webkit-border-bottom-right-radius:4px;-webkit-border-bottom-left-radius:4px;-moz-border-radius-bottomright:4px;-moz-border-radius-bottomleft:4px;border-bottom-right-radius:4px;border-bottom-left-radius:4px}
.website_block .description{margin:10px auto 0;display:block;height:100px;padding:2px 3px;background:#fff;color:#444;font-size:13px;border:1px solid #ccc;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px}
.website_block .reward{margin:10px auto;display:inline-block;width:126px;padding:2px 3px;background:#fff;color:#444;font-size:13px;border:1px solid #ccc;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px}
.website_block .reward span{color:#4c7d00}
.website_block .time{margin:10px auto;display:inline-block;width:62px;padding:2px 3px;background:#fff;color:#444;font-size:13px;border:1px solid #ccc;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px}
.notify-icon{padding:5px;width:25px}
.notify-icon-fa{line-height:46px}
.notification{display:inline-block;position:relative;padding:8px 12px;background:#3498db;border-radius:3px;font-size:20px;box-shadow:0 0 10px rgba(0,0,0,0.2);text-decoration:none}
.notification:hover{background:#2d87c3;text-decoration:none}
.notification::before,.notification::after{color:#fff;text-shadow:0 1px 3px rgba(0,0,0,0.3)}
.notification::before{display:block;content:"\f0f3";font-family:"Font Awesome 5 Free";transform-origin:top center}
.notification::after{font-family:Arial;font-size:11px;font-weight:700;position:absolute;top:-15px;right:-15px;padding:5px 8px;line-height:100%;border:2px #fff solid;border-radius:60px;background:#3498db;opacity:0;content:attr(data-count);opacity:0;-webkit-transform:scale(0.5);transform:scale(0.5);transition:transform,opacity;transition-duration:.3s;transition-timing-function:ease-out}
.notification.notify::before{-webkit-animation:ring 1.5s ease;animation:ring 1.5s ease}
.notification.show-count::after{-webkit-transform:scale(1);transform:scale(1);opacity:1}
.tagline{border-bottom:1px solid #D6D6D6;margin-bottom:25px;text-align:center}
.tagline span{display:inline-block;font-size:12px;color:#666;font-weight:bold;background:none repeat scroll 0% 0% #FFF;padding:3px 5px 4px;position:relative;top:14px;border:1px solid #D6D6D6;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}
.blog_comment{margin:10px auto;background:#efefef;border:solid 1px #<?php echo $config['title_color']; ?>;-moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px;color:#696969}
.com_title{width:100%;text-align:center;font-size:12px;color:#fff;padding:5px;background:#<?php echo $config['title_color']; ?>;border-bottom:solid 1px #1d1d1d;-webkit-border-top-left-radius:3px;-webkit-border-top-right-radius:3px; -moz-border-radius-topleft:3px;-moz-border-radius-topright:3px;border-top-left-radius:3px;border-top-right-radius:3px}
.comments_wrap{overflow:hidden;margin:5px auto;background:#efefef;border:1px solid #<?php echo $config['title_color']; ?>;border-radius:5px}
.comments_wrap .content_text{ padding:10px 5px;overflow:hidden;text-align:justify;color:#464646}
.comments_wrap .content_top{padding:1px 5px 4px;background:#<?php echo $config['title_color']; ?>;color:#fff;border-bottom:1px dotted #fff}
@-webkit-keyframes ring{0%{-webkit-transform:rotate(35deg)}12.5%{-webkit-transform:rotate(-30deg)}25%{-webkit-transform:rotate(25deg)}37.5%{-webkit-transform:rotate(-20deg)}50%{-webkit-transform:rotate(15deg)}62.5%{-webkit-transform:rotate(-10deg)}75%{-webkit-transform:rotate(5deg)}100%{-webkit-transform:rotate(0)}}@keyframes ring{0%{-webkit-transform:rotate(35deg);transform:rotate(35deg)}12.5%{-webkit-transform:rotate(-30deg);transform:rotate(-30deg)}25%{-webkit-transform:rotate(25deg);transform:rotate(25deg)}37.5%{-webkit-transform:rotate(-20deg);transform:rotate(-20deg)}50%{-webkit-transform:rotate(15deg);transform:rotate(15deg)}62.5%{-webkit-transform:rotate(-10deg);transform:rotate(-10deg)}75%{-webkit-transform:rotate(5deg);transform:rotate(5deg)}100%{-webkit-transform:rotate(0);transform:rotate(0)}}
.modal-close {margin: 0;position: absolute;top: -10px;right: -10px;width: 25px;height: 25px;background-color: #333;color: #fff;border: 0;border-radius: 50%;font-size: 13px;z-index: 11;}
.dropdown:hover>.dropdown-menu {display: block;}
.no-space {margin-right: 0;padding-left: 0;}
ad,l{font-size:10px;padding:0 4px;border-radius:4px;margin-left:5px;height:19px;line-height:17px;display:inline-block;font-weight:600}ad{border:1px solid #ffa097;color:#f4786b}l{border:1px solid #757575;color:#b6b6b6}l::before{content:"Lvl "}l::after{content:attr(class)}ad::before{content:"Admin"}
.faucet-form label{margin-bottom:10px;font-weight:500;font-size:14px;color:#fff}
.faucet-form label span{font-weight:400;font-size:12px}
.faucet-form label span span{color:#007bff;cursor:pointer;text-decoration:underline}
.faucet-form label #available-area{display:none}
.faucet-form .title-s{display:block;font-weight:500;font-size:14px;color:#fff;float:left}
.faucet-form .title-s .icon{color:#e44436;margin-right:10px}
.faucet-form .info-s{display:block;font-weight:400;font-size:14px;color:#e44436;float:left;margin-left:35px}
.faucet-form .desc-s{font-weight:400;font-size:14px;color:#7f8e9d;line-height:1.7em}
.faucet-form .cwrapper{display:block;position:relative;padding-left:35px;margin-bottom:12px;cursor:pointer;font-size:16px;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;font-family:"Poppins", "Helvetica Neue", Helvetica, Arial, sans-serif;font-weight:400;padding-top:5px;color:#17a2b8}
.faucet-form .rwrapper{display:block;position:relative;padding-left:35px;margin-bottom:12px;cursor:pointer;font-size:16px;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;font-family:"Poppins", "Helvetica Neue", Helvetica, Arial, sans-serif;font-weight:400;padding-top:5px;color:#17a2b8;text-transform:capitalize}
.faucet-form .item p{position:relative;display:inline-block;font-weight:400;font-size:14px;margin:-5px 0;color:#fff}
.faucet-form .item p span{font-weight:500}
.claim-area{position:relative;background:#fff;margin:5px auto;width:100%;height:auto;padding:30px;-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;-o-border-radius:3px;border-radius:3px;border:1px solid #edf2f9}
.claim-area .mid-300{max-width:300px;margin:15px auto}
.claim-area .title-faucet{font-weight:500;font-size:16px;color:#2a2a2a;text-align:center}
.claim-area .desc{margin-top:30px;font-weight:400;font-size:14px;color:#7f8e9d;line-height:1.7em;text-align:center}
.claim-area label{margin-bottom:10px;font-weight:500;font-size:14px;color:#7f8e9d}
.claim-area label span{font-weight:400;font-size:12px}
.claim-area .next-payout{display:block;text-align:center;margin-top:50px}
.claim-area .next-payout p{display:block;font-weight:500;font-size:16px;color:#2a2a2a;text-align:center}
.claim-area .next-payout p span{background:#17a2b8;-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;-o-border-radius:3px;border-radius:3px;padding:5px 10px;color:#fff}
.claim-area .progress{margin:20px auto 0 auto;max-width:500px;-webkit-transition:all .4s linear;transition:all .4s linear}
.claim-area .progress div{-webkit-transition:all .4s linear;transition:all .4s linear}
.claim-area .warning{display:block;font-weight:500;font-size:16px;color:#e44436;text-align:center;margin-top:30px}
.claim-area .content-box{float:left;text-align:left}
.claim-area .content-box .head{font-weight:300;font-size:16px;color:#72849a}
.claim-area .content-box .head span{text-transform:uppercase}
.claim-area .content-box .amount{font-weight:500;font-size:24px;margin-top:8px;color:#2a2a2a}
.claim-area .icon-area{height:50px;width:50px;-webkit-border-radius:40px;-moz-border-radius:40px;-ms-border-radius:40px;-o-border-radius:40px;border-radius:40px;float:right}
.claim-area .icon-area .icon{display:block;margin:15px auto 0 auto;font-size:20px;text-align:center}
.claim-area .icon-area.green{background:#e5f9f6}
.claim-area .icon-area.green .icon{color:#00c9a7}
.currencies .item{background:#fff;color:#222;border-radius:4px;padding:5px 2px 1px;margin:30px 0 3px;}
.currencies .item img:hover{transform:scale(1.1)}
.currencies .item img{margin-top:-35px;height:50px;}
#faq .card{margin-bottom:0;border:0}
#faq .card .card-header{border:0;-webkit-box-shadow:0 0 20px 0 rgba(213,213,213,.5);box-shadow:0 0 20px 0 rgba(213,213,213,.5);border-radius:2px;padding:0}
#faq .card .card-body{color:#222}
#faq .card .card-header .btn-header-link{display:block;text-align:left;background:#ffe472;color:#222;padding:20px;font-weight:600}
#faq .card .card-header .btn-header-link:after{content:"\f107";font-family:'Font Awesome 5 Free';font-weight:900;float:right}
#faq .card .card-header .btn-header-link.collapsed{background:#e6cd66;color:#222}
#faq .card .card-header .btn-header-link.collapsed:after{content:"\f106"}
#faq .card .collapsing{background:#ffe472;line-height:30px}
#faq .card .collapse{border:0}
#faq .card .collapse.show{background:#ffe472;line-height:30px;color:#222}
.odometer{font-size:40px;margin-bottom:27px;color:#222}
.odometer.odometer-auto-theme,.odometer.odometer-theme-digital{display:inline-block;vertical-align:middle;position:relative}
.odometer.odometer-auto-theme .odometer-digit,.odometer.odometer-theme-digital .odometer-digit{display:inline-block;vertical-align:middle;position:relative}
.odometer.odometer-auto-theme .odometer-digit .odometer-digit-spacer,.odometer.odometer-theme-digital .odometer-digit .odometer-digit-spacer{display:inline-block;vertical-align:middle;visibility:hidden}
.odometer.odometer-auto-theme .odometer-digit .odometer-digit-inner,.odometer.odometer-theme-digital .odometer-digit .odometer-digit-inner{text-align:left;display:block;position:absolute;top:0;left:0;right:0;bottom:0;overflow:hidden}
.odometer.odometer-auto-theme .odometer-digit .odometer-ribbon,.odometer.odometer-theme-digital .odometer-digit .odometer-ribbon{display:block}
.odometer.odometer-auto-theme .odometer-digit .odometer-ribbon-inner,.odometer.odometer-theme-digital .odometer-digit .odometer-ribbon-inner{display:block;-webkit-backface-visibility:hidden}
.odometer.odometer-auto-theme .odometer-digit .odometer-value,.odometer.odometer-theme-digital .odometer-digit .odometer-value{display:block;-webkit-transform:translateZ(0)}
.odometer.odometer-auto-theme .odometer-digit .odometer-value.odometer-last-value,.odometer.odometer-theme-digital .odometer-digit .odometer-value.odometer-last-value{position:absolute}
.odometer.odometer-auto-theme.odometer-animating-up .odometer-ribbon-inner,.odometer.odometer-theme-digital.odometer-animating-up .odometer-ribbon-inner{-webkit-transition:-webkit-transform 2s;-moz-transition:-moz-transform 2s;-ms-transition:-ms-transform 2s;-o-transition:-o-transform 2s;transition:transform 2s}
.odometer.odometer-auto-theme.odometer-animating-up.odometer-animating .odometer-ribbon-inner,.odometer.odometer-theme-digital.odometer-animating-up.odometer-animating .odometer-ribbon-inner{-webkit-transform:translateY(-100%);-moz-transform:translateY(-100%);-ms-transform:translateY(-100%);-o-transform:translateY(-100%);transform:translateY(-100%)}
.odometer.odometer-auto-theme.odometer-animating-down .odometer-ribbon-inner,.odometer.odometer-theme-digital.odometer-animating-down .odometer-ribbon-inner{-webkit-transform:translateY(-100%);-moz-transform:translateY(-100%);-ms-transform:translateY(-100%);-o-transform:translateY(-100%);transform:translateY(-100%)}
.odometer.odometer-auto-theme.odometer-animating-down.odometer-animating .odometer-ribbon-inner,.odometer.odometer-theme-digital.odometer-animating-down.odometer-animating .odometer-ribbon-inner{-webkit-transition:-webkit-transform 2s;-moz-transition:-moz-transform 2s;-ms-transition:-ms-transform 2s;-o-transition:-o-transform 2s;transition:transform 2s;-webkit-transform:translateY(0);-moz-transform:translateY(0);-ms-transform:translateY(0);-o-transform:translateY(0);transform:translateY(0)}
.odometer.odometer-auto-theme,.odometer.odometer-theme-digital{background-size:100%;font-family:Wallpoet,monospace;padding:0 .2em;line-height:1.1em}
.odometer.odometer-auto-theme .odometer-digit+.odometer-digit,.odometer.odometer-theme-digital .odometer-digit+.odometer-digit{margin-left:.1em}
#coinflip{position:relative;margin:0 auto;width:150px;height:150px;cursor:pointer}#coinflip div{width:100%;height:100%;-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;-webkit-box-shadow:inset 0 0 45px rgba(255,255,255,.3),0 12px 20px -10px rgba(0,0,0,.4);-moz-box-shadow:inset 0 0 45px rgba(255,255,255,.3),0 12px 20px -10px rgba(0,0,0,.4);box-shadow:inset 0 0 45px rgba(255,255,255,.3),0 12px 20px -10px rgba(0,0,0,.4)}#bitcoin{background:url(images/flip/bitcoin.png) no-repeat center;background-size:contain;height:200px}#ethereum{background:url(images/flip/ethereum.png) no-repeat center;background-size:contain;height:200px}#coinflip{transition:-webkit-transform 1s ease-in;-webkit-transform-style:preserve-3d}#coinflip div{position:absolute;-webkit-backface-visibility:hidden}#bitcoin{z-index:100}#ethereum{-webkit-transform:rotateY(-180deg)}#coinflip.heads{-webkit-animation:flipHeads 3s cubic-bezier(.39,.96,1,1) forwards;-moz-animation:flipHeads 3s cubic-bezier(.39,.96,1,1) forwards;-o-animation:flipHeads 3s cubic-bezier(.39,.96,1,1) forwards;animation:flipHeads 3s cubic-bezier(.39,.96,1,1) forwards}#coinflip.tails{-webkit-animation:flipTails 3s cubic-bezier(.39,.96,1,1) forwards;-moz-animation:flipTails 3s cubic-bezier(.39,.96,1,1) forwards;-o-animation:flipTails 3s cubic-bezier(.39,.96,1,1) forwards;animation:flipTails 3s cubic-bezier(.39,.96,1,1) forwards}#coinflip.wait{-webkit-animation:flipWait 300s cubic-bezier(.39,.96,1,1) forwards;-moz-animation:flipWait 300s cubic-bezier(.39,.96,1,1) forwards;-o-animation:flipWait 300s cubic-bezier(.39,.96,1,1) forwards;animation:flipWait 300s cubic-bezier(.39,.96,1,1) forwards}@-webkit-keyframes flipWait{from{-webkit-transform:rotateY(0);-moz-transform:rotateY(0);transform:rotateY(0)}to{-webkit-transform:rotateY(180000deg);-moz-transform:rotateY(180000deg);transform:rotateY(180000deg)}}@-webkit-keyframes flipHeads{from{-webkit-transform:rotateY(0);-moz-transform:rotateY(0);transform:rotateY(0)}to{-webkit-transform:rotateY(1800deg);-moz-transform:rotateY(1800deg);transform:rotateY(1800deg)}}@-webkit-keyframes flipTails{from{-webkit-transform:rotateY(0);-moz-transform:rotateY(0);transform:rotateY(0)}to{-webkit-transform:rotateY(1980deg);-moz-transform:rotateY(1980deg);transform:rotateY(1980deg)}}
@media screen and (min-width:100px) and (max-width:400px){.scratch-ticket{transform:scale(.5);-webkit-transform:scale(.5);-ms-transform:scale(.5);transform-origin:0 50%;-webkit-transform-origin:0 50%;-ms-transform-origin:0 50%}}@media screen and (min-width:400px) and (max-width:500px){.scratch-ticket{transform:scale(.65);-webkit-transform:scale(.65);-ms-transform:scale(.65);transform-origin:0 50%;-webkit-transform-origin:0 50%;-ms-transform-origin:0 50%}}@media screen and (min-width:500px) and (max-width:600px){.scratch-ticket{transform:scale(.75);-webkit-transform:scale(.75);-ms-transform:scale(.75);transform-origin:0 50%;-webkit-transform-origin:0 50%;-ms-transform-origin:0 50%}}@media screen and (min-width:600px) and (max-width:770px){.scratch-ticket{transform:scale(.8);-webkit-transform:scale(.8);-ms-transform:scale(.8);transform-origin:0 50%;-webkit-transform-origin:0 50%;-ms-transform-origin:0 50%}}@media screen and (min-width:770px) and (max-width:990px){.scratch-ticket{transform:scale(.6);-webkit-transform:scale(.6);-ms-transform:scale(.6);transform-origin:0 50%;-webkit-transform-origin:0 50%;-ms-transform-origin:0 50%}}