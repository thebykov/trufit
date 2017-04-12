<?php /* Template: Profile Tab */?>

<div class="um <?php echo $this->get_class($mode); ?> um-<?php echo $form_id; ?>">

	<div class="um-form">

		<?php

$edit = (isset($_GET['um_action']) && $_GET['um_action'] == 'edit');

if ($edit && !isset($ultimatemember->user->cannot_edit)) {
	echo '<form method="post" action="">';
}

um_fetch_user(um_profile_id());

$nav    = $ultimatemember->profile->active_tab;
$subnav = (get_query_var('subnav')) ? get_query_var('subnav') : 'default';

echo '<div class="um-profile-body">';

$mode = $args['mode'];

do_action("um_before_form", $args);

do_action("um_before_{$mode}_fields", $args);

do_action("um_main_{$mode}_fields", $args);

do_action("um_after_form_fields", $args);

// need to add the buttons manually because of UM hardcoded cancel link
if ($edit && !isset($ultimatemember->user->cannot_edit)) {
	?>

<div class="um-col-alt">
	<div class="um-left um-half">
		<input type="submit" value="<?php echo $args['primary_btn_word']; ?>" class="um-button" />
	</div>

	<?php if (isset($args['secondary_btn']) && $args['secondary_btn'] != 0) {?>

	<div class="um-right um-half">
		<a href="<?php echo remove_query_arg('um_action', $_SERVER['REQUEST_URI']); ?>" class="um-button um-alt"><?php echo $args['secondary_btn_word']; ?></a>
	</div>

	<?php }?>

	<div class="um-clear"></div>
</div>

<?php

}

do_action("um_after_form", $args);

echo "</div>";

if (!$edit && !isset($ultimatemember->user->cannot_edit)) {

	$edit_link = add_query_arg('um_action', 'edit', $_SERVER['REQUEST_URI']);

	?>
	<div class="um-left um-half">
		<a href="<?php echo $edit_link; ?>" class="um-button">Edit</a>
	</div>
	<div class="um-clear"></div>

	<?php

}

if ($edit && !isset($ultimatemember->user->cannot_edit)) {

	echo "</form>";
}

?>

	</div>
</div>