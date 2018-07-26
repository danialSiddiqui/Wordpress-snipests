<?php

add_action('wp_enqueue_scripts', 'porto_child_css', 1001);
 
// Load CSS
function porto_child_css() {
    // porto child theme styles
    wp_deregister_style( 'styles-child' );
    wp_register_style( 'styles-child', get_stylesheet_directory_uri() . '/style.css' );
    wp_enqueue_style( 'styles-child' );

    if (is_rtl()) {
        wp_deregister_style( 'styles-child-rtl' );
        wp_register_style( 'styles-child-rtl', get_stylesheet_directory_uri() . '/style_rtl.css' );
        wp_enqueue_style( 'styles-child-rtl' );
    }
}

/**

* garivtor icon

*/


add_filter( 'avatar_defaults', 'wpb_new_gravatar' );
function wpb_new_gravatar ($avatar_defaults) {
$myavatar = 'https://cyclerskit.com/wp-content/uploads/2018/05/profile-pic.jpg';
$avatar_defaults[$myavatar] = "Default Gravatar";
return $avatar_defaults;
}


/**

* hiding in sotck 

*/

function my_wc_hide_in_stock_message( $html, $text, $product ) {
    $availability = $product->get_availability();
    if ( isset( $availability['in-stock'] ) && 'in-stock' === $availability['in-stock'] ) {
        return '';
    }
    return $html;
}
add_filter( 'woocommerce_stock_html', 'my_wc_hide_in_stock_message', 10, 3 );


/**
 * Adds a custom message about how long will take to delivery.
 */
function my_wc_custom_cart_shipping_notice() {
    echo '<tr class="shipping-notice"><th><small style="font-size:14px;">';
        _e( '<strong>Shipping Time</strong> ', 'my-text-domain' );
        echo '</small></th><td>';
        _e( '2 - 4 Weeks', 'my-text-domain');
        echo '</td><tr>';
}
add_action( 'woocommerce_cart_totals_after_shipping', 'my_wc_custom_cart_shipping_notice' );
add_action( 'woocommerce_review_order_after_shipping', 'my_wc_custom_cart_shipping_notice' );


add_filter( 'woocommerce_after_single_product_summary', 'banner_image',28 );
function banner_image()
{   echo '
    <div class="product-detail-header">
    <img class="sm-img-d" src="https://cyclerskit.com/wp-content/uploads/2018/07/mobileicons.png" alt="" />
    <img class="lg-img-d" src="https://cyclerskit.com/wp-content/uploads/2018/07/cyclerskit-Free-Shipping-banner-for-product-pages.jpg" alt="" />
    </div> ';
       
    
}
 
/*    shipping text adding on single product page    */

add_filter( 'woocommerce_product_meta_start', 'Shipping_text',20 );
function Shipping_text()
{  

    global $product;

    $hide_for_products = array( 16141 );
    if ( in_array( $product->get_id(), $hide_for_products ) ){
     echo '
    <div class="product-detail-shipping">
    <ul class="just-pay-txt">
    <li class="jpt-check" ><span>Worldwide Shipping</span></li>
    <li class="jpt-check" ><span>Trackable Delivery</span></li>
    <li class="jpt-check" ><span>Processed in 24 hours </span></li>
    </ul>
    </div> ';
}
else{
    echo '
    <div class="product-detail-shipping">
    <p><span class="product-stock in-stock"> <span style="color:black;" class="stock"> Free Trackable Shipping</span></span></p>
    </div> ';
       
    }
}


/**

* prices adding after text

*/



function cruency_label ($price){
    
 global $post;
        
    $product_id = $post->ID;
    
        $textafter = ' <span class="price-description">US </span>'; //add your text
        $p = " " ;
        return  $textafter . ' ' . $price ;
    
     
    
}

add_filter('woocommerce_get_price_html', 'cruency_label');


add_filter('woocommerce_show_variation_price',      function() { return TRUE;});

