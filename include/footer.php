<?php 


class PLS_Footer {

    static function init() {

        add_action( 'placester-spine_close_menu_subsidiary', array( __CLASS__, 'copyright'  ) );
        add_action( 'placester-spine_open_footer', array( __CLASS__, 'contact'  ));
        add_action( 'placester-spine_close_footer', array( __CLASS__, 'logo'  ));
    }

    static function copyright() {
    ?>
        <section class="footer-copyright">
            <p class="sma-txt yyy">&COPY;&nbsp;2011&nbsp;Powered by Placestar</p>
        </section>
    <?php
    }

    static function contact() {
        /** Get the company info object. */
        $company_details = PLS_Plugin_API::get_company_details();
    ?>
        <section class="footer-contact">
        <?php echo "{$company_details->location->address}, {$company_details->location->city}, {$company_details->location->state}{$company_details->location->zip}" ?> | <span><?php echo __( 'Support', pls_get_textdomain() ) . ": " . PLS_Plugin_API::get_option( 'support_email' );  ?></span> | <span><?php _e( 'Phone', pls_get_textdomain() ) ?>: <?php echo $company_details->phone ?></span> | <?php _e( 'Fax', pls_get_textdomain() ) ?>: <?php echo PLS_Plugin_API::get_option( 'fax_no' ) ?>
        </section>
    <?php
    }

    static function logo() {
    ?>
        <section class="footer-logo">
            <a href="<?php echo get_option('home'); ?>"><span class="footer-name"><?php bloginfo('name'); ?></span><br/><span class="footer-slogan"><?php bloginfo('description'); ?></span></a>            
        </section>
    <?php
    }
}