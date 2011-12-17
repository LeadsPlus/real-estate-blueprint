<?php 

class PLS_Format {


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

//end of class
}