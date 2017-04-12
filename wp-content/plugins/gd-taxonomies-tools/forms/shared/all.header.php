<div class="wrap gdsr">
    <div class="logo"></div>
    <div class="header">
        <?php

        $breadcrumb = array();
        foreach ($header as $h) {
            if ($h[1] == "#") $h[1] = $_SERVER['REQUEST_URI'];
            $breadcrumb[] = '<a href="'.$h[1].'">'.$h[0].'</a>';
        }
        echo join("<span>|</span>", $breadcrumb);

        ?>
    </div>
    <?php do_action('gdcpt_panel_header_'.$_panel_name); ?>