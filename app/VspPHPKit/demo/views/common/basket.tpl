<div id="content">
    <p id="contentHeader">Creating an Example Order</p>
    <p>This page demonstrates how to  create a very simple basket of goods. Use the form below to select the number of each DVD title you wish to buy, then hit Proceed. You have to select at least 1 DVD to continue. </p>

    <?php $this->renderContent('common/form_error'); ?>

    <p class="subheader">Please select the quantity of each item you wish to buy</p>
    <div class="basket">
        <form action="<?php echo $actionUrl; ?>" method="post" id="mainForm">
            <table class="formTable">
                <thead>
                    <tr class="greybar">
                        <th class="img">Image</th>
                        <th class="name">Title</th>
                        <th class="price">Price</th>
                        <th class="qty">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($products as $product)
                    {
                    ?>
                    <tr>
                        <td class="img">
                            <img src="<?php echo BASE_PATH . $product['image'] . '.gif'; ?>" alt="DVD box" />
                        </td>
                        <td class="name">
                            <?php echo $product['title']; ?>
                        </td>
                        <td class="price">
                            <?php echo number_format($product['price'] + $product['tax'], 2) . ' ' . $currency ?>
                        </td>
                        <td class="qty">
                            <select name="quantity<?php echo $product['id']; ?>" size="1">
                                <option value="0" <?php echo !isset($selectedProducts[$product['id']]) || $selectedProducts[$product['id']] == 0 ? 'selected="selected"' : '' ?>>None</option>
                                <?php
                                for ($i = 1; $i <= 50; $i++)
                                {
                                ?>
                                <option value="<?php echo $i; ?>"  <?php echo isset($selectedProducts[$product['id']]) && $selectedProducts[$product['id']] == $i ? 'selected="selected"' : '' ?>>
                                        <?php echo $i; ?>
                            </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="greyHzShadeBar">&nbsp;</div>
        <div class="formFooter">
            <a href="<?php echo $backUrl; ?>" style="float: left;">
                <img src="<?php echo BASE_PATH; ?>assets/images/back.gif" alt="Go back to the kit home page" border="0" alt="" />
            </a>
            <input type="image" src="<?php echo BASE_PATH; ?>assets/images/proceed.gif" alt="Proceed to the next page" style="float:right"/>
        </div>
    </form>
</div>
</div>
