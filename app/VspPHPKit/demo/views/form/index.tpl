<div id="content">
    <div id="contentHeader">Welcome to the Sage Pay FORM PHP Kit </div>
    <div class="greyHzShadeBar">&nbsp;</div>
    <table class="formTable">
        <tr>
            <td colspan="2">
                <div class="subheader">Your current kit set-up</div>
            </td>
        </tr>
        <tr>
            <td class="fieldLabel">Vendor Name:</td>
            <td class="fieldData"><?php echo $vendorName; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">Default Currency:</td>
            <td class="fieldData"><?php echo $currency; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">URL of this kit:</td>
            <td class="fieldData">
                <a href="<?php echo $fullUrl ?>"><?php echo $fullUrl ?></a>
                <?php
                if (empty($siteFqdn))
                {
                ?>
                <br />(<span class="warning">warning</span>: this is a guessed value as no "siteFqdn" property is explicitly set in your configuration)
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td class="fieldLabel">Gateway:</td>
            <td class="fieldData">
                <a href="<?php echo $purchaseUrl; ?>"><?php echo $purchaseUrl; ?></a>
                <br/>(The Sage Pay Server URLs come ready configured for each environment but you can also override them if you wish)
            </td>
        </tr>
    </table>
    <?php
    if (!$isEncryptionPasswordOk)
    {
    ?>
    <p class="warning"><b>Could not perform a test encryption. Verify your encryption password is set correctly.</b></p>
    <?php } ?>

    <p>
        <?php
        switch ($env)
        {
        case SAGEPAY_ENV_LIVE :
        ?>
        <span class="warning">Your kit is pointing at the Live Sage Pay environment
            (you can change this by changing the value of the <b>"env"</b> property).
            You should only do this once your have completed testing on both the Simulator AND Test servers, have sent your GoLive request to the technical support team and had confirmation that your account has been set up. <br><br><strong>Transactions sent to the Live service WILL charge your customers' cards.</strong></span>
        <?php
        break;
        case SAGEPAY_ENV_TEST:
        ?>
        Your kit is pointing at the Sage Pay TEST environment
        (you can change this by changing the value of the <b>"env"</b> property).  This is an exact replica of the Live systems except that no banks are attached, so no authorisation requests are sent, nothing is settled and you can use our test card numbers when making payments. You should only use the test environment after you have completed testing using the Simulator AND the Sage Pay support team have mailed you to let you know your account has been created.<br><br><span><strong>If you are already set up on Live and are testing additional functionality, DO NOT leave your kit set to Test or you will not receive any money for your transactions!</strong></span>
        <?php
        break;
        default:
        ?>
        <span class="warning">ERROR: unknown environment value</span>
        <?php } ?>
    </p>
    <div class="greyHzShadeBar">&nbsp;</div>
    <div class="formFooter">
        <a href="<?php echo url(array('form', 'basket')); ?>" title="Proceed to the next page" style="float: right;">
            <img src="<?php echo BASE_PATH; ?>assets/images/proceed.gif" alt="Proceed to the next page" border="0" />
        </a>
    </div>
</div>
