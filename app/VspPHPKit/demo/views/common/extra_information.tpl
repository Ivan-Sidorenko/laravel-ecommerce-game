<script>
    $(document).ready(function() {



        if($("[name=carTo]").val() != '' || $("[name=carFrom]").val() != '') {
            $("#car").show();
        } else if($("[name=cruiseTo]").val() != '' || $("[name=cruiseFrom]").val() != '') {
            $("#cruise").show();
        } else if($("[name=tourTo]").val() != '' || $("[name=tourFrom]").val() != '') {
            $("#tour").show();
        } else if($("[name=hotelTo]").val() != '' || $("[name=hotelFrom]").val() != '' ||
                $("[name=numberInParty]").val() != '' || $("[name=guestName]").val() != ''
                    || $("[name=roomRate]").val() != '') {
            $("#hotel").show();
        }

        $("select").change(function() {
            var divId = $(this).val();
            if (divId != '') {
                $(".trip").hide();
                $('#' + divId).fadeIn('300');
            } else {
                $(".trip").hide();
            }
        });

        $("select").trigger("change");
    });


    function setDateRange(from, to) {
        $(from).datepicker(
                "option",
                "onSelect",
                function(selectedDate) {
                    $(to).datepicker("option", "minDate", selectedDate);
                }
        );

        $(to).datepicker(
                "option",
                "onSelect",
                function(selectedDate) {
                    $(from).datepicker("option", "maxDate", selectedDate);
                }
        );
    }

    $(function() {
        $(".datepicker").datepicker(
                {
                    minDate: 0,
                    dateFormat: "dd-mm-yy"
                }
        );

        $(".datepicker").attr("autocomplete", "off");

        setDateRange("[name=hotelFrom]", "[name=hotelTo]");
        setDateRange("[name=carFrom]","[name=carTo]");
        setDateRange("[name=tourFrom]","[name=tourTo]");
        setDateRange("[name=cruiseFrom]", "[name=cruiseTo]");

        $("[name=fiRecipientDob]").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+0",
            maxDate: new Date(),
            dateFormat: "yymmdd"
        });
    });
