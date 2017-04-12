<?php if(!empty($label)) : ?>
	<label class="input-place-name"><?php echo $label ?></label>
<?php endif; ?>
<div class="input-cover contact-line">
	<input 
		type='text' 
		name='<?php echo esc_attr($name)?>' 
		placeholder='<?php echo esc_attr($placeholder)?>' 
		value=''
		<?php if($required) echo 'data-parsley-required="true"'; ?>
		class="contact-form-line"
		>
</div>