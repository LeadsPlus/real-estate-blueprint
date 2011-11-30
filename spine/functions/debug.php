<?php
/**
 * Wrapper function for the PLS_Debug::dump() function.
 * 
 * @access public
 * @return void
 */
function pls_dump() {
    $args = func_get_args();
    PLS_Debug::dump( $args );
}

/**
 * A class that includes theme debugging functions
 *
 * @static
 */
class PLS_Debug {

    /**
     * Dumps a variable for debugging purposes
     * 
     * @param mixed $data The variable that needs to be dumped.
     * @static
     */
    static function dump() {
        $args = func_get_args();
        /**
         *  If the given variable is an array use print_r
         */
        foreach ( $args as $data ) {
            if( is_array( $data ) ) {
                print "<pre>-----------------------\n";
                print_r( $data );
                print "-----------------------</pre>\n";
            } elseif ( is_object( $data ) || is_bool( $data ) ) {
                print "<pre>==========================\n";
                var_dump( $data );
                print "===========================</pre>\n";
            } else {
                print "<pre>=========&gt; ";
                echo $data;
                print " &lt;=========</pre>";
                echo "\n";
            }
        }
    }
}