/* 



* add partent theme style on child theme




   US adding before text    

function cruency_piece($price){
    
 global $post;
        
    $product_id = $post->ID;
    
        $textafter = '/piece '; //add your text
        return $price . '<span class="price-piece-description">' . $textafter . '</span>';
    
     
    
}

add_filter('woocommerce_get_price_html', 'cruency_piece'); */


/**

* alter notic msg when variton is not slected

*/

add_filter( 'gettext', 'customizing_variable_product_message', 97, 3 );
function customizing_variable_product_message( $translated_text, $untranslated_text, $domain )
{
    if ($untranslated_text == 'Please select some product options before adding this product to your cart.') {
        $translated_text = __( 'Please select a product options', $domain );
    }
    return $translated_text;
}




add_filter( 'woocommerce_get_price_html', 'custom_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'custom_price_format', 10, 2 );
function custom_price_format( $price, $product ) {

    // Main Price
    $regular_price = $product->is_type('variable') ? $product->get_variation_regular_price( 'max', true ) : $product->get_regular_price();
    $sale_price = $product->is_type('variable') ? $product->get_variation_sale_price( 'max', true ) : $product->get_sale_price();


    if ( $regular_price !== $sale_price && $product->is_on_sale()) {
        // Percentage calculation and text
        $percentage = round( ( $regular_price - $sale_price ) / $regular_price * 100 ).'%';
      //  $percentage_txt = __(' Save', 'woocommerce' ).' '.$percentage;
      //  $price = '<del>' . wc_price($regular_price) . '</del> <ins>' . wc_price($sale_price) . $percentage_txt . '</ins>';
        $price = '<del class="32">' . wc_price($regular_price) . '</del> <ins class="22">' . wc_price($sale_price)  . '</ins>';
    }
    return $price;
}



/**

* remove vers and defer parsing 

*/


function _remove_script_version( $src ){ 
$parts = explode( '?', $src );  
return $parts[0]; 
} 
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 ); 
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );



if (!(is_admin() )) {
    function defer_parsing_of_js ( $url ) {
        if ( FALSE === strpos( $url, '.js' ) ) return $url;
        if ( strpos( $url, 'jquery.js' ) ) return $url;
        // return "$url' defer ";
        return "$url' defer onload='";
    }
    add_filter( 'clean_url', 'defer_parsing_of_js', 11, 1 );
}

/**

* add badge on single product page

*/

add_action( 'woocommerce_share', 'add_badges_single_product_page');
function add_badges_single_product_page() {
echo '<div class="product-badge"><img src="/wp-content/uploads/2018/07/badgesresize.png" /></div>';
}


/**

* add partent theme style on child theme

*/

add_action( 'wp_enqueue_scripts', 'my_child_theme_scripts' );
function my_child_theme_scripts() {
    wp_enqueue_style( 'parent-theme-css', get_template_directory_uri() . '/style.css' );
}


/**

* change read more to buy now

*/

add_filter( 'woocommerce_product_add_to_cart_text', function( $text ) {
    global $product;
    if ( $product->is_type( 'variable' ) ) {
        $text = $product->is_purchasable() ? __( 'Buy Now', 'woocommerce' ) : __( 'Read more', 'woocommerce' );
    }
    return $text;
}, 10 );


/**

* prices display on cat page

*/


add_filter( 'woocommerce_get_price_html', 'hide_price', 10, 2 );

