<?php
session_start();
@session_save_path("./");
include('config.php');
include("libfuncs.php");
include("functions.php");

//Reseller Club Variable
// $key = ""; //replace ur 32 bit secure key , Get your secure key from your Reseller Control panel
$transId = $_GET["transid"];
$sellingCurrencyAmount = $_GET["sellingcurrencyamount"]; //This refers to the amount of transaction in your Selling Currency
$accountingCurrencyAmount = $_GET["accountingcurrencyamount"]; //This refers to the amount of transaction in your Accounting Currency
$redirectUrl = $_GET["redirecturl"];  //This is the URL on our server, to which you need to send the user once you have finished charging him
$checksuma = $_GET["checksum"];

$billing_cust_name = $_GET["name"]; //"";
$billing_cust_address = $_GET["address1"] . ',' . $HTTP_GET_VARS["address2"] . ',' . $HTTP_GET_VARS["address3"]; //"";
$billing_cust_state = $_GET["state"]; //"";
$billing_cust_country = $_GET["country"]; //"";
$billing_cust_tel = $_GET["telNoCc"] . $HTTP_GET_VARS["telNo"]; //"";
$billing_cust_email = $_GET["emailAddr"]; //"";
$delivery_cust_name = "";
$delivery_cust_address = $_GET["address1"] . ',' . $HTTP_GET_VARS["address2"] . ',' . $HTTP_GET_VARS["address3"]; //"";
$delivery_cust_state = $_GET["state"]; //"";
$delivery_cust_country = $_GET["country"]; //"";
$delivery_cust_tel = $_GET["telNoCc"] . $HTTP_GET_VARS["telNo"]; //"";
$delivery_cust_notes = "";
$Merchant_Param = "";
$billing_city = $_GET["city"]; // "";
$billing_zip = $_GET["zip"]; //"";
$delivery_city = $_GET["city"]; //"";
$delivery_zip = $_GET["zip"]; //"";
//CcAavenue variable	
//$Merchant_Id = "" ;//This id(also User Id)  available at "Generate Working Key" of "Settings & Options" 
$Amount = $_GET["sellingcurrencyamount"]; //"1000" ;//your script should substitute the amount in the quotes provided here
$Order_Id = $_GET["transid"];  //"a1235" ;//your script should substitute the order description in the quotes provided here
//$redirect_url = "" ;//your redirect URL where your customer will be redirected after authorisation from CCAvenue
//$working_key = "mksj3rhl1fnxpccbju"  ;//CCAvenue - put in the 32 bit alphanumeric key in the quotes provided here.Please note that get this key ,login to your CCAvenue merchant account and visit the "Generate Working Key" section at the "Settings & Options" page. 
//ccAvenue checksum
$Checksum = getCheckSum($merchant_id, $Amount, $Order_Id, $redirect_url, $working_key);


