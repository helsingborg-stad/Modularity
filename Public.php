<?php

function modularity_decode_icon($data) {
	
    if (!empty($data['menu_icon'])) {
	    
	    if ( isset($data['menu_icon_auto_import']) && $data['menu_icon_auto_import'] === true ) {
		    
		    return $data['menu_icon']; 
		    
	    } else {
		    
		    $data = explode(',', $data['menu_icon']);
		
		    if (strpos($data[0], 'svg') !== false) {
		        return base64_decode($data[1]);
		    }
		
		    return '<img src="' . base64_decode($data[1]) . '">';
		    
	    }
	    
    } 
    
    return; 

}
