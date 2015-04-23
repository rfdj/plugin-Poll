<?php

$pollPath = get_option('poll_page_path');
if(!$pollPath) {
    $pollPath = 'poll';
}
queue_css_file('form');

?>
<script type="text/javascript">
// <![CDATA[

jQuery('#poll').hide();


function enablePollAjaxForm(url){
    jQuery(document).ready(function(){
        
		var form = jQuery('#poll-form');
		var duration = 200;
		jQuery('#poll-usage-other').hide();
		jQuery('#poll').delay(500).slideDown(600);
		

        // Show/hide the Other input
		jQuery('#poll-usage').on("change", function(){
			if(jQuery(this).val() == 7){
				jQuery('#poll-usage-other').show(200).focus();
			}else{
				jQuery('#poll-usage-other').hide(200);				
			}
		});
		

        // When the form is submitted, prevent it from redirecting
        form.submit(function (e){
			
            var value = form.serializeArray();
            //var arr = form.serializeArray();
			
			value[3] = {//
				'name' : 'page',
				'value' : document.URL
			};
			
			console.log(value);
			<!--fields can't be blank-->
			var q_usage = jQuery('#poll-usage').val();
			var q_location = jQuery('#poll-location').val();
			
			
			if (q_usage !== '' && q_location !== '') {
			
				jQuery.post(url, value, function() {
					jQuery('#poll-content').hide();
					jQuery('#poll-thankyou').show();
					setTimeout(function(){
						jQuery('#poll').slideUp();
					}, 2000);
			   });
			   
			}else{
				jQuery('#poll-warning').text('Vul alle velden in, alstublieft.').show(200);
			}
				
			e.preventDefault();
        });
    });
}


enablePollAjaxForm('<?php echo url($pollPath.'/submit'); ?>');
// ]]>
</script>


<style>
#poll{
	position: relative;
	top: 26px;
	width: 100%;
	padding: 10px;
	background-color: #DDD;
	line-height: 1.2em;
	box-sizing: border-box;
}
#poll p, #poll h4{
	margin: 0;
}

.poll-question{
	display: inline-block;
	margin: 10px 20px 0 0;
}

#poll select{
	max-width: 215px;
	height: 30px;
}
#poll-usage-other{
	max-width: 180px;
	height: 30px;
}
.poll-question label{
	padding: 8px 12px;
}
.poll-question label:first-of-type{
	margin-right: 30px;
}
.poll-question label:hover{
	background-color: #EEE;
}

#poll-submit{
	color: #EEE;
}
#poll-submit:hover{
	color: #FFF;
}
@media screen and (min-width: 768px){

}
</style>
<div id="poll">

	<div id="poll-content">
	
		<h4 id="poll-instructions">
			Door deze twee vragen te beantwoorden, helpt u Verhalenbank.nl te verbeteren:
		</h4>
		<?php echo flash(); ?>
		
		<h4 id="poll-warning"></h4>
		
			<form id="poll-form" action="<?php echo url($pollPath.'/submit'); ?>" method="post" name="poll">
				<div class="poll-question">
					<h4>1.</h4>
					<p>Waarvoor wilt u de informatie van de Verhalenbank gebruiken?</p>
					
					<select name="poll-usage" id="poll-usage">
						<option value="">Selecteer een optie...</option>
						<option value="1">Persoonlijk gebruik</option>
						<option value="2">Wetenschappelijk onderzoek</option>
						<option value="3">Onderwijs</option>
						<option value="4">Schoolopdracht</option>
						<option value="5">Journalisme</option>
						<option value="6">Vertelrepertoire</option>
						<option value="7">Anders:</option>
					</select>
					
					<input type="text" id="poll-usage-other" name="poll-usage-other" value="" placeholder="anders...">		
				</div>
			
				<div class="poll-question">
					<h4>2.</h4>
					<p>Zou u in de toekomst gebruik willen maken van een interactieve kaart om verhalen te ontdekken?</p>
					
					<label for="poll-location-yes"><input name="poll-location" id="poll-location-yes" type="radio" value="1">Ja</input></label>
					<label for="poll-location-no"><input name="poll-location" id="poll-location-no" type="radio" value="0">Nee</input></label>
				</div>
				
				<input type="hidden" id="poll-page" name="page" value="<?=$_SERVER['REQUEST_URI'];?>">
				<input type="hidden" id="poll-js" name="js" value="false">
				<input type="submit" id="poll-submit" name="submit" value="Verzend">
			
			</form>
	
	</div>
	
	<div id="poll-thankyou" style="display:none">Dank voor uw hulp.</div>
	
</div>
<script>
document.getElementById('poll-js').value = true;
</script>