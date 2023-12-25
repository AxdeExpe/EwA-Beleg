<?php
require('stripe-php-master/init.php');
require('./stripe-php-master/init.php');


include 'books.php';

$bookId = $_GET['bookId'];

$public_key_for_js ="1" ; // Definition einer Variable für den public key - Verwendung ganz unten in JS

// #################################################################  
// Definition der Stripe-Account-Keys
if($_GET['live']) {
    // Secret Key des Grosshändlers - bitte so lassen !!!
    \Stripe\Stripe::setApiKey('sk_test_cFnCai0Ye9NM8Tn9CMo6k0fn00P0R9pt9u');

	$public_key_for_js="pk_test_aLcPqdtG2FDzxPWu5N9OBNOs00Yt0nKnhS";  //  PK Großhändler - So lassen !!!!
} else {
      // Der Key Ihres eigenen Stripe-Accounts 
    \Stripe\Stripe::setApiKey('sk_test_51OREORC36J02THDSJqRzCyfAOimB3RTMMOb5j6126e3Yx69FDre0gbMkHz04Ak4Kb3XjIY9sWGdbju60MOVck9WZ00IVbnW19S');
	
	$public_key_for_js="pk_test_51OREORC36J02THDS7v1pjY6BICfVf7OgXq8V7fvZhPSd8iIa9A9Zp3NePwm2uCvl3p6dcyLe1UgbB91ItWeoysjv00mRLr04dx";  // PK  G01 
}
// #################################################################  

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [$books[$bookId]],
        'success_url' => 'http://ivm108.informatik.htw-dresden.de/ewa/Demos/bookstore-stripe-checkout/' . 'success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://ivm108.informatik.htw-dresden.de/ewa/Demos/bookstore-stripe-checkout/' . 'cancel.php',
    ]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo "Error in Session::create()";
}


?>