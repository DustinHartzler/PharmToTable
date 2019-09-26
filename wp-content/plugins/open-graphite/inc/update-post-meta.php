<?php 

if ( ! defined( 'ABSPATH' ) ) exit; /* Exit if accessed directly */

$user           = wp_get_current_user();
$allowed_roles  = array('editor', 'administrator', 'author'); /* Allow saving post meta for these roles */
if( array_intersect($allowed_roles, $user->roles ) ) {

    if ( !wp_verify_nonce( $_POST['_open_graphite_open_type__nounce'], plugin_basename( __FILE__ ) ) ) {

        // common
        if( isset( $_POST['_open_graphite_open_type_field'] ) )
            update_post_meta( $post_id, '_open_graphite_open_type_field', sanitize_text_field( $_POST['_open_graphite_open_type_field'] ) );

        if( isset( $_POST['_custom_image'] ) )
            update_post_meta( $post_id, '_custom_image', sanitize_text_field( $_POST['_custom_image'] ) );
            
        if( isset( $_POST['_open_graph_description'] ) )
            update_post_meta( $post_id, '_open_graph_description', sanitize_textarea_field( $_POST['_open_graph_description'] ) );
            
        if( isset( $_POST['_open_graph_title'] ) )
            update_post_meta( $post_id, '_open_graph_title', sanitize_text_field( $_POST['_open_graph_title'] ) );
            
        if( isset( $_POST['_open_graph_post_type'] ) )
            update_post_meta( $post_id, '_open_graph_post_type', sanitize_text_field( $_POST['_open_graph_post_type'] ) );
            

        // books.book
        if( isset( $_POST['books:gender'] ) )
            update_post_meta( $post_id, 'books:gender', sanitize_text_field( $_POST['books:gender'] ) );

        if( isset( $_POST['books:official_site'] ) )
            update_post_meta( $post_id, 'books:official_site', sanitize_text_field( $_POST['books:official_site'] ) );

        if( isset( $_POST['books:isbn'] ) )
            update_post_meta( $post_id, 'books:isbn', sanitize_text_field( $_POST['books:isbn'] ) );
            
        if( isset( $_POST['books:rating:value'] ) )
            update_post_meta( $post_id, 'books:rating:value', sanitize_text_field( $_POST['books:rating:value'] ) );
            
        if( isset( $_POST['books:rating:scale'] ) )
            update_post_meta( $post_id, 'books:rating:scale', sanitize_text_field( $_POST['books:rating:scale'] ) );


        // books.genre
        if( isset( $_POST['books:canonical_name'] ) )
            update_post_meta( $post_id, 'books:canonical_name', sanitize_text_field( $_POST['books:canonical_name'] ) );
            
            
        // business.business
        if( isset( $_POST['business:contact_data:street_address'] ) )
            update_post_meta( $post_id, 'business:contact_data:street_address', sanitize_text_field( $_POST['business:contact_data:street_address'] ) );

        if( isset( $_POST['business:contact_data:locality'] ) )
            update_post_meta( $post_id, 'business:contact_data:locality', sanitize_text_field( $_POST['business:contact_data:locality'] ) );
            
        if( isset( $_POST['business:contact_data:region'] ) )
            update_post_meta( $post_id, 'business:contact_data:region', sanitize_text_field( $_POST['business:contact_data:region'] ) );

        if( isset( $_POST['business:contact_data:postal_code'] ) )
            update_post_meta( $post_id, 'business:contact_data:postal_code', sanitize_text_field( $_POST['business:contact_data:postal_code'] ) );
            
        if( isset( $_POST['business:contact_data:country_name'] ) )
            update_post_meta( $post_id, 'business:contact_data:country_name', sanitize_text_field( $_POST['business:contact_data:country_name'] ) );

        if( isset( $_POST['business:contact_data:email'] ) )
            update_post_meta( $post_id, 'business:contact_data:email', sanitize_text_field( $_POST['business:contact_data:email'] ) );
            
        if( isset( $_POST['business:contact_data:phone_number'] ) )
            update_post_meta( $post_id, 'business:contact_data:phone_number', sanitize_text_field( $_POST['business:contact_data:phone_number'] ) );

        if( isset( $_POST['business:contact_data:fax_number'] ) )
            update_post_meta( $post_id, 'business:contact_data:fax_number', sanitize_text_field( $_POST['business:contact_data:fax_number'] ) );
            
        if( isset( $_POST['business:contact_data:website'] ) )
            update_post_meta( $post_id, 'business:contact_data:website', sanitize_text_field( $_POST['business:contact_data:website'] ) );
            

        // music.album
        if( isset( $_POST['music:musician'] ) )
            update_post_meta( $post_id, 'music:musician', sanitize_text_field( $_POST['music:musician'] ) );
            
        if( isset( $_POST['music:release_date'] ) )
            update_post_meta( $post_id, 'music:release_date', sanitize_text_field( $_POST['music:release_date'] ) );
            
        if( isset( $_POST['music:release_type'] ) )
            update_post_meta( $post_id, 'music:release_type', sanitize_text_field( $_POST['music:release_type'] ) );
            
        if( isset( $_POST['music:song:url'] ) )
            update_post_meta( $post_id, 'music:song:url', sanitize_text_field( $_POST['music:song:url'] ) );
            
        if( isset( $_POST['music:song:track'] ) )
            update_post_meta( $post_id, 'music:song:track', sanitize_text_field( $_POST['music:song:track'] ) );


        // music.radio_station
        if( isset( $_POST['music:creator'] ) )
            update_post_meta( $post_id, 'music:creator', sanitize_text_field( $_POST['music:creator'] ) );
            

        // music.song
        if( isset( $_POST['music:song:music:album'] ) )
            update_post_meta( $post_id, 'music:song:music:album', sanitize_text_field( $_POST['music:song:music:album'] ) );

        if( isset( $_POST['music:song:music:album:url'] ) )
            update_post_meta( $post_id, 'music:song:music:album:url', sanitize_text_field( $_POST['music:song:music:album:url'] ) );
            
        if( isset( $_POST['music:song:music:preview_url:url'] ) )
            update_post_meta( $post_id, 'music:song:music:preview_url:url', sanitize_text_field( $_POST['music:song:music:preview_url:url'] ) );
            
        // place
        if( isset( $_POST['place:location:latitude'] ) )
            update_post_meta( $post_id, 'place:location:latitude', sanitize_text_field( $_POST['place:location:latitude'] ) );
            
        if( isset( $_POST['place:location:longitude'] ) )
            update_post_meta( $post_id, 'place:location:longitude', sanitize_text_field( $_POST['place:location:longitude'] ) );
            
        if( isset( $_POST['place:location:altitude'] ) )
            update_post_meta( $post_id, 'place:location:altitude', sanitize_text_field( $_POST['place:location:altitude'] ) );
            

        // product
        if( isset( $_POST['product:original_price:amount'] ) )
            update_post_meta( $post_id, 'product:original_price:amount', sanitize_text_field( $_POST['product:original_price:amount'] ) );
            
        if( isset( $_POST['product:original_price:currency'] ) )
            update_post_meta( $post_id, 'product:original_price:currency', sanitize_text_field( $_POST['product:original_price:currency'] ) );
            
        if( isset( $_POST['product:pretax_price:amount'] ) )
            update_post_meta( $post_id, 'product:pretax_price:amount', sanitize_text_field( $_POST['product:pretax_price:amount'] ) );
            
        if( isset( $_POST['product:pretax_price:currency'] ) )
            update_post_meta( $post_id, 'product:pretax_price:currency', sanitize_text_field( $_POST['product:pretax_price:currency'] ) );
            
        if( isset( $_POST['product:price:amount'] ) )
            update_post_meta( $post_id, 'product:price:amount', sanitize_text_field( $_POST['product:price:amount'] ) );
            
        if( isset( $_POST['product:price:currency'] ) )
            update_post_meta( $post_id, 'product:price:currency', sanitize_text_field( $_POST['product:price:currency'] ) );
            
        if( isset( $_POST['product:shipping_cost:amount'] ) )
            update_post_meta( $post_id, 'product:shipping_cost:amount', sanitize_text_field( $_POST['product:shipping_cost:amount'] ) );
            
        if( isset( $_POST['product:shipping_cost:currency'] ) )
            update_post_meta( $post_id, 'product:shipping_cost:currency', sanitize_text_field( $_POST['product:shipping_cost:currency'] ) );
            
        if( isset( $_POST['product:sale_price:amount'] ) )
            update_post_meta( $post_id, 'product:sale_price:amount', sanitize_text_field( $_POST['product:sale_price:amount'] ) );
            
        if( isset( $_POST['product:sale_price:currency'] ) )
            update_post_meta( $post_id, 'product:sale_price:currency', sanitize_text_field( $_POST['product:sale_price:currency'] ) );


        // profile
        if( isset( $_POST['profile:first_name'] ) )
            update_post_meta( $post_id, 'profile:first_name', sanitize_text_field( $_POST['profile:first_name'] ) );	

        if( isset( $_POST['profile:last_name'] ) )
            update_post_meta( $post_id, 'profile:last_name', sanitize_text_field( $_POST['profile:last_name'] ) );
            
        if( isset( $_POST['profile:gender'] ) )
            update_post_meta( $post_id, 'profile:gender', sanitize_text_field( $_POST['profile:gender'] ) );
            
        // restaurant.menu
        if( isset( $_POST['restaurant:restaurant'] ) )
            update_post_meta( $post_id, 'restaurant:restaurant', sanitize_text_field( $_POST['restaurant:restaurant'] ) );
            
            
        // restaurant.menu_item
        if( isset( $_POST['restaurant:variation:price:amount'] ) )
            update_post_meta( $post_id, 'restaurant:variation:price:amount', sanitize_text_field( $_POST['restaurant:variation:price:amount'] ) );
            
        if( isset( $_POST['restaurant:variation:price:currency'] ) )
            update_post_meta( $post_id, 'restaurant:variation:price:currency', sanitize_text_field( $_POST['restaurant:variation:price:currency'] ) );
            

        // restaurant.restaurant
        if( isset( $_POST['restaurant:contact_info:street_address'] ) )
            update_post_meta( $post_id, 'restaurant:contact_info:street_address', sanitize_text_field( $_POST['restaurant:contact_info:street_address'] ) );
            
        if( isset( $_POST['restaurant:contact_info:locality'] ) )
            update_post_meta( $post_id, 'restaurant:contact_info:locality', sanitize_text_field( $_POST['restaurant:contact_info:locality'] ) );
            
        if( isset( $_POST['restaurant:contact_info:region'] ) )
            update_post_meta( $post_id, 'restaurant:contact_info:region', sanitize_text_field( $_POST['restaurant:contact_info:region'] ) );
            
        if( isset( $_POST['restaurant:contact_info:postal_code'] ) )
            update_post_meta( $post_id, 'restaurant:contact_info:postal_code', sanitize_text_field( $_POST['restaurant:contact_info:postal_code'] ) );
            
        if( isset( $_POST['restaurant:contact_info:country_name'] ) )
            update_post_meta( $post_id, 'restaurant:contact_info:country_name', sanitize_text_field( $_POST['restaurant:contact_info:country_name'] ) );
            
        if( isset( $_POST['restaurant:contact_info:email'] ) )
            update_post_meta( $post_id, 'restaurant:contact_info:email', sanitize_text_field( $_POST['restaurant:contact_info:email'] ) );
            
        if( isset( $_POST['restaurant:contact_info:phone_number'] ) )
            update_post_meta( $post_id, 'restaurant:contact_info:phone_number', sanitize_text_field( $_POST['restaurant:contact_info:phone_number'] ) );
            
        if( isset( $_POST['restaurant:contact_info:fax_number'] ) )
            update_post_meta( $post_id, 'restaurant:contact_info:fax_number', sanitize_text_field( $_POST['restaurant:contact_info:fax_number'] ) );
            
        if( isset( $_POST['restaurant:contact_info:website'] ) )
            update_post_meta( $post_id, 'restaurant:contact_info:website', sanitize_text_field( $_POST['restaurant:contact_info:website'] ) );
            

        // video.episode
        if( isset( $_POST['video:actor'] ) )
            update_post_meta( $post_id, 'video:actor', sanitize_text_field( $_POST['video:actor'] ) );
            
        if( isset( $_POST['video:director'] ) )
            update_post_meta( $post_id, 'video:director', sanitize_text_field( $_POST['video:director'] ) );
            
        if( isset( $_POST['video:writer'] ) )
            update_post_meta( $post_id, 'video:writer', sanitize_text_field( $_POST['video:writer'] ) );
            
        if( isset( $_POST['video:release_date'] ) )
            update_post_meta( $post_id, 'video:release_date', sanitize_text_field( $_POST['video:release_date'] ) );
            
        if( isset( $_POST['video:duration'] ) )
            update_post_meta( $post_id, 'video:duration', sanitize_text_field( $_POST['video:duration'] ) );
            
        if( isset( $_POST['video:series'] ) )
            update_post_meta( $post_id, 'video:series', sanitize_text_field( $_POST['video:series'] ) );
            
        if( isset( $_POST['video:tag'] ) )
            update_post_meta( $post_id, 'video:tag', sanitize_text_field( $_POST['video:tag'] ) );
            

        // video.movie	
        if( isset( $_POST['video:movie:video:actor'] ) )
            update_post_meta( $post_id, 'video:movie:video:actor', sanitize_text_field( $_POST['video:movie:video:actor'] ) );

        if( isset( $_POST['video:movie:video:director'] ) )
            update_post_meta( $post_id, 'video:movie:video:director', sanitize_text_field( $_POST['video:movie:video:director'] ) );

        if( isset( $_POST['video:movie:video:writer'] ) )
            update_post_meta( $post_id, 'video:movie:video:writer', sanitize_text_field( $_POST['video:movie:video:writer'] ) );

        if( isset( $_POST['video:movie:video:release_date'] ) )
            update_post_meta( $post_id, 'video:movie:video:release_date', sanitize_text_field( $_POST['video:movie:video:release_date'] ) );

        if( isset( $_POST['video:movie:video:duration'] ) )
            update_post_meta( $post_id, 'video:movie:video:duration', sanitize_text_field( $_POST['video:movie:video:duration'] ) );
            
        if( isset( $_POST['video:movie:video:tag'] ) )
            update_post_meta( $post_id, 'video:movie:video:tag', sanitize_text_field( $_POST['video:movie:video:tag'] ) );
            

        // video.other
        if( isset( $_POST['video:other:video:actor'] ) )
            update_post_meta( $post_id, 'video:other:video:actor', sanitize_text_field( $_POST['video:other:video:actor'] ) );
            
        if( isset( $_POST['video:other:video:director'] ) )
            update_post_meta( $post_id, 'video:other:video:director', sanitize_text_field( $_POST['video:other:video:director'] ) );
            
        if( isset( $_POST['video:other:video:writer'] ) )
            update_post_meta( $post_id, 'video:other:video:writer', sanitize_text_field( $_POST['video:other:video:writer'] ) );
            
        if( isset( $_POST['video:other:video:release_date'] ) )
            update_post_meta( $post_id, 'video:other:video:release_date', sanitize_text_field( $_POST['video:other:video:release_date'] ) );
            
        if( isset( $_POST['video:other:video:duration'] ) )
            update_post_meta( $post_id, 'video:other:video:duration', sanitize_text_field( $_POST['video:other:video:duration'] ) );
            
        if( isset( $_POST['video:other:video:tag'] ) )
            update_post_meta( $post_id, 'video:other:video:tag', sanitize_text_field( $_POST['video:other:video:tag'] ) );
            
            
        // video:tv_show
        if( isset( $_POST['video:tv_show:video:actor'] ) )
            update_post_meta( $post_id, 'video:tv_show:video:actor', sanitize_text_field( $_POST['video:tv_show:video:actor'] ) );
            
        if( isset( $_POST['video:tv_show:video:director'] ) )
            update_post_meta( $post_id, 'video:tv_show:video:director', sanitize_text_field( $_POST['video:tv_show:video:director'] ) );
            
        if( isset( $_POST['video:tv_show:video:writer'] ) )
            update_post_meta( $post_id, 'video:tv_show:video:writer', sanitize_text_field( $_POST['video:tv_show:video:writer'] ) );
            
        if( isset( $_POST['video:tv_show:video:release_date'] ) )
            update_post_meta( $post_id, 'video:tv_show:video:release_date', sanitize_text_field( $_POST['video:tv_show:video:release_date'] ) );
            
        if( isset( $_POST['video:tv_show:video:duration'] ) )
            update_post_meta( $post_id, 'video:tv_show:video:duration', sanitize_text_field( $_POST['video:tv_show:video:duration'] ) );
            
        if( isset( $_POST['video:tv_show:video:tag'] ) )
            update_post_meta( $post_id, 'video:tv_show:video:tag', sanitize_text_field( $_POST['video:tv_show:video:tag'] ) );


        // Facebook app ID	
        if( isset( $_POST['fb:app_id'] ) )
            update_post_meta( $post_id, 'fb:app_id', sanitize_text_field( $_POST['fb:app_id'] ) );

    }

}