function hide_price( $price, $product ) {


    $hide_for_products = array( 10988 , 6686 );

    if ( in_array( $product->get_id(), $hide_for_products ) ) {

  //     $regular_price = $product->is_type('variable') ? $product->get_variation_regular_price( 'max', true ) : $product->get_regular_price();
         $sale_price = $product->is_type('variable') ? $product->get_variation_sale_price( 'min', true ) : $product->get_sale_price();
  //     $price = '<del class="2a2">' . wc_price($regular_price) . '</del> <ins class="2b2">' . wc_price($sale_price)  . '</ins>';
         $price = ' <ins class="test21">' . woocommerce_price($sale_price) . '</ins>'; 
         return $price;
      
    }


 else{

  // $sale_price = $product->is_type('variable') ? $product->get_variation_sale_price( 'max', true) : $product->get_sale_price();
    $sale_price =  $product->is_type('variable') ? $product->get_variation_sale_price( 'max', true) : $product->get_sale_price() ;
    $price = ' <ins class="test1">' . woocommerce_price($sale_price) . '</ins>'; 
    return $price;


}
  return $price;

    
} 


/**

* Prices display on single product page

*/

add_action( 'woocommerce_before_single_product', 'move_variations_single_price', 1 );
function move_variations_single_price(){
    global $product, $post;

    if ( $product->is_type( 'variable' ) ) {
        // removing the variations price for variable products
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

        // Change location and inserting back the variations price
        add_action( 'woocommerce_single_product_summary', 'replace_variation_single_price', 10 );
    }
}

