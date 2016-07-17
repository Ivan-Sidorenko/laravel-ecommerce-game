<div id="content">
    <div id="contentHeader">Order Confirmation Page</div>
    <p>This page summarises the order details and customer information gathered on the previous screens.
        It is always a good idea to show your customers a page like this to allow them to go back and edit
        either basket or contact details.<br>
        <br>
        At this stage we also create the Form crypt field and a form to POST this information to
        the Sage Pay Gateway when the Proceed button is clicked.
    </p>
    <p>The URL to post to is:<b><?php echo $purchaseUrl ?></b></p>

    <p>
        <?php
        if ($env != SAGEPAY_ENV_LIVE)
        {
            ?>
            Because you are in <?php echo $env; ?> mode, the unencrypted contents of the crypt field are also
            displayed below, allowing you to check the contents. When you are in Live mode, you will only
            see the order confirmation boxes.
            <?php
        }
        else
        {
            ?>
            Since you are in LIVE mode, clicking Proceed will register your transaction with Form
            and automatically redirect you to the payment page, or handle any registration errors.
        <?php } ?>
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
                <td align="center"><img src="<?php echo $item['productUrlImage']; ?>" alt="DVD box"></td>
                <td align="left"><?php echo $item['description']; ?></td>
                <td align="right"><?php echo $item['unitGrossAmount'] . ' ' . $currency; ?></td>
                <td align="right"><?php echo $item['quantity'] ?></td>
                <td align="right"><?php echo $item['totalGrossAmount'] . ' ' . $currency; ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="4" align="right">Delivery:</td>
            <td align="right"><?php echo $basket['deliveryGrossPrice'] . ' ' . $currency; ?></td>
        </tr>
        <tr>
            <td colspan="4" align="right"><strong>Total:</strong></td>
            <td align="right"><strong><?php echo $basket['totalGrossPrice'] . ' ' . $currency; ?></strong></td>
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
                <?php echo ($details['BillingAddress2'] ? $details['BillingAddress2'] . '<br>' : ''); ?>
                <?php echo $details['BillingCity']; ?><br>
                <?php echo ($details['BillingState'] ? $details['BillingState'] . '<br>' : ''); ?>
                <?php echo ($details['BillingPostCode'] ? $details['BillingPostCode'] . '<br>' : ''); ?>

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
                <?php echo ($details['DeliveryAddress2'] ? $details['DeliveryAddress2'] . '<br>' : ''); ?>
                <?php echo $details['DeliveryCity']; ?><br>
                <?php echo ($details['DeliveryState'] ? $details['DeliveryState'] . '<br>' : ''); ?>
                <?php echo ($details['DeliveryPostCode'] ? $details['DeliveryPostCode'] . '<br>' : ''); ?>
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
    if ($env == SAGEPAY_ENV_TEST)
    {
        ?>
        <table class="formTable">
            <tr>
                <td><div class="subheader">Your Form Crypt Post Contents</div></td>
            </tr>
            <tr>
                <td><p>The text below shows the unencrypted contents of the Form
                        Crypt field.  This application will not display this in LIVE mode.
                        If you wish to view the encrypted and encoded
                        contents view the source of this page and scroll to the bottom.
                        You'll find the submission FORM there.</p></td>
            </tr>
            <tr>
                <td class="code">
                    <div class="protocolMessage">
                        <?php echo $displayQueryString; ?>
                    </div>
                </td>
            </tr>
        </table>
    <?php } ?>

    <div class="greyHzShadeBar">&nbsp;</div>

    <div class="formFooter">
        <table border="0" width="100%">
            <tr>
                <td width="50%" align="left">
                    <a href="<?php echo url(array('form', 'details')); ?>" title="Go back to the customer details page">
                        <img src="<?php echo BASE_PATH; ?>assets/images/back.gif" alt="Go back to the previous page" border="0" />
                    </a>
                </td>
                <td width="50%" align="right">
                    <!-- ************************************************************************************* -->
                    <!-- This form is all that is required to submit the payment information to the system -->
                    <form action="<?php echo $purchaseUrl; ?>" method="post" id="SagePayForm" name="SagePayForm">
                        <?php
                        foreach ($request as $key => $value)
                        {
                            ?>
                            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlentities($value); ?>" />
                        <?php } ?>
                        <input type="image" src="<?php echo BASE_PATH; ?>assets/images/proceed.gif" alt="Proceed to Form registration" />
                    </form>
                    <!-- ************************************************************************************* -->
                </td>
            </tr>
        </table>
    </div>
</div>
