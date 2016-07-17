<!DOCTYPE html>
<html>
    <head>
        <title>Sage Pay PHP Kit</title>
        <link rel="stylesheet" type="text/css" href="<?php echo BASE_PATH; ?>assets/css/main.css">
        <link rel="stylesheet" type="text/css" href="<?php echo BASE_PATH; ?>assets/css/jquery-ui-1.8.23.css">
        <script type="text/javascript" src="<?php echo BASE_PATH; ?>assets/scripts/countrycodes.js"></script>
        <script type="text/javascript" src="<?php echo BASE_PATH; ?>assets/scripts/customerDetails.js"></script>
        <script type="text/javascript" src="<?php echo BASE_PATH; ?>assets/scripts/statecodes.js"></script>
        <script type="text/javascript" src="<?php echo BASE_PATH; ?>assets/scripts/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="<?php echo BASE_PATH; ?>assets/scripts/jquery-ui-1.8.23.min.js"></script>
        <script type="text/javascript">
            var isInIFrame = (window.location != window.parent.location) ? true : false;
            if (isInIFrame) {
                window.parent.location = window.location;
            }
        </script>
    </head>
    <body>

        <?php
        if ($env != SAGEPAY_ENV_LIVE)
        {
        ?>
        <div id="topBar">
            <dl>
                <dt>Vendor:</dt><dd><?php echo $vendorName ?></dd>
                <dt>Env:</dt><dd><?php echo $env ?></dd>
                <dt>Integration:</dt><dd><?php echo $integrationType; ?></dd>
            </dl>
        </div>
        <?php
        }
        ?>

        <div id="pageContainer">

            <div id="pageHeader">
                <a href="<?php echo BASE_PATH; ?>"><img src="<?php echo BASE_PATH; ?>assets/images/sage_pay_logo.gif" id="logo" /></a>
                <img src="<?php echo BASE_PATH; ?>assets/images/title.png" id="siteTitle"/>
            </div>

            <?php
            if ($integrationType == SAGEPAY_DIRECT || $integrationType == SAGEPAY_SERVER)
            {
            ?>
            <div id="loginInfo">
                <?php
                if (HelperCommon::getStore('account'))
                {
                ?>
                <p>
                    You are logged in as: <?php echo HelperCommon::getStore('account', 'email'); ?>.
                    <a href="<?php echo url(array($integrationType, 'logout')); ?>" class="logoutLink">Logout</a>
                </p>
                <?php
                }
                else
                {
                ?>
                <a href="<?php echo url(array($integrationType, 'entry')); ?>" class="loginLink">Login/Register</a>
                <?php
                }
                ?>
            </div>
            <?php } ?>

            <?php $this->renderContent(); ?>

        </div>

        <?php $this->renderContent('common/resource_bar'); ?>

    </body>
</html>