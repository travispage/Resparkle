<?php

function fca_pc_search_integration() {
	
	if ( is_search() ) {
		wp_localize_script( 'fca_pc_client_js', 'fcaPcSearchQuery', array( 'search_string' => get_search_query() ) );
	}
}
