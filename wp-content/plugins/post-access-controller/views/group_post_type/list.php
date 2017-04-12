<div class="wrap">
	<?php

		//localize everything down one level from the $data array
		extract( $data );

		//header
		echo '<h2>'.$header_text.$add_new.'</h2>';

		$this->ui->display_admin_notices();

		echo '<form id="events-filter" method="get">';
		echo '<input type="hidden" name="page" value="'.$_REQUEST['page'].'" />';
		$list_table->display();
		echo '</form>';

	?>
</div><!-- /.wrap -->
<?php

/* End of file */
/* Location: ./post-access-controller/groups-list.php */