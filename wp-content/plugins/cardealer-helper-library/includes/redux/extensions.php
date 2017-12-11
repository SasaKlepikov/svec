<?php
// Replace {$redux_opt_name} with your opt_name.
// Also be sure to change this function name!
if( !function_exists('cdhl_redux_extensions_loader') ) :
    function cdhl_redux_extensions_loader($ReduxFramework) {
		$fields_path = realpath( untrailingslashit( CDHL_PATH ) . '/includes/redux/extensions/' );
		$folders = scandir( $fields_path, 1 );
		
		foreach ( $folders as $folder ) {
			if ( $folder === '.' or $folder === '..' or ! is_dir( realpath($fields_path .'/'. $folder) ) ) {
				continue;
			}
			$extension_class = 'ReduxFramework_' . $folder;
			if ( ! class_exists( $extension_class ) ) {
				// In case you wanted override your override, hah.
				$class_file = realpath($fields_path .'/'. $folder) . '/field_' . $folder . '.php';
				if ( $class_file ) {
					require_once( $class_file );
				}
			}
		}
    }
	
    // Modify {$redux_opt_name} to match your opt_name
    add_action("redux/extensions/car-dealer-options/before", 'cdhl_redux_extensions_loader', 0);
endif;