<div id="content">

    <div id="contentHeader"><?php echo $command; ?> Transaction</div>

    <p>For your information, the internal request and response POST details are shown below.</p>

    <div class="greyHzShadeBar">&nbsp;</div>

    <div class="<?php echo ($status == 'OK' ? 'infoheader' : ''); ?>">Server returned a Status of <?php echo $status; ?></div>

    <table class="formTable">
        <tr>
            <td colspan="2"><div class="subheader">POST Sent to Server</div></td>
        </tr>
        <tr>
            <td colspan="2" style="word-wrap: break-word;" class="code">
                <?php echo htmlspecialchars($requestBody); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2"><div class="subheader">Raw Response from Server</div></td>
        </tr>
        <tr>
            <td colspan="2" style="word-wrap: break-word;" class="code">
            <?php echo $resultBody; ?>
        </td>
        </tr>
    </table>

    <div class="formFooter">
        <a href="<?php echo url(array($integrationType, 'admin')); ?>" style="float: left">
            <img src="<?php echo BASE_PATH; ?>assets/images/back.gif" alt="Back" border="0" />
        </a>
    </div>

</div>
