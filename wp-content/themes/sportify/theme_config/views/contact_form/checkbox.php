<?php if(isset($label)) : ?>
	<label>
<?php endif; ?>
<input
	type='checkbox'
	name='<?php echo esc_attr($name)?>'
	value='<?php echo esc_attr($name)?>'
	<?php if(isset($required)) echo 'data-parsley-required="true"'; ?>
	>
<?php if(isset($label)) : echo $label?>
	</label>
<?php endif; ?>