<?php
require_once( ABSPATH . 'wp-load.php' );
$pdf_template_title = $_POST['pdf_template_title'];
$id = $_POST['id'];

$post_title =  get_the_title( $id );
$new_title = sanitize_title($post_title);
$file_name = $id."_".$new_title.".pdf";

if( have_rows('html_templates', 'option') ): 
    while ( have_rows('html_templates', 'option') ) : the_row();    
        $template_title = get_sub_field('templates_title');
        if($template_title==$pdf_template_title) { 
            $html_text =  get_sub_field('template_content'); 
        } 
    endwhile;
endif;

$car_price=cardealer_get_car_price_array($id);

$car_images = get_field('car_images',$id);

$citympg = get_field('city_mpg',$id);
$city_mpg = (isset($citympg) && !empty($citympg))? $citympg=$citympg : '';

$highwaympg = get_field('highway_mpg',$id);
$high_waympg = (isset($highwaympg) && !empty($highwaympg))? $high_waympg=$highwaympg : '';

$caryear = wp_get_post_terms($id, 'car_year');
$car_year = (isset($caryear) && !empty($caryear))? $caryear[0]->name : '';

$carmake = wp_get_post_terms($id, 'car_make');
$car_make = (isset($carmake) && !empty($carmake))? $carmake[0]->name : '';

$carmodel = wp_get_post_terms($id, 'car_model');
$car_model = (isset($carmodel) && !empty($carmodel))? $carmodel[0]->name : '';

$bodystyle = wp_get_post_terms($id, 'car_body_style');
$body_style = (isset($bodystyle) && !empty($bodystyle))? $bodystyle[0]->name : '';

$condition = wp_get_post_terms($id, 'car_condition');
$car_condition = (isset($carmodel) && !empty($condition))? $condition[0]->name : '';

$mileage = wp_get_post_terms($id, 'car_mileage');
$car_mileage = (isset($mileage) && !empty($mileage))? $mileage[0]->name : '';

$transmission = wp_get_post_terms($id, 'car_transmission');
$car_transmission = (isset($transmission) && !empty($transmission))? $transmission[0]->name : '';

$drivetrain = wp_get_post_terms($id, 'car_drivetrain');
$car_drivetrain = (isset($drivetrain) && !empty($drivetrain))? $drivetrain[0]->name : '';

$engine = wp_get_post_terms($id, 'car_engine');
$car_engine = (isset($engine) && !empty($engine))? $engine[0]->name : '';

$fuel_economy = wp_get_post_terms($id, 'car_fuel_economy');
$car_fuel_economy = (isset($fuel_economy) && !empty($fuel_economy))? $fuel_economy[0]->name : '';

$exterior_color = wp_get_post_terms($id, 'car_exterior_color');
$car_exterior_color = (isset($exterior_color) && !empty($exterior_color))? $exterior_color[0]->name : '';

$interior_color = wp_get_post_terms($id, 'car_interior_color');
$car_interior_color = (isset($interior_color) && !empty($interior_color))? $interior_color[0]->name : '';

$carstock = wp_get_post_terms($id, 'car_stock_number');
$car_stock_number = (isset($carstock) && !empty($carstock))? $carstock[0]->name : '';

$vin_number = wp_get_post_terms($id, 'car_vin_number');
$car_vin_number= (isset($vin_number) && !empty($vin_number))? $vin_number[0]->name : '';

$trim = wp_get_post_terms($id, 'car_trim');
$car_trim= (isset($trim) && !empty($trim))? $trim[0]->name : '';

$fuel_type = wp_get_post_terms($id, 'car_fuel_type');
$car_fuel_type = (isset($fuel_type) && !empty($fuel_type))? $fuel_type[0]->name : '';

$option="";
$features_options = wp_get_post_terms($id, 'car_features_options');
foreach($features_options as $features_option){
    $option .= '<li>'.$features_option->name.'</li>';     
}        
        
$attributes = array();
$attribute['year']=$car_year;
$attribute['make']=$car_make;
$attribute['model']=$car_model;
$attribute['body_style']=$body_style;
$attribute['condition']=$car_condition;
$attribute['mileage']=$car_mileage;
$attribute['transmission']=$car_transmission;
$attribute['drivetrain']=$car_drivetrain;
$attribute['engine']=$car_engine;
$attribute['fuel_economy']=$car_fuel_economy;
$attribute['exterior_color']=$car_exterior_color; 
$attribute['interior_color']=$car_interior_color;
$attribute['stock_number']=$car_stock_number;
$attribute['vin_number']=$car_vin_number;
$attribute['features_options']=$option;
$attribute['trim']=$car_trim;
$attribute['city_mpg']=$city_mpg;
$attribute['high_waympg']=$high_waympg;
$attribute['fuel_type']=$car_fuel_type;

foreach($attribute as $key => $val){
	$html_text = str_replace('{{'.$key.'}}',$val,$html_text); 
}
       
      
//============================================================+
// File name   : example_061.php
// Begin       : 2010-05-24
// Last Update : 2014-01-25
//
// Description : Example 061 for TCPDF class
//               XHTML + CSS
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: XHTML + CSS
 * @author Nicola Asuni
 * @since 2010-05-25
 */

// Include the main TCPDF library (search for installation path).
require_once('pdf/examples/tcpdf_include.php');
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('cardealer');
// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'pdf/examples/lang/eng.php')) {
require_once(dirname(__FILE__).'pdf/examples/lang/eng.php');
$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

