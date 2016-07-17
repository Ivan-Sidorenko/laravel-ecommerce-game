<div id="content">
    <div id="contentHeader">Transaction Registration Page</div>

    <?php $this->renderContent('common/form_error'); ?>

    <form name="cardToken" method="post" action="<?php echo url(array('direct', 'card_token')); ?>">
    <table class="formTable">
    <tr>
        <td colspan="2"><div class="subheader">Please confirm payment details</div></td>
    </tr>
    <tr>
        <td class="fieldLabel">Card Verification Value:</td>
        <td class="fieldData">
            <input name="cv2" type="text" value="" size="5" maxlength="4" autocomplete="off">
            &nbsp;<font size="1">(Additional 3 digits on card signature strip, 4 on Amex cards)</font>
        </td>
    </tr>
    <tr>
        <td class="fieldLabel">Gift aid:</td>
        <td class="fieldData"><input name="giftAid" type="checkbox">
    </tr>
    <tr>
        <td colspan="2">&nbsp;<font size="1">I confirm I have paid or will pay an amount of Income Tax and/or Capital Gains Tax for each tax year (6 April to 5 April)
            that is at least equal to the amount of tax that all the charities or  that I donate to will reclaim on my gifts for that tax year. I understand that other taxes
            such as VAT and Council Tax do not qualify. I understand the charity will reclaim 28p of tax on every &pound;1 that I gave up to 5 April 2008 and will reclaim 25p of
            tax on every &pound;1 that I give on or after 6 April 2008.</font>
        </td>
    </tr>
    </table>

    <div class="greyHzShadeBar">&nbsp;</div>

    <div class="formFooter">
        <a href="<?php echo url(array('direct', 'details')); ?>" style="float: left;"><img src="<?php echo BASE_PATH; ?>assets/images/back.gif" alt="Back"/></a>
        <input type="image" style="float: right;" src="<?php echo BASE_PATH; ?>assets/images/proceed.gif" alt="Proceed"/>
    </div>

    </form>
</div>