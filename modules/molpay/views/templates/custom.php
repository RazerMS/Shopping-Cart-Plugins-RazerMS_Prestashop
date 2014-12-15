<html>
    <head>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" />
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" />
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootswatch/3.0.2/cosmo/bootstrap.min.css" />
        <style>
            #logo-molpay, .centerme {
                text-align: center;
            }
            #logo-molpay img {
                display: block;
                margin-left: auto;
                margin-right: auto;
            }
            h2 {
                margin-top: 10px;
                margin-bottom: 30px;
            }
            .btn-lg {
                border-radius: 5px;
            }
        </style>
    </head>
    
    <body>
        <div class="container">
            <div id="logo-molpay">
                <img src="http://molpay.com/v2/images/logo/molpay_logo_400x160_transparent_24bit.png" class="img-responsive img-rounded" alt="MOLPay logo" />
                <?php if($status == '00'): ?>                
                <h2 class="text-success"><i class="fa fa-check-circle"></i> Payment Completed</h2>
                <?php elseif($status == '22'): ?>                
                <h2 class="text-success"><i class="fa fa-check-circle"></i> Awaiting Cash Payment from customer</h2>
                <br />
                <p> Kindly make a payment within 48 hours. </p>
                <?php else: ?>
                <h2 class="text-danger"><i class="fa fa-times-circle"></i> Payment Failed</h2>
                <?php endif; ?>
                <hr>
            </div>
            <form action="<?php echo $_SERVER['REQUEST_URI'] ?>&gotoorder=<?php echo $_POST['orderid'] ?>" method="POST" id="molpay-form">
                <?php foreach($_POST as $name => $value): ?>
                <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />            
                <?php endforeach; ?>
                <div class="centerme">
                    <button type="submit" class="btn btn-info btn-lg">Click here to continue</button>
                </div>                
            </form>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#molpay-form').submit();
                });
            </script>
        </div>        
    </body>
</html>