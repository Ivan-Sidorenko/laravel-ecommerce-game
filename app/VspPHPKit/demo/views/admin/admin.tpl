<div id="content">
    <div id="contentHeader">Payment Administration Menu</div>
    <p class="warning">Back office functions like this should have access restrictions applied in your real application!</p>
    <p>The table below lists all the transactions in the local database in reverse date order.</p>
    <p>For a full list of back office functions see the Server and Direct Shared Protocols document.</p>
    <div class="greyHzShadeBar">&nbsp;</div>
    <p class="subheader">Transactions</p>
    <table class="formTable adminOrders">
        <tr class="greybar">
            <td>Created</td>
            <td>VendorTxCode</td>
            <td>TxType</td>
            <td align="right">Amount</td>
            <td align="right">Captured</td>
            <td>CCY</td>
            <td>Stat</td>
            <td>Message</td>
            <td>Actions</td>
        </tr>
        <?php
        foreach ($payments as $row)
        {
            ?>
            <tr>
                <td><?php echo $row['created']; ?></td>
                <td class="vendorTxCode"><?php echo $row['vendorTxCode']; ?></td>
                <td><?php echo $row['transactionType']; ?></td>
                <td align="right"><?php echo number_format($row['amount'], 2); ?></td>
                <td align="right"><?php echo $row['capturedAmount']; ?></td>
                <td><?php echo $row['currency']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td class="orderStatusMessage"><?php echo $row['statusMessage']; ?></td>
                <td class="orderActions" align="left">
                    <?php if ($row['status'] == 'OK'): ?>

                        <?php /* We can Refund, Repeat or Void any captured transaction that is not a REFUND */ ?>
                        <?php if ($row['capturedAmount'] > 0.0 && $row['transactionType'] != 'REFUND'): ?>
                            <a href="<?php echo url(array($integrationType, 'refund')); ?>/?origVtx=<?php echo $row['vendorTxCode']; ?>">Refund</a>

                            <?php if (!($row['cardType'] == 'PAYPAL' && in_array($row['transactionType'], array('AUTHORISE', 'DEFERRED')))): ?>
                                <a href = "<?php echo url(array($integrationType, 'repeat')); ?>/?deferred=false&amp;origVtx=<?php echo $row['vendorTxCode']; ?>">
                                    Repeat</a>
                            <?php endif; ?>
                            <?php if ($row['cardType'] != 'PAYPAL'): ?>
                                <a href = "<?php echo url(array($integrationType, 'repeat')); ?>/?deferred=true&amp;origVtx=<?php echo $row['vendorTxCode']; ?>"
                                   title = "Repeat Deferred">RepDef</a>
                               <?php endif; ?>
                           <?php endif; ?>

                        <?php if (!in_array($row['cardType'], array('EPS', 'SOFORT', 'ELV', 'IDEAL', 'GIROPAY', 'PAYPAL')) && (($row['capturedAmount'] > 0.0 && $row['transactionType'] != 'REFUND') || $row['transactionType'] == 'REFUND')):
                            ?>
                            <a href="<?php echo url(array($integrationType, 'void')); ?>/?origVtx=<?php echo $row['vendorTxCode']; ?>">Void</a>
                        <?php endif; ?>

                        <?php
                        // We can Release/Abort any non-captured (i.e. once) DEFERRED payment
                        //  The kit will set captured amount only upon successful release.
                        if (empty($row['capturedAmount']) && in_array($row['transactionType'], array('DEFERRED', 'REPEATDEFERRED')))
                        {
                            ?>
                            <a href="<?php echo url(array($integrationType, 'release')); ?>/?origVtx=<?php echo $row['vendorTxCode']; ?>">Release</a>
                            <a href="<?php echo url(array($integrationType, 'abort')); ?>/?origVtx=<?php echo $row['vendorTxCode']; ?>">Abort</a>
                            <?php
                        }
                    endif;
                    // We can Authorise/Cancel any AUTHENTICATE even after previous captures
                    if ($row['status'] == 'AUTHENTICATED' || $row['status'] == 'REGISTERED' && $row['transactionType'] == 'AUTHENTICATE')
                    {
                        ?>
                        <a href="<?php echo url(array($integrationType, 'authorise')); ?>/?origVtx=<?php echo $row['vendorTxCode']; ?>">Authorise</a>
                        <a href="<?php echo url(array($integrationType, 'cancel')); ?>/?origVtx=<?php echo $row['vendorTxCode']; ?>">Cancel</a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
    <div class="deleteAll deleteTransactions">
        <p>Delete all transactions in your local database</p>
        <form action="<?php echo url(array($integrationType, 'delete_all_payments')); ?>" method="post"
              onsubmit="return confirm('Sure? This will remove all entries in the local database')">
            <input type="submit" value="Delete All" />
        </form>
    </div>
    <p class="subheader">Customers</p>
    <table class="formTable adminCustomers">
        <tr class="greybar">
            <td>ID</td>
            <td>Created</td>
            <td>Email</td>
            <td>Token Count</td>
        </tr>
        <?php
        foreach ($customers as $row)
        {
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['created']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['cards']; ?></td>
            </tr>
        <?php } ?>
    </table>
    <div class="deleteAll deleteCustomers">
        <p>Delete all customers in your local database</p>
        <form action="<?php echo url(array($integrationType, 'delete_all_customers')); ?>" method="post"
              onsubmit="return confirm('Sure? This will remove all entries in the local database')">
            <input type="submit" value="Delete All" />
        </form>
    </div>
    <div class="greyHzShadeBar">&nbsp;</div>
    <div class="formFooter">
        <a href="<?php echo url($integrationType); ?>" style="float: left;">
            <img src="<?php echo BASE_PATH; ?>assets/images/back.gif" alt="Back" />
        </a>
    </div>
</div>
