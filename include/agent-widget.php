<?php 

add_filter( 'pls_widget_agent', 'my_agent_widget', 10, 9 );
function my_agent_widget( $widget_complete, $widget_title, $before_title, $after_title, $widget_body, $agent_html, $agent_obj, $instance, $widget_id ) {

    $args = func_get_args();
    echo $widget_complete;
}