<?php 
include_once('config.php');
include('templates/header.php'); ?>
<?php
function getCallbackUrl()
{
  $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . 'response.php';
}
?>
<section class="showcase">
  <div class="container">
    <div class="pb-2 mt-4 mb-2 border-bottom">
      <h2>PayUMoney Payment Gateway Integration in PHP</h2>
    </div>
    <div class="row">       
      <div class="col-md-12 gedf-main">
        <form action="#" id="payment_form">
          <input type="hidden" id="udf5" name="udf5" value="BOLT_KIT_PHP7" />
          <input type="hidden" id="surl" name="surl" value="<?php echo getCallbackUrl(); ?>" />
          <input type="hidden" id="key" name="key" placeholder="Merchant Key" value="<?php print MERCHANT_KEY;?>" />
          <input type="hidden" id="salt" name="salt" placeholder="Merchant Salt" value="<?php print SALT; ?>" />
          <input type="hidden" id="txnid" name="txnid" placeholder="Transaction ID" value="<?php echo  "Txn" . rand(10000,99999999)?>" />
          
          <div class="row align-items-center">
            <div class="form-group col-md-6">
              <label for="inputEmail4">Amount</label>
              <input type="number" class="form-control" id="amount" name="amount" placeholder="Amount" value="1.00">
              </div>

            <div class="form-group col-md-6">
              <label for="inputEmail4">Product Info</label>
              <input type="text" class="form-control" id="pinfo" name="pinfo" placeholder="Product Info" value="Product-001">
            </div>
          </div>

        <div class="row align-items-center">
           <div class="form-group col-md-6">
            <label for="inputEmail4">Frist Name</label>
            <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" value="">
          </div>

          <div class="form-group col-md-6">
            <label for="inputEmail4">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email ID" value="">
          </div>
        </div>

        <div class="row align-items-center">
          <div class="form-group col-md-6">
            <label for="inputEmail4">Mobile/Cell Number</label>
            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile/Cell Number" value="">
          </div>
        </div>
            
        <div class="row justify-content-start mt-4">
          <div class="col">
            <button type="submit" class="btn btn-primary mt-4 float-right" onclick="launchBOLT(); return false;">Pay Now</button>
          </div>
        </div>

        <input type="hidden" id="hash" name="hash" placeholder="Hash" value="" />
        </form>
           <hr>
        <div class="row">
          <div class="col-md-3">(Visa) Card Name: Test</div>
          <div class="col-md-3">Card Number: 4012001037141112</div>          
          <div class="col-md-3">Expiry Date : <?php  print date('m/y',strtotime('+1 years', time()));?></div>          
          <div class="col-md-3">CVV : 123</div>
        </div>

        <div class="row">
          <div class="col-md-3">(Master) Card Name: Test</div>          
          <div class="col-md-3">Card Number: 5123456789012346</div>          
          <div class="col-md-3">Expiry Date : <?php  print date('m/y',strtotime('+1 years', time()));?></div>          
          <div class="col-md-3">CVV : 123</div>
        </div>

      </div>       
    </div>
  </div>
</section>
<?php include('templates/footer.php'); ?>
<!-- BOLT Sandbox/test //-->
<script id="bolt" src="https://sboxcheckout-static.citruspay.com/bolt/run/bolt.min.js" bolt-
color="e34524" bolt-logo="http://webcodeservices.in/wp-content/uploads/2020/06/cropped-wce2.png"></script>
<!-- BOLT Production/Live //-->
<!--// script id="bolt" src="https://checkout-static.citruspay.com/bolt/run/bolt.min.js" bolt-color="e34524" bolt-logo="http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png"></script //-->
<script type="text/javascript"><!--
  $('#payment_form').bind('keyup blur', function(){
    $.ajax({
      url: 'request.php',
      type: 'post',
      data: JSON.stringify({ 
        key: $('#key').val(),
        salt: $('#salt').val(),
        txnid: $('#txnid').val(),
        amount: $('#amount').val(),
        pinfo: $('#pinfo').val(),
        fname: $('#fname').val(),
        email: $('#email').val(),
        mobile: $('#mobile').val(),
        udf5: $('#udf5').val()
      }),
      contentType: "application/json",
      dataType: 'json',
      success: function(json) {
      if (json['error']) {
        $('#alertinfo').html('<i class="fa fa-info-circle"></i>'+json['error']);
      }
      else if (json['success']) { 
        $('#hash').val(json['success']);
      }
      }
    }); 
});
//-->
</script>
<script type="text/javascript"><!--
  function launchBOLT() {
    bolt.launch({
      key: $('#key').val(),
      salt: $('#salt').val(),
      txnid: $('#txnid').val(), 
      hash: $('#hash').val(),
      amount: $('#amount').val(),
      firstname: $('#fname').val(),
      email: $('#email').val(),
      phone: $('#mobile').val(),
      productinfo: $('#pinfo').val(),
      udf5: $('#udf5').val(),
      surl : $('#surl').val(),
      furl: $('#surl').val(),
      mode: 'dropout' 
    },
    { 
      responseHandler: function(BOLT){
      console.log( BOLT.response.txnStatus );   
    if(BOLT.response.txnStatus != 'CANCEL') {
      //Salt is passd here for demo purpose only. For practical use keep salt at server side only.
      var fr = '<form action=\"'+$('#surl').val()+'\" method=\"post\">' +
      '<input type=\"hidden\" name=\"key\" value=\"'+BOLT.response.key+'\" />' +
      '<input type=\"hidden\" name=\"salt\" value=\"'+$('#salt').val()+'\" />' +
      '<input type=\"hidden\" name=\"txnid\" value=\"'+BOLT.response.txnid+'\" />' +
      '<input type=\"hidden\" name=\"amount\" value=\"'+BOLT.response.amount+'\" />' +
      '<input type=\"hidden\" name=\"productinfo\" value=\"'+BOLT.response.productinfo+'\" />' +
      '<input type=\"hidden\" name=\"firstname\" value=\"'+BOLT.response.firstname+'\" />' +
      '<input type=\"hidden\" name=\"email\" value=\"'+BOLT.response.email+'\" />' +
      '<input type=\"hidden\" name=\"udf5\" value=\"'+BOLT.response.udf5+'\" />' +
      '<input type=\"hidden\" name=\"mihpayid\" value=\"'+BOLT.response.mihpayid+'\" />' +
      '<input type=\"hidden\" name=\"status\" value=\"'+BOLT.response.status+'\" />' +
      '<input type=\"hidden\" name=\"hash\" value=\"'+BOLT.response.hash+'\" />' +
      '</form>';
      var form = jQuery(fr);
      jQuery('body').append(form);                
      form.submit();
      }
    },
      catchException: function(BOLT){
      alert( BOLT.message );
    }
    });
  }
//--
</script> 