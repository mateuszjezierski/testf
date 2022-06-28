<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$faqs = $db->QueryFetchArrayAll("SELECT question,answer FROM `faq` ORDER BY id ASC");
?> 
 <main role="main" class="container">
      <div class="row">
		<?php 
			if($is_online) {
				require(BASE_PATH.'/template/'.$config['theme'].'/common/sidebar.php');
			}
		?>
		<div class="<?=($is_online ? 'col-xl-9 col-lg-8 col-md-7' : 'col-12')?>">
			<div class="my-3 p-3 bg-white rounded box-shadow box-style">
				<div id="grey-box">
					<div class="title">
						<?=$lang['l_29']?>
					</div>
					<div class="content">
						<?php
							if(count($faqs) == 0){
								echo '<div class="alert alert-info" role="alert">'.$lang['l_121'].'</div>';
							}
						?>
						<div class="accordion" id="faq">
						  <?php
							$j = 0;
							foreach($faqs as $faq){
								$j++;
						  ?>
							<div class="card">
								<div class="card-header" id="faqhead<?=$j?>">
									<a href="#" class="btn btn-header-link<?=($j > 1 ? ' collapsed' : '')?>" data-toggle="collapse" data-target="#faq<?=$j?>" aria-expanded="true" aria-controls="faq<?=$j?>"><?=$faq['question']?></a>
								</div>
								<div id="faq<?=$j?>" class="collapse<?=($j > 1 ? '' : ' show')?>" aria-labelledby="faqhead<?=$j?>" data-parent="#faq">
									<div class="card-body">
										<?=BBCode(nl2br($faq['answer']))?>
									</div>
								</div>
							</div>
						  <?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	  </div>
    </main>