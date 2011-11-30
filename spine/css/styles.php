<?php
/**
 *  Enqueue the styles only if the PLS_LOAD_STYLES constant is not set to 
 *  false. This allows developers to stop style loading by defining it in 
 *  'functions.php'
 */
if ( !defined( 'PLS_LOAD_STYLES' ) || ( defined( 'PLS_LOAD_STYLES' ) && ( PLS_LOAD_STYLES === true ) ) ) {

    /**
     * Registers and enqueues styles
     *
     * @since 0.0.1
     */
    add_action( 'template_redirect', 'pls_styles' );
    function pls_styles() {

        // wp_enqueue_style( 'pls-default', '/css/style.css' . get_bloginfo( 'stylesheet_url' ) );

        /**
         *  If the plugin is inactive, or the api key is missing from the 
         *  plugin enqueue a css file that deals with styling the plugin 
         *  notifications. Accompanied by plugin-nags.js.
         */
        if ( pls_has_plugin_error() ) {
            wp_enqueue_style( 'pls-plugin-nags', trailingslashit( PLS_CSS_URL ) . 'styles/plugin-nags.css' );
        }

        wp_enqueue_style( 'normalize', trailingslashit( PLS_CSS_URL ) . 'styles/normalize.css' );

        /** Include default style only if supported. */
        if ( get_theme_support( 'pls-default-style' ) )
            wp_enqueue_style( 'pls-default-style', trailingslashit( PLS_CSS_URL ) . 'styles/style.css' );
    }
}

