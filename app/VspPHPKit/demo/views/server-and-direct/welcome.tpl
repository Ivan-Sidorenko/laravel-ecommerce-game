<div id="content">
    <div id="contentHeader">Welcome to the Sage Pay <?php echo strtoupper($integrationType);?> PHP Kit </div>
    <div class="greyHzShadeBar">&nbsp;</div>
    <table class="formTable">
        <tr>
            <td colspan="2">
                <div class="subheader">Your current kit set-up</div>
                <p>These settings are defined in the <b>config.php</b> file.</p>
            </td>
        </tr>
        <tr>
            <td class="fieldLabel">Environment:</td>
            <td class="fieldData"><?php echo $env; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">Protocol Version:</td>
            <td class="fieldData"><?php echo $protocolVersion; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">Default Transaction Type:</td>
            <td class="fieldData"><?php echo $txType; ?></td>
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
            <td class="fieldLabel">Database Details:</td>
            <td class="fieldData<?php echo ($pdo == null ? 'warning' : '') ?>">
                Database name: <b><?php echo $db; ?></b><br>
                User: <b><?php echo $username ?></b><br>
                <?php if ($pdo == null)
                { ?>
                <br>
                <span class="warning">Could not connect. Check configuration.</span>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td class="fieldLabel">URL of this kit:</td>
            <td class="fieldData">
                <a href="<?php echo $fullUrl ?>"><?php echo $fullUrl ?></a>
                <?php if (empty($siteFqdn))
                { ?>
                <br />(<span class="warning">warning</span>: this is a guessed value as no "siteFqdn" property is explicitly set in your configuration)
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td class="fieldLabel">Gateway:</td>
            <td class="fieldData">
                <?php echo $purchaseUrl; ?>
                <br/>(The Sage Pay URLs come pre-configured for each environment but you can also override them if you wish)
            </td>
        </tr>
    </table>

    <p>
        <?php
        switch ($env)
        {
        case SAGEPAY_ENV_LIVE:
        ?>
        <span class="warning">Your kit is pointing at the Live Sage Pay environment
            (you can change this by changing the value of the <b>"env"</b> configuration property).
            You should only do this once your have completed testing on both the Simulator AND Test servers, have sent your GoLive request to the technical support team and had confirmation that your account has been set up. <br><br><strong>Transactions sent to the Live service WILL charge your customers' cards.</strong></span>
        <?php
        break;
        case SAGEPAY_ENV_TEST:
        ?>
        Your kit is pointing at the Sage Pay TEST environment
        (you can change this by changing the value of the <b>"env"</b> property).  This is an exact replica of the Live systems except that no banks are attached, so no authorisation requests are sent, nothing is settled and you can use our test card numbers when making payments. You should only use the test environment after you have completed testing using the Simulator AND the Sage Pay support team have mailed you to let you know your account has been created.<br>
        <br><strong>If you are already set up on Live and are testing additional functionality, DO NOT leave your kit set to Test or you will not receive any money for your transactions!</strong>
        <?php
        break;
        default:
        ?>
        <span class="warning">ERROR: unknown environment value</span>
        <?php } ?>
    </p>

    <div class="greyHzShadeBar">&nbsp;</div>
    <?php if ($pdo == null)
    { ?>
    <p class="warning">
        Cannot proceed: please check your configuration settings.
        <?php if ($pdo == null)
        { ?>
        Cannot connect to database server.
        <?php } ?>
    </p>
    <?php } ?>

    <?php if ($pdo != null)
    { ?>
    <div class="formFooter">
        <div style="clear: both;">
            <a href="<?php echo url(array($integrationType, 'entry')); ?>" title="Proceed to the next page" style="float: right;">
                <img src="<?php echo BASE_PATH; ?>assets/images/proceed.gif" alt="button" />
            </a>
            To begin the purchase process, click the Proceed button.
        </div>
        <div style="clear: both;">
            <a href="<?php echo url(array($integrationType, 'admin')); ?>" title="Go to the Admin page" style="float:right;">
                <img src="<?php echo BASE_PATH; ?>assets/images/admin.gif" alt="button" />
            </a>
            Alternatively, to administer your existing orders, click the Admin button.
        </div>
    </div>
    <?php } ?>

</div>
