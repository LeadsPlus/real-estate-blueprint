<?php 


PLS_Style::init();

class PLS_Style {
	
	/**
     *  grabs the option list and makes it available
     */
    static function init()
    {
		
		include_once( trailingslashit( TEMPLATEPATH ) . 'options.php' );
    }

    public static function create_css ($options)
    {

        if ( isset($options) && is_array($options)) {

            $sorted_selector_array = array();
            $sorted_selector_array = self::sort_by_selector($options);

            if ( empty($sorted_selector_array) ) {
                return false;
            } 

    		$styles = '<style type="text/css">  ';

            foreach ($sorted_selector_array as $selector => $options) {
                $styles .= $selector . ' {';

                foreach ($options as $index => $option) {
                    
                    $defaults = array(
                        "name" => "",
                        "desc" => "",
                        "id" => "",
                        "std" => "",
                        "selector" => "body",
                        "style" => "",
                        "type" => "",
                        "important" => true,
                        "default" => ""
                    );

                    /** Merge the arguments with the defaults. */
                    $option = wp_parse_args( $option, $defaults );

                    // PLS_Debug::dump($option);

                    if (!empty($option['style']) || self::is_special_case($option['type'])) {
                        //if we have a style, then let's try to generate a stlye.
                        $styles .= self::handle_style($option['style'], $option['id'], $option['default'], $option['type'], $option['important']);
                        continue;
                    } elseif (!empty($id)) {
                        //try to use the id as the style... saves time for power devs.
                        $styles .= self::handle_style($option['id'], $option['id'], $option['default'], $option['type'], $option['important']);    
                        continue;
                    } else {
                        continue;
                    }
                }
                $styles .= '}';   
            }
    		$styles .= '  </style>';


    		return $styles;
    		
    	} else {
    		// no options for some reason,
    		// die quitely.
    		return false;
    	}
    }

	//for quick styling
	private static function handle_style ($style, $id, $default, $type, $important) 
	{        
        
        if ($value = pls_get_option($id, $default)) {
            
            $css_style = '';
            
            // check for special cases
            // sometimes the optoins framework saves certain options
            // in unique ways which can't be directly translated into styles
            if (self::is_special_case($type)) {
                
                //handles edge cases, returns a property formatted string
                return self::handle_special_case($value, $id, $default, $type, $important);
            } else {
                $css_style = self::make_style($style, $value, $important);
                return $css_style;                    
            }
                        
        } else {
            return '';
        }
	}

    private static function handle_special_case($value, $id, $default, $type, $important)
    {
        switch ($type) {
            case 'typography':
                
                return self::handle_typography($value, $id, $default, $type, $important);
                break;
        }
    }

    private static function handle_typography ($value, $id, $default, $type, $important)
    {
        
        if (is_array($value)) {
            
            $css_style = '';
            
            foreach ($value as $key => $value) {
                switch ($key) {
                        case 'size':
                            $css_style .= self::make_style('font-size', $value, $important);
                            break;

                        case 'face':
                            $css_style .= self::make_style('font-family', $value, $important);
                            break;
                        
                        case 'style':
                            $css_style .= self::make_style('font-weight', $value, $important);
                            break;
                        
                        case 'color':
                            $css_style .= self::make_style('color', $value, $important);
                            break;
                    }    
            }
            // return the new styles.
            return $css_style;

        } else {
            //something strange happened, typography should always return an array.
            return '';
        }
    }

    //given a syle, and a value, it returns a propertly formated styles
    private static function make_style($style, $value, $important = false)
    {
        return $style . ': ' . $value . ($important ? ' !important;' : '');
    }

    // Takes an array with options that have various seelctors
    // and merges all the otpions with the same selector under
    // a new array attribute so it can be easily used to generate
    // css
    private static function sort_by_selector ($options)
    {
        $selector_array = array();

        foreach ($options as $item => $option) {
            //if we don't have a selector, try to generate one
            if ($option['type'] == 'heading' || $option['type'] == 'info') {
                continue;
            }

            if ((is_array($option) && !isset($option['selector'])) || empty($option['selector'])) {

                // user can set selector in front of id
                $selector_id_array = explode('.', $option['id']);
                
                if ( isset($selector_id_array[1])) {
                    $option['selector'] = $selector_id_array[0];    
                } else {
                    $option['selector'] = 'body';    
                }
            } 

            // yank out all the styles that apply to specific selectors into
            // an array that is 'selector'[0] => style, [1] => style
            if (array_key_exists($option['selector'], $selector_array)) {
                $selector_array[$option['selector']][] = $option;
            } else {
                $selector_array[$option['selector']] = array();
                $selector_array[$option['selector']][] = $option;
            }
        }

        return $selector_array;
    }

    private static function is_special_case($option_type)
    {
        $special_id_cases = array('typography');
        if ( in_array($option_type, $special_id_cases) ) {
            return true;
        } 

        return false;

    }
}

