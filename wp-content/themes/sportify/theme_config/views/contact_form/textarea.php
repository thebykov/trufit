<?php if(!empty($label)) : ?>
	<label class="input-place-name"><?php echo $label ?></label>
<?php endif; ?>
<div class="input-cover<?php echo $location === "contact_page" ? " input-cover-text" :""?>">
	<textarea 
		name='<?php echo esc_attr($name)?>'
		placeholder='<?php echo esc_attr($placeholder)?>' 
		<?php if($required) echo 'data-parsley-required="true"'; ?>
		class="contact-form-area"
		></textarea>
</div>