/* NOTE:
 * *********************************************************
 * You can load external XHTML using :
 *
 * $html = file_get_contents('/path/to/your/file.html');
 *
 * External CSS files will be automatically loaded.
 * Sometimes you need to fix the path of the external CSS.
 * *********************************************************
 */

// define some HTML content with style
$html_ul = "";
if(isset($attribute['features_options']) && !empty($attribute['features_options'])){
    $myArray = explode('-', $attribute['features_options']);
    $counter=sizeof($myArray);
    $html_ul= "<ul>";  $i="0"; 
    foreach( $myArray as $feature):
    if($i!=0){ 
        $html_ul.= "<li>".$feature."</li>";
       }
       $i++;
    endforeach; 
    $html_ul.= "</ul>";     
}
                           
if( isset($car_images) && !empty($car_images) ){                    
    foreach($car_images as $image ):
        $carimage=$image['url'];
        break;
    endforeach;                                 
}

if( isset($car_price) && !empty($car_price) ){                   
     $regprice=$car_price['regular_price'];
     $saleprice=$car_price['sale_price'];
     $symbol=$car_price['currency_symbol'];  
}

$fuel_image=plugin_dir_url('').'cardealer-helper-library/images/pdf/fuel.jpg';
$city_image=plugin_dir_url('').'cardealer-helper-library/images/pdf/city.jpg';
$hwy_image =plugin_dir_url('').'cardealer-helper-library/images/pdf/hwy.jpg';
$newhtml = '';
$newhtml = str_replace('{{year}}',$car_year,$html_text);  
$newhtml = str_replace('{{make}}',$car_make,$newhtml);
$newhtml = str_replace('{{model}}',$car_model,$newhtml);
$newhtml = str_replace('{{body_style}}',$body_style,$newhtml);
$newhtml = str_replace('{{condition}}',$car_condition,$newhtml);
$newhtml = str_replace('{{mileage}}',$car_mileage,$newhtml);
$newhtml = str_replace('{{transmission}}',$car_transmission,$newhtml);
$newhtml = str_replace('{{drivetrain}}',$car_drivetrain,$newhtml);
$newhtml = str_replace('{{engine}}',$car_engine,$newhtml);
$newhtml = str_replace('{{fuel_economy}}',$car_fuel_economy,$newhtml);
$newhtml = str_replace('{{exterior_color}}',$car_exterior_color,$newhtml);
$newhtml = str_replace('{{interior_color}}',$car_interior_color,$newhtml);
$newhtml = str_replace('{{stock_number}}',$car_stock_number,$newhtml);
$newhtml = str_replace('{{vin_number}}',$car_vin_number,$newhtml);
$newhtml = str_replace('{{features_options}}',$html_ul,$newhtml);
$newhtml = str_replace('{{city_mpg}}',$city_mpg,$newhtml);
$newhtml = str_replace('{{high_waympg}}',$high_waympg,$newhtml);
$newhtml = str_replace('{{image}}',$carimage,$newhtml); 
$newhtml = str_replace('{{trim}}',$car_trim,$newhtml); 
$newhtml = str_replace('{{fuel_image}}',$fuel_image,$newhtml);
$newhtml = str_replace('{{city_image}}',$city_image,$newhtml);
$newhtml = str_replace('{{hwy_image}}',$hwy_image,$newhtml);
$newhtml = str_replace('{{fuel_type}}',$car_fuel_type,$newhtml);
$newhtml = str_replace('{{regular_price}}',$regprice,$newhtml);
$newhtml = str_replace('{{sale_price}}',$saleprice,$newhtml);
$newhtml = str_replace('{{currency_symbol}}',$symbol,$newhtml);

// output the HTML content
$pdf->writeHTML($newhtml, true, false, true, false, '');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output(plugin_dir_path( __FILE__ )."pdf/examples/uploads/".$file_name, 'F');


// Need to require these files
if ( !function_exists('media_handle_upload') ) {	
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
}

$url = CDHL_URL.'includes/pdf_generator/pdf/examples/uploads/'.$file_name;	
$tmp = download_url( $url );

if( is_wp_error( $tmp ) ){
	// download failed, handle error
}
$post_id = $id;
$file_array = array();

// Set variables for storage
// fix file filename for query strings
preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png|pdf)/i', $url, $matches);
$file_array['name'] = basename($matches[0]);
$file_array['tmp_name'] = $tmp;

// If error storing temporarily, unlink
if ( is_wp_error( $tmp ) ) {
	if( file_exists($file_array['tmp_name']) ) {
		@unlink($file_array['tmp_name']);
	}
	$file_array['tmp_name'] = '';
}
// do the validation and storage stuff
$iid = media_handle_sideload( $file_array, $post_id, $post_title );
// If error storing permanently, unlink
if ( is_wp_error($iid) ) {
	if( file_exists($file_array['tmp_name']) ) {
		@unlink($file_array['tmp_name']);
		return $iid;
	}
}

// change the file attachment for pdf brochure
update_post_meta( $post_id, 'pdf_file', $iid);
$src = wp_get_attachment_url( $iid );

// unlink pdf file generated inside CDHL plugin
if( isset( $src ) ){
	if( file_exists( plugin_dir_path( __FILE__ )."pdf/examples/uploads/".$file_name ) ) { 
		@unlink( plugin_dir_path( __FILE__ )."pdf/examples/uploads/".$file_name );
	}
}
echo $src;
die;