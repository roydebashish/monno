<?php
$layouts = get_field('layouts');

if ( $layouts ) {
    foreach ($layouts as $layout) {
        include TEMPLATEPATH.'/inc/layouts/'.$layout['acf_fc_layout'].'.php'; 
    }
} else {
    include TEMPLATEPATH.'/inc/layouts/without-layout.php'; 
}