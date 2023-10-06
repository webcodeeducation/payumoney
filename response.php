<?php
include_once('config.php');
include('templates/header.php'); 
$postdata = $_POST;
$msg = '';
if (isset($postdata ['key'])) {
	$key				=   $postdata['key'];
	$salt				=   SALT;
	$txnid 				= 	$postdata['txnid'];
    $amount      		= 	$postdata['amount'];
	$productInfo  		= 	$postdata['productinfo'];
	$firstname    		= 	$postdata['firstname'];
	$email        		=	$postdata['email'];
	$udf5				=   $postdata['udf5'];
	$mihpayid			=	$postdata['mihpayid'];
	$status				= 	$postdata['status'];
	$resphash			= $postdata['hash'];

	//Calculate response hash to verify	
	$keyString 	  		=  	$key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||'.$udf5.'|||||';
	$keyArray 	  		= 	explode("|",$keyString);
	$reverseKeyArray 	= 	array_reverse($keyArray);
	$reverseKeyString	=	implode("|",$reverseKeyArray);
	$CalcHashString 	= 	strtolower(hash('sha512', $salt.'|'.$status.'|'.$reverseKeyString));
	
	
	if ($status == 'success'  && $resphash == $CalcHashString) {
		$msg = '<div class="alert alert-success" role="alert">Transaction Successful and Hash Verified...</div>';
		//Do success order processing here...
	}
	else {
		//tampered or failed
		$msg = '<div class="alert alert-danger" role="alert">Payment failed for Hasn not verified...</div>';
	} 
}
else exit(0);
?>
<section class="showcase">
  <div class="container">
    <div class="pb-2 mt-4 mb-2 border-bottom">
      <h2>PayUMoney Payment Gateway Integration in PHP</h2>
    </div>
    <div class="row">       
      <div class="col-md-12"><?php echo $msg; ?></div>
    </div>

    <div class="row">       
      <div class="col-md-12">Merchant Key: <?php echo $key; ?></div>
    </div>

    <div class="row">       
      <div class="col-md-12">Merchant Salt: <?php echo $salt; ?></div>
    </div>

    <div class="row">       
      <div class="col-md-12">Transaction/Order ID: <?php echo $txnid; ?></div>
    </div>

    <div class="row">       
      <div class="col-md-12">Amount: <?php echo $amount; ?></div>
    </div>

    <div class="row">       
      <div class="col-md-12">Product Info: <?php echo $productInfo; ?></div>
    </div>

    <div class="row">       
      <div class="col-md-12">Name: <?php echo $firstname; ?></div>
    </div>

    <div class="row">       
      <div class="col-md-12">Emaul ID: <?php echo $email; ?></div>
    </div>

    <div class="row">       
      <div class="col-md-12">Mihpayid: <?php echo $mihpayid; ?></div>
    </div>

    <div class="row">       
      <div class="col-md-12">Hash: <?php echo $resphash; ?></div>
    </div>

    <div class="row">       
      <div class="col-md-12">Transaction Status: <?php echo $status; ?></div>
    </div>
</div>
</section>
<?php include('templates/footer.php'); ?>