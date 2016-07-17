<div id="content">
    <div id="contentHeader">Proceed to 3D Secure</div>

    <form name="cardToken" method="post" action="<?php echo $purchaseUrl; ?>">
        <p>Please click button below to proceed to 3D secure.</p>

        <?php
        foreach ($threeDSecure as $key => $value)
{ ?>
            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlentities($value); ?>" />
        <?php } ?>

        <div class="formFooter">
            <input type="image" src="<?php echo BASE_PATH; ?>assets/images/proceed.gif" alt="Proceed to the next page" />
        </div>

    </form>
</div>