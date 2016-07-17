<div id="content">

    <p id="contentHeader">Checkout</p>

    <p>The contents of your basket are shown here.</p>

    <div class="selectCheckout">
        <table class="formTable">
        <tr>
            <td colspan="5">
                <div class="subheader">Your Basket Contents</div>
            </td>
        </tr>
        <tr class="greybar">
            <td width="17%" align="center">Image</td>
            <td width="45%" align="left">Title</td>
            <td width="15%" align="right">Price</td>
            <td width="8%" align="right">Quantity</td>
            <td width="15%" align="right">Total</td>
        </tr>
        <?php
        foreach ($basket['items'] as $item)
        {
        ?>
        <tr>
            <td align="center"><img src="<?php echo $item['urlImage']; ?>" alt="DVD box"></td>
            <td align="left"><?php echo $item['description']; ?></td>
            <td align="right"><?php echo $item['unitGrossAmount'] . ' ' . $currency ?></td>
            <td align="right"><?php echo $item['quantity'] ?></td>
            <td align="right"><?php echo $item['totalGrossAmount'] . ' ' . $currency ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="4" align="right">Delivery:</td>
            <td align="right"><?php echo $deliveryGrossPrice; ?></td>
        </tr>
        <tr>
            <td colspan="4" align="right"><strong>Total:</strong></td>
            <td align="right"><strong><?php echo $totalGrossPrice; ?></strong></td>
        </tr>
        </table>
        <div class="greyHzShadeBar">&nbsp;</div>
        <div class="formFooter">
            <a href="<?php echo url(array('direct', 'basket')) ?>" style="float: left;">
                <img src="<?php echo BASE_PATH; ?>assets/images/back.gif" alt="Go back to the previous page" border="0" />
            </a>
            <form action="<?php echo url(array('direct', 'basket_checkout')) ?>" method="post">
                <input type="hidden" name="checkoutType" value="OnSite" />
                <input type="image" style="float: right" src="<?php echo BASE_PATH; ?>assets/images/proceed.gif" alt="Proceed" />
            </form>
        </div>
    </div>

</div><!--  end content -->
