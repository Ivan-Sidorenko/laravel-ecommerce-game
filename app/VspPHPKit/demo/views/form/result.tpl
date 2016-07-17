<div id="content">

    <?php
    if ($isSuccess)
    {
        ?>
        <div id="contentHeader">Your order has been Successful</div>
        <p>
            The Form transaction has completed successfully
            and the customer has been returned to this order completion page
            <br>
            <br>
            The order number, for your customer's reference is:
            <strong><?php echo $decrypt['VendorTxCode'] ?></strong>
            <br>
            <br>
            They should quote this in all correspondence with you, and likewise you should use this reference
            when sending queries to Sage Pay about this transaction (along with your Vendor Name).<br>
            <?php
            if ($env != SAGEPAY_ENV_LIVE)
            {
                ?>
                <br>
                The table below shows everything sent back from Form about this order.  You would not normally
                show this level of detail to your customers, but it is useful during development.  You may wish to
                store this information in a local database if you have one.<br>
                <br>
                You can customize this page to send confirmation e-mails, display delivery times, present
                download pages, whatever is appropriate for your application.
            <?php } ?>
        </p>
        <?php
    }
    else
    {
        ?>
        <div id="contentHeader">Your order has NOT been successful</div>
        <p>The Form transaction did not completed successfully and the customer has been returned to this completion page for the following reason: <br></p>

        <p class="warning"><strong><?php echo $message ?></strong></p>
        <p>
            The order number, for your customer's reference is: <strong><?php echo $decrypt['VendorTxCode']; ?></strong><br>
            <br>
            They should quote this in all correspondence with you, and likewise you should use this
            reference when sending queries to Sage Pay about this transaction (along with your Vendor Name).<br>
            <br>
            The table below shows everything sent back from Form about this order.  You would not
            normally show this level of detail to your customers, but it is useful during development.
            You may wish to store this information in a local database if you have one.<br>
            <br>
            You can customize this page to suggest alternative payment options, direct the customer to
            call you, or simply present a failure notice, whatever is appropriate for your application.
        </p>
    <?php } ?>

    <div class="greyHzShadeBar">&nbsp;</div>

    <table class="formTable">
        <tr>
            <td colspan="2"><div class="subheader">Details sent back by Form</div></td>
        </tr>
        <tr>
            <td class="fieldLabel">VendorTxCode:</td>
            <td class="fieldData"><?php echo $decrypt['VendorTxCode']; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">Status:</td>
            <td class="fieldData"><?php echo $decrypt['Status']; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">StatusDetail:</td>
            <td class="fieldData"><?php echo $decrypt['StatusDetail'] ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">Amount (Incl. Surcharge):</td>
            <td class="fieldData"><?php echo $decrypt['Amount']; ?></td>
        </tr>
        <?php
        if ($res['Surcharge'])
        {
            ?>
            <tr>
                <td class="fieldLabel">Surcharge:</td>
                <td class="fieldData"><?php echo $res['Surcharge']; ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td class="fieldLabel">VPSTxId:</td>
            <td class="fieldData"><?php echo $res['vpsTxId']; ?></td>
        </tr>
        <?php
        if ($res['txAuthNo'])
        {
            ?>
            <tr>
                <td class="fieldLabel">VPSAuthCode (TxAuthNo):</td>
                <td class="fieldData"><?php echo $res['txAuthNo']; ?></td>
            </tr>
            <?php
        }
        if ($res['BankAuthCode'])
        {
            ?>
            <tr>
                <td class="fieldLabel">Bank Authentication Code:</td>
                <td class="fieldData"><?php echo $res['BankAuthCode']; ?></td>
            </tr>
            <?php
        }
        if ($res['DeclineCode'])
        {
            ?>
            <tr>
                <td class="fieldLabel">Bank Decline Code:</td>
                <td class="fieldData"><?php echo $res['DeclineCode']; ?></td>
            </tr>
            <?php
        }
        if ($res['avsCv2'])
        {
            ?>

            <tr>
                <td class="fieldLabel">AVSCV2 Results:</td>
                <td class="fieldData">
                    <?php echo $res['avsCv2']; ?>
                    <span class="smalltext"> - Address:<?php echo $res['addressResult']; ?>,
                        Post Code:<?php echo $res['postCodeResult']; ?>,
                        CV2:<?php echo $res['cv2Result']; ?></span>
                </td>
            </tr>
            <?php
        }
        if ($res['3DSecureStatus'])
        {
            ?>
            <tr>
                <td class="fieldLabel">3D-Secure Status:</td>
                <td class="fieldData"><?php echo $res['3DSecureStatus']; ?></td>
            </tr>
            <?php
        }
        if ($res['CAVV'])
        {
            ?>
            <tr>
                <td class="fieldLabel">CAVV:</td>
                <td class="fieldData"><?php echo $res['CAVV']; ?></td>
            </tr>
            <?php
        }
        if ($res['cardType'])
        {
            ?>
            <tr>
                <td class="fieldLabel">Card Type:</td>
                <td class="fieldData"><?php echo $res['cardType']; ?></td>
            </tr>
            <?php
        }
        if ($res['last4Digits'])
        {
            ?>
            <tr>
                <td class="fieldLabel">Last 4 Digits:</td>
                <td class="fieldData"><?php echo $res['last4Digits']; ?></td>
            </tr>
            <?php
        }
        if ($res['expiryDate'])
        {
            ?>
            <tr>
                <td class="fieldLabel">Expiry Date:</td>
                <td class="fieldData"><?php echo $res['expiryDate']; ?></td>
            </tr>
        <?php 
        }
        if ($res['cardType'] != 'PAYPAL')
        {
        ?>
        <tr>
            <td class="fieldLabel">Gift Aid Transaction</td>
            <td class="fieldData"><?php echo $res['GiftAid'] == '1' ? 'Yes' : 'No'; ?></td>
        </tr>
        <?php
        }
        if ($res['addressStatus'])
        {
            ?>
            <tr>
                <td class="fieldLabel">Address Status:</td>
                <td class="fieldData">
                    <span style="float:right; font-size: smaller;">*PayPal transactions only</span>
                    <?php echo $res['addressStatus']; ?>
                </td>
                <?php
            }
            if ($res['payerStatus'])
            {
                ?>
            </tr>
            <tr>
                <td class="fieldLabel">Payer Status:</td>
                <td class="fieldData">
                    <span style="float:right; font-size: smaller;">*PayPal transactions only</span>
                    <?php echo $res['payerStatus']; ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td class="fieldLabel">Basket Contents:</td>
            <td class="fieldData">
                <table width="100%" style="border-collapse: collapse;">
                    <tr class="greybar">
                        <td width="10%" align="right">Quantity</td>
                        <td width="30%" align="center">Image</td>
                        <td width="60%" align="left">Title</td>
                    </tr>
                    <?php
                    foreach ($basket['items'] as $item)
                    {
                        ?>
                        <tr>
                            <td align="right"><?php echo $item['quantity']; ?></td>
                            <td align="center"><img src="<?php echo $item['productUrlImage']; ?>" alt="DVD box"></td>
                            <td align="left"><?php echo $item['description']; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </td>
        </tr>
    </table>
    <div class="greyHzShadeBar">&nbsp;</div>
    <div class="formFooter">
        <p style="float: left">Click Proceed to go back to the Home Page to start another transaction</p>
        <a href="<?php echo url(array('form')); ?>" title="Click to go back to the welcome page" style="float: right">
            <img src="<?php echo BASE_PATH; ?>assets/images/proceed.gif" alt="Click to go back to the welcome page" border="0" />
        </a>
    </div>
</div>