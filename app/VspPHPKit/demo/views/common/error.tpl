<div id="content">

    <div id="contentHeader">Error</div>

    <p class="warning"><?php echo $errorMessage; ?></p>

    <?php if (!empty($exception))
    {
    ?>
    <div style="border: 1px solid #ccc; padding: 5px; overflow: auto; ">
        <p>Configuration is set to debug display so exception details are shown below (see log file for full information).</p>  
        <pre class="warning"><?php $exception->getTrace()?></pre>
    </div>
    <?php
    }
    ?>

    <div class="greyHzShadeBar">&nbsp;</div>

    <div class="formFooter">
        <table border="0" width="100%">
            <tr>
                <td width="50%" align="left">
                <a href="<?php echo $backUrl; ?>" title="Home"><img src="<?php echo BASE_PATH; ?>assets/images/back.gif" alt="Go back to the home page" border="0" /></a>
                </td>
                <td width="50%" align="right">
                </td>
            </tr>
        </table>
    </div>

</div>
