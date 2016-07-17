<script>
    $(function() {
        $("[name=dateOfBirth]").datepicker(
                {
                            minDate: "-150y",
                            maxDate: 0,
                            dateFormat: "dd-mm-yy",
                            yearRange: "-150:+0",
                            changeMonth: true,
                            changeYear: true
                        }
        );

        $("[name=dateOfBirth]").attr("autocomplete", "off");
    });
</script>
<div id="content">
    <div id="contentHeader">Gathering Customer Details</div>
    <p>This page captures your customer's name, address and contact details.</p>
    <div class="greyHzShadeBar">&nbsp;</div>

    <?php $this->renderContent('common/form_error'); ?>

    <form name="customerform" action="<?php echo $actionUrl; ?>" method="post">
        <table class="formTable">
            <?php
            if (isset($allTokens) && !empty($allTokens))
            {
                ?>
                <tr>
                    <td colspan="2">
                        <div class="subheader">Select a previous card (optional, leave blank to enter new payment details)</div>
                    </td>
                </tr>
                <tr>
                    <td class="fieldLabel">
                        Previous Payment Method:
                    </td>
                    <td class="fieldData">
                        <select name="token" style="width: 200px;" >
                            <option></option>
                            <?php
                            foreach ($allTokens as $row)
                            {
                                ?>
                                <option value="<?php echo $row['token']; ?>"<?php echo $row['token'] == $token ? ' selected' : ''; ?>>XXXX XXXX XXXX <?php echo $row['last4digits']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="2">
                    <div class="subheader">Please enter your Billing details below</div>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">
                    <span class="warning">*</span>First Name(s):
                </td>
                <td class="fieldData">
                    <input name="billingFirstnames" type="text" maxlength="20" value="<?php echo htmlentities($current['BillingFirstnames']); ?>" style="width: 200px;">
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">
                    <span class="warning">*</span>Surname:
                </td>
                <td class="fieldData">
                    <input name="billingSurname" type="text" maxlength="20" value="<?php echo htmlentities($current['BillingSurname']); ?>" style="width: 200px;">
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">
                    <span class="warning">*</span>Address Line 1:
                </td>
                <td class="fieldData">
                    <input name="billingAddress1" type="text" maxlength="100" value="<?php echo htmlentities($current['BillingAddress1']); ?>" style="width: 400px;">
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Address Line 2:</td>
                <td class="fieldData">
                    <input name="billingAddress2" type="text" maxlength="100" value="<?php echo htmlentities($current['BillingAddress2']); ?>" style="width: 400px;">
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">
                    <span class="warning">*</span>City:
                </td>
                <td class="fieldData">
                    <input name="billingCity" type="text" maxlength="40" value="<?php echo htmlentities($current['BillingCity']); ?>" style="width: 200px;">
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Post/Zip Code:</td>
                <td class="fieldData">
                    <input name="billingPostCode" type="text" maxlength="10" value="<?php echo htmlentities($current['BillingPostCode']); ?>" style="width: 100px;">
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">
                    <span class="warning">*</span>Country:
                </td>
                <td class="fieldData">
                    <select name="billingCountry" style="width: 200px;">
                        <script type="text/javascript" language="javascript">
                            document.write(getCountryOptionsListHtml("<?php echo htmlentities($current['BillingCountry']); ?>"));
                        </script>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">State Code (U.S. only):</td>
                <td class="fieldData">
                    <select name="billingState" style="width: 200px;">
                        <script type="text/javascript" language="javascript">
                            document.write(getUsStateOptionsListHtml("<?php echo htmlentities($current['BillingState']); ?>"));
                        </script>
                    </select>&nbsp;(<span class="warning">*</span> for U.S. customers only)
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Phone:</td>
                <td class="fieldData">
                    <input name="billingPhone" type="text" maxlength="20" value="<?php echo htmlentities($current['BillingPhone']); ?>" style="width: 200px;">
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">e-Mail Address:</td>
                <td class="fieldData">
                    <input name="customerEmail" type="text" maxlength="255" value="<?php echo htmlentities($current['customerEmail']); ?>"  style="width: 200px;">
                </td>
            </tr>
            <tr>
            <tr>
                <td class="fieldLabel">Date of Birth</td>
                <td class="fieldData"><input value="<?php echo htmlentities($current['dateOfBirth']); ?>" type="text" name="dateOfBirth"></td>
            </tr>
        </tr>
            <td colspan="2">
                <div class="subheader">Please enter your Delivery details below</div>
            </td>
        </tr>
            <tr>
                <td class="fieldLabel">Same as Billing Details?:</td>
                <td class="fieldData">
                    <input name="isDeliverySame" type="checkbox" value="YES" <?php echo ($isDeliverySame ? 'checked="checked"' : ''); ?> onClick="IsDeliverySame_clicked();">
                </td>
            </tr>
            <tr>
                <td class="fieldLabel"><span class="warning">*</span>First Name(s):</td>
                <td class="fieldData">
                    <input name="deliveryFirstnames" type="text" maxlength="20" value="<?php echo htmlentities($current['DeliveryFirstnames']); ?>" style="width: 200px;">
                </td>
            </tr>
            <tr>
                <td class="fieldLabel"><span class="warning">*</span>Surname:</td>
                <td class="fieldData">
                    <input name="deliverySurname" type="text" maxlength="20" value="<?php echo htmlentities($current['DeliverySurname']); ?>" style="width: 200px;">
                </td>
            </tr>
            <tr>
                <td class="fieldLabel"><span class="warning">*</span>Address Line 1:</td>
                <td class="fieldData">
                    <input name="deliveryAddress1" type="text" maxlength="100" value="<?php echo htmlentities($current['DeliveryAddress1']); ?>" style="width: 400px;">
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Address Line 2:</td>
                <td class="fieldData">
                    <input name="deliveryAddress2" type="text" maxlength="100" value="<?php echo htmlentities($current['DeliveryAddress2']); ?>" style="width: 400px;">
                </td>
            </tr>
            <tr>
                <td class="fieldLabel"><span class="warning">*</span>City:</td>
                <td class="fieldData">
                    <input name="deliveryCity" type="text" maxlength="40" value="<?php echo htmlentities($current['DeliveryCity']); ?>" style="width: 200px;">
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Post/Zip Code:</td>
                <td class="fieldData">
                    <input name="deliveryPostCode" type="text" maxlength="10" value="<?php echo htmlentities($current['DeliveryPostCode']); ?>" style="width: 100px;">
                </td>
            </tr>
            <tr>
                <td class="fieldLabel"><span class="warning">*</span>Country:</td>
                <td class="fieldData">
                    <select name="deliveryCountry" style="width: 200px;">
                        <script type="text/javascript" language="javascript">
                            document.write(getCountryOptionsListHtml("<?php echo htmlentities($current['DeliveryCountry']); ?>"));
                        </script>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">State Code (U.S. only):</td>
                <td class="fieldData">
                    <select name="deliveryState" style="width: 200px;">
                        <script type="text/javascript">
                            document.write(getUsStateOptionsListHtml("<?php echo htmlentities($current['DeliveryState']); ?>"));
                        </script>
                    </select>&nbsp;(<span class="warning">*</span> for U.S. customers only)
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Phone:</td>
                <td class="fieldData">
                    <input name="deliveryPhone" type="text" maxlength="20" value="<?php echo htmlentities($current['DeliveryPhone']); ?>" style="width: 200px;">
                </td>
            </tr>
        </table>

        <script type="text/javascript" language="javascript">
            IsDeliverySame_clicked(true);
        </script>

        <div class="greyHzShadeBar">&nbsp;</div>
        <div class="formFooter">
            <a href="<?php echo $backUrl; ?>" title="Go back to the place order page" style="float: left;">
                <img src="<?php echo BASE_PATH; ?>assets/images/back.gif" alt="Go back to the previous page" border="0" />
            </a>
            <input type="image" style="float: right" src="<?php echo BASE_PATH; ?>assets/images/proceed.gif" alt="Proceed to the next page" />
        </div>
    </form>
</div>
