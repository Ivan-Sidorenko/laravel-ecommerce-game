<div id="content">

    <div id="contentHeader">Order Confirmation Page</div>
	
	<?php $this->renderContent('common/form_error'); ?>
	
    <p>
        This page summarises the order details and customer information gathered on the previous screens.
        It is always a good idea to show your customers a page like this to allow them to go back and edit
        either basket or contact details.
    </p>

    <p>Once you press the proceed button, the kit will send a registration post to the following remote URL:</p>

    <p><b><?php echo $purchaseUrl; ?></b></p>

    <p>
        This kit is configured in <b><?php echo $env; ?></b> mode.
        <?php
        if ($integrationType == SAGEPAY_ENV_LIVE)
        {
            ?>
            so clicking Proceed will register your transaction with Sage Pay Server
            and automatically redirect you to the payment page, or handle any registration errors.
        <?php }; ?>
    </p>

    <div class="greyHzShadeBar">&nbsp;</div>
    <table class="formTable">
        <tr>
            <td colspan="5">
                <div class="subheader">Your Basket Contents</div>
            </td>
        </tr>
        <tr class="greybar">
            <td width="17%" align="center">Image</td>
            <td width="45%" align="left">Title</td>
            <td width="15%" align="right">Price</td>
            <td width="8%" align="right">Quantity</td>
            <td width="15%" align="right">Total</td>
        </tr>

        <?php
        foreach ($basket['items'] as $item)
        {
            ?>
            <tr>
                <td align="center"><img src="<?php echo $item['urlImage']; ?>" alt="DVD box"></td>
                <td align="left"><?php echo $item['description']; ?></td>
                <td align="right"><?php echo $item['unitGrossAmount'] . ' ' . $currency ?></td>
                <td align="right"><?php echo $item['quantity'] ?></td>
                <td align="right"><?php echo $item['totalGrossAmount'] . ' ' . $currency ?></td>
            </tr>
        <?php } ?>

        <tr>
            <td colspan="4" align="right">Delivery:</td>
            <td align="right"><?php echo $deliveryGrossPrice; ?></td>
        </tr>
        <tr>
            <td colspan="4" align="right"><strong>Total:</strong></td>
            <td align="right"><strong><?php echo $totalGrossPrice; ?></strong></td>
        </tr>
    </table>

    <table class="formTable">
        <tr>
            <td colspan="2">
                <div class="subheader">Your Billing Details</div>
            </td>
        </tr>
        <tr>
            <td class="fieldLabel">Name:</td>
            <td class="fieldData"><?php echo $details['BillingFirstnames'] ?>&nbsp;<?php echo $details['BillingSurname']; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">Address Details:</td>
            <td class="fieldData">
                <?php echo $details['BillingAddress1']; ?><br>
                <?php echo $details['BillingAddress2']; ?>
                <?php echo $details['BillingCity']; ?><br>
                <?php echo $details['BillingState']; ?>
                <?php echo $details['BillingPostCode']; ?>
                <script type="text/javascript" language="javascript">
                    document.write(getCountryName("<?php echo $details['BillingCountry']; ?>"));
                </script>
            </td>
        </tr>
        <tr>
            <td class="fieldLabel">Phone Number:</td>
            <td class="fieldData"><?php echo $details['BillingPhone']; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">e-Mail Address:</td>
            <td class="fieldData"><?php echo $details['customerEmail']; ?></td>
        </tr>
    </table>

    <table class="formTable">
        <tr>
            <td colspan="2">
                <div class="subheader">Your Delivery Details</div>
            </td>
        </tr>
        <tr>
            <td class="fieldLabel">Name:</td>
            <td class="fieldData"><?php echo $details['DeliveryFirstnames']; ?>&nbsp;<?php echo $details['DeliverySurname']; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">Address Details:</td>
            <td class="fieldData">
                <?php echo $details['DeliveryAddress1']; ?><br>
                <?php echo $details['DeliveryAddress2']; ?>
                <?php echo $details['DeliveryCity']; ?><br>
                <?php echo $details['DeliveryState']; ?>
                <?php echo $details['DeliveryPostCode']; ?>
                <script type="text/javascript" language="javascript">
                    document.write(getCountryName("<?php echo $details['DeliveryCountry']; ?>"));
                </script>
            </td>
        </tr>
        <tr>
            <td class="fieldLabel">Phone Number:</td>
            <td class="fieldData"><?php echo $details['DeliveryPhone']; ?>&nbsp;</td>
        </tr>
    </table>

    <?php
    if ($integrationType == SAGEPAY_DIRECT && !empty($card['cardType']) && $card['cardType'] != 'PAYPAL')
    {
        ?>
        <table class="formTable">
            <tr>
                <td colspan="2">
                    <div class="subheader">Your Card Details</div>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Card Holder:</td>
                <td class="fieldData"><?php echo $card['cardHolder']; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Card Number:</td>
                <td class="fieldData"><?php echo $card['cardNumber']; ?></td>
            </tr>
            <?php
            if (!empty($card['startDate']))
            {
                ?>
                <tr>
                    <td class="fieldLabel">Start Date</td>
                    <td class="fieldData"><?php echo $card['startDate']; ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td class="fieldLabel">Expiry Date:</td>
                <td class="fieldData"><?php echo $card['expiryDate']; ?></td>
            </tr>
        </table>
    <?php } ?>

    <div class="greyHzShadeBar">&nbsp;</div>

    <p>Clicking "Proceed" causes the application to POST to <br /><b><?php echo $purchaseUrl; ?></b></p>

    <div class="formFooter">
        <a href="<?php echo $backUrl; ?>" style="float: left;">
            <img src="<?php echo BASE_PATH; ?>assets/images/back.gif" alt="Go back to the previous page" border="0" />
        </a>
        <form action="<?php echo $perceedUrl; ?>" method="post">
            <input type="image" style="float: right" src="<?php echo BASE_PATH; ?>assets/images/proceed.gif" alt="Proceed to the next page" />
        </form>
    </div>

</div>
