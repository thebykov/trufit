<?php if(isset($label)) : ?>
	<label>
<?php endif; ?>
<select 
	name="<?php echo esc_attr($name)?>"
	<?php if($required) echo 'data-parsley-required="true" data-trigger="change"'; ?>
	>
	<?php 
	$options = explode(',', $select_options);
	if(isset($placeholder)) echo '<option value="">'.$placeholder.'</option>';
	if(!empty($options)) :
		foreach ($options as $value) : ?>
			<option value="<?php echo $value?>"><?php echo $value ?></option>
		<?php endforeach ?>
	<?php else: ?>
		<option value=""><?php _e('No Options Inserted','sevenfold') ?></option>
	<?php endif; ?>
</select>
<?php if(isset($label)) : echo $label?>
	</label>
<?php endif; ?>