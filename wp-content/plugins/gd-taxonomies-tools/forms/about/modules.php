<?php

global $gdtt;

$modules = array_keys($gdtt->loaded_modules);

foreach ($modules as $mod) {
    $data = $gdtt->mods[$mod]['__core__'];
    
    echo '<h3>'.$data['name'].' '.$data['version'].($data['status'] != 'stable' ? ' '.ucfirst($data['status']) : '').'</h3>';
    echo '<blockquote><em>'.$data['description'].'</em><br/>';
    if ($data['url'] != '') {
        echo '<strong>Home:</strong> <a href="'.$data['url'].'">'.$data['url'].'</a><br/>';
    }
    echo '<strong>Build:</strong> '.$data['build'].' / '.$data['date'].'<br/>';
    echo '<strong>Author:</strong> <a href="'.$data['author_web'].'">'.$data['author_name'].'</a><br/>';
    echo '</blockquote>';
}

?>