</script>
<div id="content">
    <div id="contentHeader">Extra Information</div>
    <?php $this->renderContent('common/form_error'); ?>
    <form name="extraInformationForm" method="post" action="<?php echo $actionUrl; ?>">
        <?php if ($basketXml !== FALSE)
        { ?>
            <p class="subheader">Trip additions</p>
            <select id="tripSelector" name="extra">
                <option value="">---</option>
                <?php foreach ($tripSelectors as $key => $value)
                {
                ?>
                <option value="<?php echo $key; ?>" <?php echo $key == $current['extra'] ? 'selected' : ''; ?>><?php echo $value; ?></option>
                <?php } ?>
            </select>
            <div class="trip" id="hotel">
                <table class="formTable">
                    <tr>
                        <td>
                            <p class="subheader">Hotel</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="fieldLabel">Check in</td>
                        <td class="fieldData"><input class="datepicker" value="<?php echo htmlentities($current['hotelFrom']); ?>" type="text" name="hotelFrom"></td>
                        <td class="fieldLabel">Check out</td>
                        <td class="fieldData"><input class="datepicker" value="<?php echo htmlentities($current['hotelTo']); ?>" type="text" name="hotelTo"></td>
                    </tr>
                    <tr>
                        <td class="fieldLabel">Number in Party</td>
                        <td class="fieldData"><input value="<?php echo htmlentities($current['numberInParty']); ?>" type="text" name="numberInParty"></td>
                    </tr>
                    <tr>
                        <td class="fieldLabel">Guest Name</td>
                        <td class="fieldData"><input value="<?php echo htmlentities($current['guestName']); ?>" type="text" name="guestName"></td>
                    </tr>
                    <tr>
                        <td class="fieldLabel">Reference Number</td>
                        <td class="fieldData"><input value="<?php echo htmlentities($current['referenceNumber']); ?>" type="text" name="referenceNumber"></td>
                    </tr>
                    <tr>
                        <td class="fieldLabel">Room Rate</td>
                        <td class="fieldData"><input value="<?php echo htmlentities($current['roomRate']); ?>" type="text" name="roomRate"></td>
                    </tr>
                    <tr>
                        <td class="fieldLabel">Confirmed Reservation</td>
                        <td class="fieldData"><input type="checkBox" name="confirmedReservation"></td>
                    </tr>
                </table>
            </div>
            <div id="tour" class="trip">
                <p class="subheader">Tour Operator</p>
                <table class="formTable">
                    <tr>
                        <td class="fieldLabel">Check in</td>
                        <td class="fieldData"><input class="datepicker" value="<?php echo htmlentities($current['tourOperatorFrom']); ?>" type="text" name="tourFrom"></td>
                        <td class="fieldLabel">Check out</td>
                        <td class="fieldData"><input class="datepicker" value="<?php echo htmlentities($current['tourOperatorTo']); ?>" type="text" name="tourTo"></td>
                    </tr>
                </table>
            </div>
            <div id="car" class="trip">
                <p class="subheader">Car Rental</p>
                <table class="formTable">
                    <tr>
                        <td class="fieldLabel">Check in</td>
                        <td class="fieldData"><input class="datepicker" value="<?php echo htmlentities($current['carRentalFrom']); ?>" type="text" name="carFrom"></td>
                        <td class="fieldLabel">Check out</td>
                        <td class="fieldData"><input class="datepicker" value="<?php echo htmlentities($current['carRentalTo']); ?>" type="text" name="carTo"></td>
                    </tr>
                </table>
            </div>
            <div id="cruise" class="trip">
                <p class="subheader">Cruise</p>
                <table class="formTable">
                    <tr>
                        <td class="fieldLabel">Check in</td>
                        <td class="fieldData"><input class="datepicker" value="<?php echo htmlentities($current['cruiseFrom']); ?>" type="text" name="cruiseFrom"></td>
                        <td class="fieldLabel">Check out</td>
                        <td class="fieldData"><input class="datepicker" value="<?php echo htmlentities($current['cruiseTo']); ?>" type="text" name="cruiseTo"></td>
                    </tr>
                </table>
            </div>
        <?php } ?>

        <?php if ($collectRecipientDetails)
        { ?>
            <p class="subheader">Recipient Details</p>
            <div class="formTable" id="recipientDetails">
                <table class="formTable">
                    <tr>
                        <td class="fieldLabel">Account number:</td>
                        <td class="fieldData"><input value="<?php echo htmlentities($current['fiRecipientAcctNumber']); ?>" type="text" name="fiRecipientAcctNumber"></td>
                    </tr>
                    <tr>
                        <td class="fieldLabel">Date of birth:</td>
                        <td class="fieldData"><input value="<?php echo htmlentities($current['fiRecipientDob']); ?>" type="text" name="fiRecipientDob">(yyyymmdd)</td>
                    </tr>
                    <tr>
                        <td class="fieldLabel">Post code:</td>
                        <td class="fieldData"><input value="<?php echo htmlentities($current['fiRecipientPostCode']); ?>" type="text" name="fiRecipientPostCode"></td>
                    </tr>
                    <tr>
                        <td class="fieldLabel">Surname:</td>
                        <td class="fieldData"><input value="<?php echo htmlentities($current['fiRecipientSurname']); ?>" type="text" name="fiRecipientSurname"></td>
                    </tr>
                </table>
            </div>
        <?php } ?>

        <div class="greyHzShadeBar">&nbsp;</div>

        <div class="formFooter">
            <a href="<?php echo $backUrl; ?>" style="float: left;"><img src="<?php echo BASE_PATH; ?>assets/images/back.gif" alt="Back" /></a>
            <input type="image" style="float: right;" src="<?php echo BASE_PATH; ?>assets/images/proceed.gif" alt="Proceed" />
        </div>

    </form>
</div>