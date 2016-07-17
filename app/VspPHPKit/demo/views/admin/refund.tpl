<div id="content">

<div id="contentHeader">Refund Transaction</div>

    <div class="greyHzShadeBar">&nbsp;</div>

    <form name="adminform" action="<?php echo url(array($integrationType, 'refund')); ?>" method="post">
        <input type="hidden" name="origVtx" value="<?php echo htmlentities($result['vendorTxCode']); ?>" />
        <table class="formTable">
            <tr>
                <td colspan="2"><div class="subheader">REFUND the following transaction</div></td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>
                        You can Refund for any amount up
                        to and including the amount of the original transaction, subtracting the amounts of any
                        other refunds you have performed against this transaction.
                    </p>
                    <?php if (!$val['ok'])
                    {
                    ?>
                    <p class="warning"><?php echo $val['errorStatusString']; ?></p>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Original Captured Amount:</td>
                <td class="fieldData"><?php echo $result['capturedAmount']; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Already Refunded Amount:</td>
                <td class="fieldData"><?php echo $alreadyRefundedAmount; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">VendorTxCode:</td>
                <td class="fieldData"><?php echo $result['vendorTxCode']; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">VPSTxId:</td>
                <td class="fieldData"><?php echo $result['vpsTxId']; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">SecurityKey:</td>
                <td class="fieldData"><?php echo $result['securityKey']; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">TxAuthNo:</td>
                <td class="fieldData"><?php echo $result['txAuthNo']; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Refund VendorTxCode:</td>
                <td class="fieldData"><input type="text" name="VendorTxCode" size="40" value="<?php echo htmlentities($refundVtx); ?>" /></td>
            </tr>
            <tr>
                <td class="fieldLabel">Refund Description:</td>
                <td class="fieldData"><input type="text" name="Description" size="50" value="Refund against <?php echo htmlentities($result['vendorTxCode']); ?>" /></td>
            </tr>
            <tr>
                <td class="fieldLabel">Refund Amount:</td>
                <td class="fieldData"><input type="text" name="Amount" size="10" value="" /> <?php echo $result['currency']; ?></td>
            </tr>
        </table>

        <div class="greyHzShadeBar">&nbsp;</div>

        <div class="formFooter">
            <a href="<?php echo url(array($integrationType, 'admin')); ?>" style="float: left;">
                <img src="<?php echo BASE_PATH; ?>assets/images/back.gif" alt="Back" />
            </a>
            <input type="image" src="<?php echo BASE_PATH; ?>assets/images/proceed.gif" />
        </div>

    </form>

</div>
