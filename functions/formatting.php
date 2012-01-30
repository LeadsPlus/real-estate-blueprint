<?php 

class PLS_Format {


	static public function phone ($phone, $options = '') {
		$new_phone = '';

		/** Define the default argument array. */
        $defaults = array(
        	'format' => 'hyphens',
        	'html_args' => array()
	        );

        /** Merge the arguments with the defaults. */
        $options = wp_parse_args( $options, $defaults );

        extract( $options, EXTR_SKIP );

        if (self::validate_number(&$phone)) {
            $phone_parts = self::process_phone_parts($phone);

            switch ($format) {
            	case 'hyphens':
            		$new_phone = self::format_phone($phone_parts, '-');
            		break;

            	case 'spaces':
					$new_phone = self::format_phone($phone_parts, ' ');
            		break;

            	case 'as_html':
            		$new_phone = self::format_phone_html($phone_parts, $html_args);
            		break;
            	
            	default:
            		# code...
            		break;
            }
        
    	}

        return $new_phone;

	}

	static public function listing_price ($listing, $args) {

		if (is_array($listing)) {
		
			$price = self::number($listing['price'], $args);

			if (isset($listing['purchase_types']) && ($listing['purchase_types'][0] == 'rental')) {
				$price .= "/month";
			}

			return $price;

		} elseif (is_object($listing)) {
			
			$price = self::number($listing->price, $args);

			if (isset($listing->purchase_types) && ($listing->purchase_types[0] == 'rental')) {
				$price .= "/month";
			}

			return $price;
		}


		return "";

	}

	// formats the given number based on the supplied options. 
	static public function number ( $number, $options = '') {
		
		$formatted_number = false;

		/** Define the default argument array. */
        $defaults = array(
            'add_commas' => true,
            'add_currency_sign' => true,
            'abbreviate' => true
        );

        /** Merge the arguments with the defaults. */
        $options = wp_parse_args( $options, $defaults );

		//given a number, properly insert commas. 
		if ($options['add_commas']) {
			$formatted_number = number_format($number);
		}
		
		if ($options['abbreviate']) {
			$formatted_number = self::abbreviate_number($formatted_number);
		}
		
		//insert $ sign.
		if ($options['add_currency_sign']) {
			$formatted_number = '$' . $formatted_number;	
		}
		
		return $formatted_number;

	}

	static public function abbreviate_number ($number) {
		$abbreviated_number = false;

		
		if ( !strpos( (string) $number, ',')) {
			$formatted_number = number_format($number);			
		}

		// Force length intellegently
		$number_blocks = explode(',', $number);

		$block_length = count($number_blocks);
	
		switch ($block_length) {				
			case 1:
				$abbreviated_number = $number;
				break;

			case 2:
				$abbreviated_number = $number_blocks[0] . 'K';
				break;

			case 3:
				$abbreviated_number = $number_blocks[0] . 'M';
				break;

			default:
				$abbreviated_number = $number;
				break;
		}

		return $abbreviated_number;
	}

	static private function validate_number ($phone) {
		
		//placester api dumps a + in there. 		
		if (substr($phone, 0, 1) == '+') {
			$phone = substr($phone, 1);
		}

		
		if (is_numeric($phone)) {
		
			// test if there's a 1 prepended.
			if (strlen($phone) === 11 && $phone[0] == 1) {
				//pop 1 off. 
				$phone = substr($phone, 1);
				return true;
			
			} elseif (strlen($phone) === 10) {
				// it's a 10 digit number... I guess that works.
				return true;	
			}
		}

		// is invalid if no return by here. 
		return false;
	}

	static private function process_phone_parts ($phone) {
    	
    	$phone_parts = array();
    	//area code in states
    	$phone_parts[] = substr($phone, 0,3);	
    	//next 3
    	$phone_parts[] = substr($phone, 3, 3);	
    	// next 4
    	$phone_parts[] = substr($phone, 6,4);	

    	return $phone_parts;
	}

	static private function format_phone ($phone_parts, $delimiter = '') {
		
		$new_phone = '';
		
		foreach ($phone_parts as $key => $part) {
			if ($key == 0) {
				$new_phone .= $part;
			} else {
				$new_phone .= $delimiter . $part;	
			}
		}

		return $new_phone;
	}

	static private function format_phone_html ($phone_parts, $args) {
		$new_phone = '';

		/** Define the default argument array. */
        $defaults = array(
        	'wrapper' => '',
        	'area_wrapper' => '',
        	'three_wrapper' => '',
        	'four_wrapper' => '',
        	'delimiter' => ' '
	        );

        /** Merge the arguments with the defaults. */
        $options = wp_parse_args( $args, $defaults );

        extract( $options, EXTR_SKIP );

		if ($area_wrapper != '') {
			$new_phone .= pls_h($area_wrapper, $phone_parts[0]);
		} else {
			$new_phone .= $phone_parts[0];
		}

		if ($three_wrapper != '') {
			$new_phone .= pls_h($three_wrapper, $phone_parts[1]);
		} else {
			$new_phone .= $delimiter . $phone_parts[1];
		}

		if ($four_wrapper != '') {
			$new_phone .= pls_h($four_wrapper, $phone_parts[2]);
		} else {
			$new_phone .= $delimiter . $phone_parts[2];
		}

		if ($wrapper != '') {
			$new_phone = pls_h($wrapper, $new_phone);	
		}

		return $new_phone;

	}


//end of class
}