//echo $paymentTypeId."<br/>Paymen: ". $paymentTypeId."<br/>TranID: ". $transId."<br/>User ID: ". $userId."<br/>Transcation: ". $transactionType."<br/>Invoice ID: ". $invoiceIds."<br/>Debit ID: ". $debitNoteIds."<br/>Desc: ". $description."<br/>Sellcin: ". $sellingCurrencyAmount."<br/>Account: ". $accountingCurrencyAmount."<br/>Key: ". $key."<br/>Check: ". $checksum;
//Reseller Club Verify Checksum
if (verifyChecksum1($paymentTypeId, $transId, $userId, $userType, $transactionType, $invoiceIds, $debitNoteIds, $description, $sellingCurrencyAmount, $accountingCurrencyAmount, $key, $checksuma)) {
    //echo "Verified";
    $_SESSION['redirecturl'] = $redirectUrl;
    $_SESSION['transid'] = $transId;
    $_SESSION['sellingcurrencyamount'] = $sellingCurrencyAmount;
    $_SESSION['accountingcurencyamount'] = $accountingCurrencyAmount;
    $checksumStatus = 1;
    //$BASE_URL="https://www.ccavenue.com/shopzone/cc_details.jsp";
} else {
    $checksumStatus = 0;
    $base_url = "";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CCAvenue Payment Gateway</title>

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom -->
        <link href="css/custom.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body style="background:#ecf0f1;">

        <form name="paymentpage"  method="post" action="<?php echo $base_url; ?>">
            <input type=hidden name=Merchant_Id value="<?php echo $merchant_id; ?>">
            <input type=hidden name=Amount value="<?php echo $Amount; ?>">
            <input type=hidden name=Order_Id value="<?php echo $Order_Id; ?>">
            <input type=hidden name=Redirect_Url value="<?php echo $redirect_url; ?>">
            <!-- CcAvenue Checksum* -->
            <input type=hidden name=Checksum value="<?php echo $Checksum; ?>">
            <input type="hidden" name="billing_cust_name" value="<?php echo $billing_cust_name; ?>"> 
            <input type="hidden" name="billing_cust_address" value="<?php echo $billing_cust_address; ?>"> 
            <input type="hidden" name="billing_cust_country" value="<?php echo $billing_cust_country; ?>"> 
            <input type="hidden" name="billing_cust_state" value="<?php echo $billing_cust_state; ?>"> 
            <input type="hidden" name="billing_zip" value="<?php echo $billing_zip; ?>"> 
            <input type="hidden" name="billing_cust_tel" value="<?php echo $billing_cust_tel; ?>"> 
            <input type="hidden" name="billing_cust_email" value="<?php echo $billing_cust_email; ?>"> 
            <input type="hidden" name="delivery_cust_name" value="<?php echo $delivery_cust_name; ?>"> 
            <input type="hidden" name="delivery_cust_address" value="<?php echo $delivery_cust_address; ?>"> 
            <input type="hidden" name="delivery_cust_country" value="<?php echo $delivery_cust_country; ?>"> 
            <input type="hidden" name="delivery_cust_state" value="<?php echo $delivery_cust_state; ?>"> 
            <input type="hidden" name="delivery_cust_tel" value="<?php echo $delivery_cust_tel; ?>"> 
            <input type="hidden" name="delivery_cust_notes" value="<?php echo $delivery_cust_notes; ?>"> 
            <input type="hidden" name="Merchant_Param" value="<?php echo $Merchant_Param; ?>"> 
            <input type="hidden" name="billing_cust_city" value="<?php echo $billing_city; ?>"> 
            <input type="hidden" name="billing_zip_code" value="<?php echo $billing_zip; ?>"> 
            <input type="hidden" name="delivery_cust_city" value="<?php echo $delivery_city; ?>"> 
            <input type="hidden" name="delivery_zip_code" value="<?php echo $delivery_zip; ?>"> 

        </form>
        <section id="transMessageSec" class="container">

            <!--GIF LOADER-->

            <div class="row"> 
                <div class="col-md-3"></div>
                <div id="loaderCol" class="col-md-6">
                    <center>
                        <img class="img-responsive" src="images/ajax-loader.gif"/>
                    </center>	
                </div>
                <div class="col-md-3"></div>
            </div>

            <!--GIF LOADER-->

            <?php if ($checksumStatus) { ?>
                <!--TRANSACTION MESSAGE-->

                <div class="row">
                    <div id="messageDiv" class="col-md-12">
                        <div class="alert-message alert-message-success text-center">
                            <h4>Transaction is being processed</h4>
                            <p>Please wait while your transaction is being processed ... </p>
                            <p> (Please do not use "Refresh" or "Back" button)</p>
                        </div>
                    </div>
                </div> 

                <!--TRANSACTION MESSAGE-->	
            <?php }  if (!$checksumStatus) { ?>

                <!--NOTIFICATION MESSAGE-->

                <div class="row">
                    <div class="col-sm-3"></div>

                    <div id="messageDiv" class="col-md-6">
                        <center>
                            <div id ="notificationBar" class="alert alert-danger" role="alert">
                                <b>Security Error.</b> Illegal access detected</div>
                        </center>
                    </div>

                    <div class="col-sm-3"></div>
                </div> 

                <!--NOTIFICATION MESSAGE-->	
            <?php } ?>
        </section>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <?php if ($checksumStatus) { ?>
            <script language='javascript'>document.paymentpage.submit();</script>
        <?php } ?>
    </body>
</html>