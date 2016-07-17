<div id="content">
    <div id="contentHeader">Transaction Registration Page</div>

    <?php $this->renderContent('common/form_error'); ?>

    <form name="storeform" method="post" action="<?php echo url(array('direct', 'card')); ?>">
        <table class="formTable">
            <tr>
                <td colspan="2"><div class="subheader">Enter Card Details</div></td>
            </tr>
            <tr>
            <td class="fieldLabel">Card Type:</td>
                <td class="fieldData">
                    <select name="cardType" onchange="cardTypeChanged(this);">
                        <option value="VISA">VISA Credit</option>
                        <option value="DELTA">VISA Debit</option>
                        <option value="UKE">VISA Electron</option>
                        <option value="MC">MasterCard</option>
                        <option value="MAESTRO">Maestro</option>
                        <option value="AMEX">American Express</option>
                        <option value="DC">Diner's Club</option>
                        <option value="JCB">JCB Card</option>
                        <option value="LASER">Laser</option>
                        <option value="PAYPAL">PayPal</option>

                    </select>&nbsp;<font size="1">(Edit to those card you can accept)</font>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Card Number:</td>
                <td class="fieldData">
                <input name="cardNumber" type="text" value="" size="25" maxlength="20" autocomplete="off">
                &nbsp;<font size="1">(With no spaces or separators)</font></td>
            </tr>
            <tr>
                <td class="fieldLabel">Card Holder Name:</td>
                <td class="fieldData">
                <input name="cardHolder" type="text" value="" size="25" maxlength="50"></td>
            </tr>
            <tr>
                <td class="fieldLabel">Start Date:</td>
                <td class="fieldData">
                <input name="startDate" type="text" value="" size="5" maxlength="4">
                &nbsp;<font size="1">(Where available. Use MMYY format  e.g. 0207)</font></td>
            </tr>
            <tr>
                <td class="fieldLabel">Expiry Date:</td>
                <td class="fieldData"><input name="expiryDate" type="text" value="" size="5" maxlength="4">
                &nbsp;<font size="1">(Use MMYY format with no / or - separators e.g. 1109)</font></td>
            </tr>
            <tr>
                <td class="fieldLabel">Card Verification Value:</td>
                <td class="fieldData"><input name="cv2" type="text" value="" size="5" maxlength="4" autocomplete="off">
                &nbsp;<font size="1">(Additional 3 digits on card signature strip, 4 on Amex cards)</font></td>
            </tr>
            <tr>
                <td class="fieldLabel">Store For Future:</td>
                <td class="fieldData"><input name="useToken" type="checkbox">
                &nbsp;<font size="1">(Use Sage Pay Token system)</font></td>
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

<script type="text/javascript">
function cardTypeChanged(selectObject)
{
    if(selectObject.value=='PAYPAL') {
        var sDisabledBGColour = "#DDDDDD";
        document.storeform.cardHolder.value='';
        document.storeform.cardHolder.style.background=sDisabledBGColour;
                 document.storeform.cardHolder.disabled = true;
        document.storeform.cardNumber.value='';
        document.storeform.cardNumber.style.background=sDisabledBGColour;
                 document.storeform.cardNumber.disabled = true;
        document.storeform.startDate.value='';
        document.storeform.startDate.style.background=sDisabledBGColour;
                 document.storeform.startDate.disabled = true;
        document.storeform.expiryDate.value='';
        document.storeform.expiryDate.style.background=sDisabledBGColour;
                 document.storeform.expiryDate.disabled = true;
        document.storeform.cv2.value='';
        document.storeform.cv2.style.background=sDisabledBGColour;
                 document.storeform.cv2.disabled = true;
        document.storeform.useToken.disabled = true;
        document.storeform.useToken.checked = false;
        document.storeform.giftAid.disabled = true;
        document.storeform.giftAid.checked = false;
        alert('You just selected a payment method of PayPal so card details will not be required here.\n\nAfter clicking \'Proceed\' you will be securely redirected to the PayPal website to authorise your details.');
             } else {
                 document.storeform.cardHolder.disabled = false;
        document.storeform.cardHolder.style.background = "";
                 document.storeform.cardNumber.disabled = false;
        document.storeform.cardNumber.style.background = "";
                 document.storeform.startDate.disabled = false;
        document.storeform.startDate.style.background = "";
                 document.storeform.expiryDate.disabled = false;
        document.storeform.expiryDate.style.background = "";
                 document.storeform.cv2.disabled = false;
        document.storeform.cv2.style.background = "";
        document.storeform.useToken.disabled = false;
        document.storeform.giftAid.disabled = false;
    }
}
</script>