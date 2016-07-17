<div id="content">

    <div id="contentHeader">Void Transaction</div>

    <p class="warning">
        This page sends a VOID request to send to the Server, to void against the transaction you 
        selected in the Order Admin area. The details are displayed below. 
        If you wish to go ahead, click Proceed, 
        otherwise click Back to go Back to the admin area.
    </p>

    <div class="greyHzShadeBar">&nbsp;</div>

    <form name="adminform" action="<?php echo url(array($integrationType, 'void')); ?>" method="post">
        <input type="hidden" name="origVtx" value="<?php echo htmlentities($result['vendorTxCode']); ?>" />
        <table class="formTable">
            <tr>
                <td colspan="2"><div class="subheader">VOID the following transaction</div></td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>You've chosen to VOID the transaction shownbelow.</p>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Original Captured Amount:</td>
                <td class="fieldData"><?php echo $result['capturedAmount']; ?></td>
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
