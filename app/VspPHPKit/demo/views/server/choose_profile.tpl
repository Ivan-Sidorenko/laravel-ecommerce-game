<div id="content">
    <p id="contentHeader">Choose profile mode</p>
    <p>
        The kit demonstrates both the low and normal profile modes of the Server integration method.
        The selection you make below will be stored in your session (default is normal profile).
    </p>
    <ul class="normalFont">
        <li>
            <a href="<?php echo $normalProfileUrl; ?>"><b>normal profile mode</b></a>
            Sage Pay delivers a full page containing a logo and a card type select option.
            The customer then proceeds to another page where they are
            presented with the card and billing address details form.
            Then there is a review page which displays the information entered so far.
        </li>
        <li>
            <a href="<?php echo $lowProfileUrl; ?>"><b>low profile mode</b></a>
            Sage Pay delivers a card and billing address details form with
            proceed and cancel buttons. This form does not ask for the card type.
            Choosing this mode will cause this demo application to render the Sage Pay
            content in an iFrame.
        </li>
    </ul>
</div>

