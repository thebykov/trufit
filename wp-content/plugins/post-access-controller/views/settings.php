<div class="wrap">
	<div id="poststuff">
		<form action="options.php" method="post">
			<?php

				// print_r( wp_get_current_user()->roles );
				 //    $role_query = '';
					// foreach( wp_get_current_user()->roles as $idx => $role ):
					// 	$role_query .= "SELECT ".$role." FROM dual";
					// 	if( $idx + 1 < count( wp_get_current_user()->roles ) ): 
					// 		$role_query .= " UNION ALL ";
					// 	endif;
					// endforeach;
					// echo $role_query;
				settings_fields( 'postaccesscontroller-settings-group' );
				do_settings_sections( 'postaccesscontroller-settings-group' );

				//header
				echo $data['header_text'];
				
				//build the group form
			?>
			<div id="namediv" class="stuffbox">
				<h3><label for="name">Overall Options</label></h3>
				<div class="inside">
					<table class="form-table editcomment">
						<tbody>
							<?php
								foreach( $overall['fields'] as $field ):
									echo $field;
								endforeach;
							?>						
						</tbody>
					</table>
					<br>
				</div>
			</div>
			<div id="namediv" class="stuffbox">
				<h3><label for="name">Post Maintenance Page Options</label></h3>
				<div class="inside">
					<table class="form-table editcomment">
						<tbody>
							<?php
								foreach( $post_maint['fields'] as $field ):
									echo $field;
								endforeach;
							?>						
						</tbody>
					</table>
					<br>
				</div>
			</div>
			<div class="form-actions">
				<?php submit_button(); ?>
				<!-- <button type="submit" class="button button-large button-primary">Save</button> -->
			</div>
		</form>
	</div><!-- /#poststuff -->
</div><!-- /.wrap -->
<?php
	
/* End of file */
/* Location: ./post-access-controller/options.php */