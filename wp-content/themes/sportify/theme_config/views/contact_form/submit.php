<input 
	type="submit"
	value="<?php echo isset($label) && $label !== '' ? $label : 'Send' ?>"
	class="<?php echo $location === "contact_page" ? "form-send button-5 d-text-c-h d-border-c-h" : "send-form d-text-c d-border-c d-bg-c-h"?>"
	data-sending='<?php _e('Sending Message','sportify') ?>'
	data-sent='<?php _e('Message Successfully Sent','sportify') ?>'
	>