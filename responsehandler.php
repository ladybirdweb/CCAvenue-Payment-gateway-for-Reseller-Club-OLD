<?php
session_start();
@session_save_path("./");
include('config.php');
include("libfuncs.php");
include("functions.php");
/*

  This is the sample RedirectURL PHP script. It can be directly used for integration with CCAvenue if your application is developed in PHP. You need to simply change the variables to match your variables as well as insert routines for handling a successful or unsuccessful transaction.

  return values i.e the parameters namely Merchant_Id,Order_Id,Amount,AuthDesc,Checksum,billing_cust_name,billing_cust_address,billing_cust_country,billing_cust_tel,billing_cust_email,delivery_cust_name,delivery_cust_address,delivery_cust_tel,billing_cust_notes,Merchant_Param POSTED to this page by CCAvenue.

 */

//$working_key = "" ; //put in the 32 bit working key in the quotes provided here
//$merchant_id= $_REQUEST['Merchant_Id'];
$Amount = $_REQUEST['Amount'];
$Order_Id = $_REQUEST['Order_Id'];
$Merchant_Param = $_REQUEST['Merchant_Param'];
$Checksum1 = $_REQUEST['Checksum'];
$AuthDesc = $_REQUEST['AuthDesc'];

/* Reseller Club Code Starts */
$Checksum = verifychecksum($merchant_id, $Order_Id, $Amount, $AuthDesc, $Checksum1, $working_key);
//$key = ""; //replace ur 32 bit secure key , Get your secure key from your Reseller Control panel


$redirectUrl = $_SESSION['redirecturl'];  // redirectUrl received from foundation
$transId = $_SESSION['transid'];   //Pass the same transid which was passsed to your Gateway URL at the beginning of the transaction.
$sellingCurrencyAmount = $_SESSION['sellingcurrencyamount'];
$accountingCurrencyAmount = $_SESSION['accountingcurencyamount'];


$status = $_REQUEST['AuthDesc']; //$_REQUEST["status"];	 // Transaction status received from your Payment Gateway
//This can be either 'Y' or 'N'. A 'Y' signifies that the Transaction went through SUCCESSFULLY and that the amount has been collected.
//An 'N' on the other hand, signifies that the Transaction FAILED.

/* * HERE YOU HAVE TO VERIFY THAT THE STATUS PASSED FROM YOUR PAYMENT GATEWAY IS VALID.
 * And it has not been tampered with. The data has not been changed since it can * easily be done with HTTP request. 
 *
 * */

srand((double) microtime() * 1000000);
$rkey = rand();


$checksum = generateChecksum($transId, $sellingCurrencyAmount, $accountingCurrencyAmount, $status, $rkey, $key);


$PayuchecksumStatus = 1;
if ($Checksum == "true" && $AuthDesc == "Y") {
    $status = "Y";
    $checksumStatus = 1;
    //echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
    //Here you need to put in the routines for a successful 
    //transaction such as sending an email to customer,
    //setting database status, informing logistics etc etc
} else if ($Checksum == "true" && $AuthDesc == "B") {
    $status = "N";
    $checksumStatus = 0;
    //echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
    //Here you need to put in the routines/e-mail for a  "Batch Processing" order
    //This is only if payment for this transaction has been made by an American Express Card
    //since American Express authorisation status is available only after 5-6 hours by mail from ccavenue and at the "View Pending Orders"
} else if ($Checksum == "true" && $AuthDesc == "N") {
    $status = "N";
    $checksumStatus = 0;
    //echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
    //Here you need to put in the routines for a failed
    //transaction such as sending an email to customer
    //setting database status etc etc
} else {
    $checksumStatus = 0;
    $status = "N";
    //echo "<br>Security Error. Illegal access detected";
    //echo "<br>".$AuthDesc;
    //echo "<br>".$redirectUrl;
    //Here you need to simply ignore this and dont need
    //to perform any operation in this condition
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

        <form name="responsepage" id="responsepage" action="<?php echo $redirectUrl; ?>">
            <input type="hidden" name="transid" value="<?php echo $transId; ?>">
            <input type="hidden" name="status" value="<?php echo $status; ?>">
            <input type="hidden" name="rkey" value="<?php echo $rkey; ?>">
            <input type="hidden" name="checksum" value="<?php echo $checksum; ?>">
            <input type="hidden" name="sellingamount" value="<?php echo $sellingCurrencyAmount; ?>">
            <input type="hidden" name="accountingamount" value="<?php echo $accountingCurrencyAmount; ?>">

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
            <?php if ($PayuchecksumStatus) { ?>
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
                            <div id ="notificationBar" class="alert alert-danger" role="alert"><?php if (isset($redirectUrl)) { ?>
                                    <b>Alert &nbsp;</b>Transcation Failed <?php } if ($Checksum == "false") { ?><b>Security Error.</b> Illegal access detected<?php } ?></div>
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
        <?php if (isset($redirectUrl) && $Checksum == "true") { ?>
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#responsepage").submit();
                });
            </script>
        <?php } ?>

    </body>
</html>