<div id="content">

    <div id="contentHeader">Repeat Transaction</div>


    <p>
        This page formats a REPEAT POST to send to the Server against the transaction you 
        selected in the Order Admin area. The details are displayed below. 
        If you wish to go ahead, enter the Amount and click Proceed, 
        otherwise click Back to go Back to the admin area.
    </p>

    <div class="greyHzShadeBar">&nbsp;</div>

    <form name="adminform" action="<?php echo url(array($integrationType, 'repeat')); ?>" method="post">
        <input type="hidden" name="origVtx" value="<?php echo htmlentities($result['vendorTxCode']); ?>" />
        <input type="hidden" name="deferred" value="<?php echo htmlentities($deferred); ?>" />
        <table class="formTable">
            <tr>
                <td colspan="2"><div class="subheader">REPEAT the following transaction</div></td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>
                        You've chosen to repeat the transaction shown
                        below. You must specify the details in the boxes below.
                    </p>

                    <?php if (!$val['ok'])
                    {
                    ?>
                    <p class="warning"><?php echo $val['errorStatusString']; ?></p>
                    <?php } ?>

                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Original Order Amount:</td>
                <td class="fieldData"><?php echo $result['amount']; ?></td>
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
                <td class="fieldLabel">Repeat VendorTxCode:</td>
                <td class="fieldData"><input type="text" name="VendorTxCode" size="40" value="<?php echo htmlentities($newVtx); ?>" /></td>
            </tr>
            <tr>
                <td class="fieldLabel">Repeat Description:</td>
                <td class="fieldData"><input type="text" name="Description" size="50" value="Repeat against <?php echo htmlentities($result['vendorTxCode']); ?>" /></td>
            </tr>
            <tr>
                <td class="fieldLabel">Repeat Amount:</td>
                <td class="fieldData"><input type="text" name="Amount" size="10" value="" /> <?php echo $result['currency']; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">CV2 (optional):</td>
                <td class="fieldData"><input type="text" name="cv2" size="10" value="" /></td>
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
