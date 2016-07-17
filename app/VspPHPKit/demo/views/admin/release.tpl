<div id="content">

    <div id="contentHeader">Release Transaction</div>

    <p class="warning">
        This page formats a RELEASE request to send to the Server, to release against the DEFERRED transaction you 
        selected in the Order Admin area. The details are displayed below. 
        If you wish to go ahead, check the Amount and click Proceed, 
        otherwise click Back to go Back to the admin area.
    </p>

    <div class="greyHzShadeBar">&nbsp;</div>

    <form name="adminform" action="<?php echo url(array($integrationType, 'release')); ?>" method="post">
        <input type="hidden" name="origVtx" value="<?php echo htmlentities($result['vendorTxCode']); ?>" />
        <table class="formTable">
            <tr>
                <td colspan="2"><div class="subheader">RELEASE the following transaction</div></td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>You've chosen to Release the transaction shown
                        below. You must specify the Release details in the boxes below. 
                        You can Release only once, and for any amount up
                        to and including the amount of the original deferred transaction.
                    </p>
                    <?php if (!$val['ok'])
                    {
                    ?>
                    <p class="warning"><?php echo $val['errorStatusString']; ?></p>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Original Amount:</td>
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
                <td class="fieldLabel">Release Amount:</td>
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