function replace_variation_single_price(){
    global $product;

    // Main Price max
    $prices = array( $product->get_variation_price( 'max', true ), $product->get_variation_price( 'max', true ) );
    $price = $prices[0] !== $prices[1] ? sprintf( __( '', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

    // Sale Price
    $prices = array( $product->get_variation_regular_price( 'max', true ), $product->get_variation_regular_price( 'max', true ) );
    sort( $prices );
    $saleprice = $prices[0] !== $prices[1] ? sprintf( __( '', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );


    // Main Price min
    $prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'min', true ) );
    $mprice = $prices[0] !== $prices[1] ? sprintf( __( '', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

    if ( $price !== $saleprice && $product->is_on_sale()  ) {

        $price = ' <ins class="out">' . $price . $product->get_price_suffix() . '</ins>';
        $price1 = '<ins class="tito">' . $mprice . $product->get_price_suffix() . '</ins>';
       // $price1 = '<del>' . $saleprice . $product->get_price_suffix() . '</del> <ins>' . $price . $product->get_price_suffix() . '</ins>';
    } 
 

    ?>
    <style>
        div.woocommerce-variation-price,
        div.woocommerce-variation-availability,
        div.hidden-variable-price {
            height: 0px !important;
            overflow:hidden;
            position:relative;
            line-height: 0px !important;
            font-size: 0% !important;
        }
    </style>
    <script>
    jQuery(document).ready(function($) {
        $('select').blur( function(){
            if( '' != $('input.variation_id').val() ){
                if($('p.availability'))
                    $('p.availability').remove();
                $('p.price').html($('div.woocommerce-variation-price > span.price').html()).append('<p class="availability">'+$('div.woocommerce-variation-availability').html()+'</p>');
                console.log($('input.variation_id').val());
            } else {
                $('p.price').html($('div.hidden-variable-price').html());
                if($('p.availability'))
                    $('p.availability').remove();
                console.log('NULL');
            }
        });
    });
    </script>
    <?php

$hide_for_products = array( 10988 , 6686);

    if ( in_array( $product->get_id(), $hide_for_products )  ){
       echo '<p class="price">'.$price1.'</p>
    <div class="hidden-variable-price" >'.$price1.'</div>';
    }

else{
   echo '<p class="price">'.$price.'</p>
    <div class="hidden-variable-price" >'.$price.'</div>';
}
}

/**
 * Rename product data tabs
 */
add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {

    
    $tabs['additional_information']['title'] = __( 'Product Specification' );   // Rename the additional information tab

    return $tabs;

}

/*   


product badge on single product page

*/

/*
add_action( 'woocommerce_product_thumbnails', 'bbloomer_img_action_5' );
function bbloomer_img_action_5() {

global $product;

$hide_for_products = array( 1862  );
if ( in_array( $product->get_id(), $hide_for_products ) ){
    echo '<img src="/wp-content/uploads/2018/07/5.png" class="aligncenter" style="position: absolute;top: 0em;z-index: 100;width: 116px;left: 1em;" />';
}
}

*/


/*   


product badge on cat page

*/
/*
add_action( 'woocommerce_before_shop_loop_item_title', 'bbloomer_cum_act_5', 15 );
 
function bbloomer_cum_act_5() {
    global $product;

$hide_for_products = array( 1862   );
if ( in_array( $product->get_id(), $hide_for_products ) ){
echo '<img src="/wp-content/uploads/2018/07/5.png" class="aligncenter cat-badge" style="" />';

}
}
*/


/**
 * Exclude products from a particular category on the shop page
 */
function custom_pre_get_posts_query( $q ) {

    $tax_query = (array) $q->get( 'tax_query' );

    $tax_query[] = array(
           'taxonomy' => 'product_cat',
           'field' => 'slug',
           'terms' => array( 'bundle' ), // Don't display products in the clothing category on the shop page.
           'operator' => 'NOT IN'
    );


    $q->set( 'tax_query', $tax_query );

}
add_action( 'woocommerce_product_query', 'custom_pre_get_posts_query' );  

/**
 * Change name of fliter text
 */


add_filter('woocommerce_catalog_orderby', 'wc_customize_product_sorting');

function wc_customize_product_sorting($sorting_options){
    $sorting_options = array(
        'menu_order' => __( 'Sorting', 'woocommerce' ),
        'popularity' => __( 'Sort by popularity', 'woocommerce' ),
        'rating'     => __( 'Sort by average rating', 'woocommerce' ),
        'date'       => __( 'Sort by latest', 'woocommerce' ),
        'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
        'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
    );

    return $sorting_options;
}

/**
* For Remove Flat Rate Shipping Label on Cart Page
*/
add_filter( 'woocommerce_cart_shipping_method_full_label', 'wdo_remove_shipping_label_cart_page', 10, 2 );
function wdo_remove_shipping_label_cart_page($label, $method) {
    $shipping_label = preg_replace( '/^.+:/', '', $label );
    return $shipping_label;
}

/**
* For Remove Flat Rate Shipping Label on Thank you ( Order Placed ) page and Email
*/

add_filter( 'woocommerce_order_shipping_to_display_shipped_via', 'wdo_remove_shipping_label_thnakyou_page_cart', 10, 2 );
function wdo_remove_shipping_label_thnakyou_page_cart($label, $method) {
    $shipping_label = '';
    return $shipping_label;
}



add_action( 'woocommerce_single_product_summary', 'custom_button_after_product_summary', 30 );

function custom_button_after_product_summary() {
  global $product;
 /* echo'apply_filters( 'woocommerce_loop_add_to_cart_link',
    sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button %s product_type_%s">%s</a>',
        esc_url( $product->add_to_cart_url() ),
        esc_attr( $product->get_id() ),
        esc_attr( $product->get_sku() ),
        $product->is_purchasable() ? 'add_to_cart_button' : '',
        esc_attr( $product->get_product_type() ),
        esc_html( $product->add_to_cart_text() )
    ),
$product );'; */

   //3836

   //echo "<a href='".$product->add_to_cart_url()."'>add to cart 2</a>";

   echo "<a href='http://yourdomain.com/?add-to-cart=3836'>add to cart 2</a>";
}


function add_content_after_addtocart() {

    // get the current post/product ID
    $current_product_id = get_the_ID();

    // get the product based on the ID
    $product = wc_get_product( $current_product_id );

    // get the "Checkout Page" URL
    $checkout_url = WC()->cart->get_checkout_url();

    // run only on simple products
 //   if( $product->is_type( 'simple' ) ){
        echo '<a href="'.$checkout_url.'?add-to-cart='.$current_product_id.'" class="single_add_to_cart_button button alt">Checkout</a>';
 //   }
}
add_action( 'woocommerce_after_add_to_cart_button', 'add_content_after_addtocart' );
