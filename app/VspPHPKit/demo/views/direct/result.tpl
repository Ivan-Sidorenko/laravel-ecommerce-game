<div id="content">

    <div id="contentHeader">
        Your order has <?php echo $stOk ? '' : 'NOT '; ?>been successful
    </div>

    <?php
    if (!empty($errorMessage))
    {
        ?>
        <p class="warning"><?php echo $errorMessage; ?></p>
    <?php } ?>

    <p>The order number, for your reference is: <b><?php echo $ord['vendorTxCode']; ?></b></p>

    <p>
        They should quote this in all correspondence with you, and likewise you should use
        this reference when sending queries to Sage Pay about this transaction (along with your Sage Pay Vendor Name).
    </p>

    <p>
        The table below shows everything sent back from Direct about this order You
        would not normally show this level of detail to your customers, but it is useful during development;
    </p>

    <p>
        You can customise this page to offer alternative payment methods, links to customer support
        numbers, help and advice for online shopper, whatever is appropriate for your application.
    </p>

    <div class="greyHzShadeBar">&nbsp;</div>

    <table class="formTable">
        <tr>
            <td colspan="2"><div class="subheader">Transaction Details</div></td>
        </tr>
        <tr>
            <td class="fieldLabel">VendorTxCode:</td>
            <td class="fieldData"><?php echo $ord['vendorTxCode']; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">Transaction Type:</td>
            <td class="fieldData"><?php echo $ord['transactionType']; ?></td>
        </tr>
        <?php
        if ($ord['amount'])
        {
            ?>
            <tr>
                <td class = "fieldLabel">Amount (Incl. Surcharge):</td>
                <td class = "fieldData"><?php echo $ord['amount']; ?></td>
            </tr>
            <?php
        }
        if ($ord['surcharge'])
        {
            ?>
            <tr>
                <td class="fieldLabel">Surcharge:</td>
                <td class="fieldData"><?php echo $ord['surcharge']; ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="2"><div class="subheader">Contents of the Direct Response</div></td>
        </tr>

        <tr>
            <td class="fieldLabel">Status:</td>
            <td class="fieldData"><?php echo $ord['status']; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">Status Message:</td>
            <td class="fieldData"><?php echo $ord['statusMessage']; ?></td>
        </tr>
        <?php
        if (isset($ord['vpsTxId']))
        {
            ?>
            <tr>
                <td class="fieldLabel">VPSTxId:</td>
                <td class="fieldData"><?php echo $ord['vpsTxId']; ?></td>
            </tr>
            <?php
        }
        if (isset($ord['securityKey']))
        {
            ?>
            <tr>
                <td class="fieldLabel">SecurityKey:</td>
                <td class="fieldData"><?php echo $ord['securityKey']; ?></td>
            </tr>
            <?php
        }
        if (isset($ord['txAuthNo']))
        {
            ?>
            <tr>
                <td class="fieldLabel">VPSAuthCode (TxAuthNo):</td>
                <td class="fieldData"><?php echo $ord['txAuthNo']; ?></td>
            </tr>
            <?php
        }
        if (isset($ord['bankAuthCode']))
        {
            ?>
            <tr>
                <td class="fieldLabel">Bank Authentication Code:</td>
                <td class="fieldData"><?php echo $ord['bankAuthCode']; ?></td>
            </tr>
            <?php
        }
        if (isset($ord['declineCode']))
        {
            ?>
            <tr>
                <td class="fieldLabel">Bank Decline Code:</td>
                <td class="fieldData"><?php echo $ord['declineCode']; ?></td>
            </tr>
            <?php
        }
        if (isset($ord['avsCv2']))
        {
            ?>
            <tr>
                <td class="fieldLabel">AVSCV2 Results:</td>
                <td class="fieldData">
                    <?php echo $ord['avsCv2']; ?>
                    <span class="addressCheckDetails">
                        Address: <?php echo empty($ord['addressResult']) ? '-' : $ord['addressResult']; ?>
                        Postcode: <?php echo empty($ord['postCodeResult']) ? '-' : $ord['postCodeResult']; ?>
                        CV2: <?php echo empty($ord['cv2Result']) ? '-' : $ord['cv2Result']; ?>
                    </span>
                </td>
            </tr>
            <?php
        }
        if (isset($ord['threeDSecureStatus']))
        {
            ?>
            <tr>
                <td class="fieldLabel">3D-Secure Status:</td>
                <td class="fieldData"><?php echo $ord['threeDSecureStatus']; ?></td>
            </tr>
            <?php
        }
        if (isset($ord['cavv']))
        {
            ?>
            <tr>
                <td class="fieldLabel">CAVV:</td>
                <td class="fieldData"><?php echo $ord['cavv']; ?></td>
            </tr>
            <?php
        }
        if (isset($ord['cardType']))
        {
            ?>
            <tr>
                <td class="fieldLabel">Card Type:</td>
                <td class="fieldData"><?php echo $ord['cardType']; ?></td>
            </tr>
            <?php
        }
        if (isset($ord['last4Digits']))
        {
            ?>
            <tr>
                <td class="fieldLabel">Last 4 Digits:</td>
                <td class="fieldData"><?php echo $ord['last4Digits']; ?> </td>
            </tr>
            <?php
        }
        if (isset($ord['token']))
        {
            ?>
            <tr>
                <td class="fieldLabel">Token ID:</td>
                <td class="fieldData"><?php echo $ord['token']; ?></td>
            </tr>
            <?php
        }
        if (isset($ord['expiryDate']))
        {
            ?>
            <tr>
                <td class="fieldLabel">Expiry Date:</td>
                <td class="fieldData"><?php echo $ord['expiryDate']; ?></td>
            </tr>
            <?php
        }

        if (false) // Hidden field for now
        {
            ?>
            <tr>
                <td class="fieldLabel">Gift Aid Transaction</td>
                <td class="fieldData"><?php echo $ord['giftAid'] == '1' ? 'Yes' : 'No'; ?></td>
            </tr>
            <?php
        }
        if (isset($ord['addressStatus']))
        {
            ?>
            <tr>
                <td class="fieldLabel">PayPal Address Status:</td>
                <td class="fieldData"><?php echo $ord['addressStatus']; ?></td>
            </tr>
            <?php
        }
        if (isset($ord['payerStatus']))
        {
            ?>
            <tr>
                <td class="fieldLabel">PayPal Payer Status:</td>
                <td class="fieldData"><?php echo $ord['payerStatus']; ?></td>
            </tr>
            <?php
        }
        if (isset($ord['payerId']))
        {
            ?>
            <tr>
                <td class="fieldLabel">PayPal Payer ID:</td>
                <td class="fieldData"><?php echo $ord['payerId']; ?></td>
            </tr>
            <?php
        }
        if (isset($ord['fraudResponse']))
        {
            ?>
            <tr>
                <td class="fieldLabel">Fraud Response:</td>
                <td class="fieldData"><?php echo $ord['fraudResponse']; ?></td>
            </tr>
            <?php
        }
        ?>

        <tr>
            <td colspan="2"><div class="subheader">Basket Contents</div></td>
        </tr>


        <tr>
            <td class="fieldData" colspan="2">
                <table width="100%" style="border-collapse: collapse;">
                    <tr class="greybar">
                        <td width="30%" align="center">Image</td>
                        <td width="60%" align="left">Title</td>
                        <td width="10%" align="right">Quantity</td>
                    </tr>
                    <?php
                    foreach ($basket['items'] as $item)
                    {
                        ?>
                        <tr>
                            <td align="center"><img src="<?php echo $item['urlImage']; ?>" alt="DVD box"></td>
                            <td align="left"><?php echo $item['description']; ?></td>
                            <td align="right"><?php echo $item['quantity'] ?></td>
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
        <a href="<?php echo url(array('direct', 'admin')); ?>" style="float: left;">
            <img src="<?php echo BASE_PATH; ?>assets/images/admin.gif" alt="Admin" border="0" />
        </a>
        <a href="<?php echo url(array('direct')); ?>" style="float: left;">
            <img src="<?php echo BASE_PATH; ?>assets/images/proceed.gif" alt="Next" border="0" />
        </a>
    </div>

</div>
