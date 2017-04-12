<div class="gdtt-about-fields">
    <?php

    global $gdtt_fields;

    $c1 = $c2 = 0;
    $_1 = $_2 = '';
    $gdtt_fields->load_admin();
    $list = $gdtt_fields->get_fields_list();

    foreach ($list as $group) {
        $render = '<h3>'.$group['title'].'</h3><ul>';
        foreach ($group['values'] as $code => $label) {
            $render.= '<li><strong>'.$label.'</strong> ('.$code.')</li>';
        }
        $render.= '</ul>';

        if ($c1 > $c2) {
            $_2.= $render;
            $c2+= count($group['values']);
        } else {
            $_1.= $render;
            $c1+= count($group['values']);
        }
    }

    echo '<div>'.$_1.'</div><div>'.$_2.'</div>';
    
    ?>
</div>