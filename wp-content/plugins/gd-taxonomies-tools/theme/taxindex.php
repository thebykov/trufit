<?php
/**
 * The template for displaying Taxonomy Terms Index page.
 */

get_header(); ?>

<?php

    $taxonomy = get_taxonomy(get_query_var('taxindex'));
    $post_type = get_post_type_object(get_query_var('post_type'));
    $current_page = get_query_var('paged') == '' ? 1 : intval(get_query_var('paged'));

    $title = $taxonomy->label.' Index';

    $args = array();

    if (!is_null($post_type)) {
        $title.= ' for '.$post_type->label;

        $args['post_types'] = $post_type->name;
    }

?>

    <h1 class="archive-title"><?php echo $title; ?></h1>

    <ul>
    <?php

    $terms = gdtt_get_terms($taxonomy->name, $args);

    foreach ($terms as $term) {
        $link = is_null($post_type) ? get_term_link($term) : gdtt_get_intersection_link($post_type->name, $taxonomy->name, $term);

        echo '<li><a href="'.$link.'">'.$term->name.'</a></li>';
    }

    ?>
    </ul>

<?php get_sidebar(); ?>
<?php get_footer(); ?>