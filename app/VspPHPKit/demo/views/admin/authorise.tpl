<div id="content">

<div id="contentHeader">Authorise Transaction</div>

    <p>
        This page formats a AUTHORISE request to send to the Server against the transaction you
        selected in the Order Admin area. The details are displayed below.
        If you wish to go ahead, enter the Amount and click Proceed,
        otherwise click Back to go Back to the admin area.
    </p>

    <div class="greyHzShadeBar">&nbsp;</div>

    <?php $this->renderContent('common/form_error'); ?>

    <form name="adminform" action="<?php echo $actionUrl; ?>" method="post">
    <input type="hidden" name="origVtx" value="<?php echo htmlentities($result['vendorTxCode']); ?>" />
    <table class="formTable">
        <tr>
            <td colspan="2"><div class="subheader">AUTHORISE the following transaction</div></td>
        </tr>
        <tr>
            <td colspan="2">
                <p>
                    You've chosen to authorise the transaction shown
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
            <td class="fieldLabel">Authorise VendorTxCode:</td>
            <td class="fieldData"><input type="text" name="VendorTxCode" size="40" value="<?php echo htmlentities($newVtx); ?>" /></td>
        </tr>
        <tr>
            <td class="fieldLabel">Authorise Description:</td>
            <td class="fieldData"><input type="text" name="Description" size="50" value="Authorise against <?php echo htmlentities($result['vendorTxCode']); ?>" /></td>
        </tr>
        <tr>
            <td class="fieldLabel">Authorise Amount:</td>
            <td class="fieldData"><input type="text" name="Amount" size="10" value="" /> <?php echo htmlentities($result['currency']); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">ApplyAvsCv2:</td>
            <td class="fieldData">
                <select name="ApplyAvsCv2">
                        <option value="0">Default</option>
                        <option value="1">Force AVS/CV2 rules apply</option>
                        <option value="2">Force NO AVS/CV2</option>
                        <option value="3">Force AVS/CV2 DON'T apply rules</option>
                </select>
            </td>
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
