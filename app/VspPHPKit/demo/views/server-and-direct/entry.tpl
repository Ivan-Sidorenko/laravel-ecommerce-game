<div id="content">
    <div id="contentHeader">Customer Registration Page</div>

    <div class="loginForm">
        <p>To continue shopping, please login or create a new customer account.</p>

        <?php $this->renderContent('common/form_error'); ?>

        <form id="CustomerLogin" method="post" action="<?php echo url(array($controller, 'entry')); ?>">
            <label for="email">Email Address</label>
            <input name="email" id="email" type="text" value="<?php echo htmlentities($current['email']); ?>" size="25" maxlength="60">
            <label for="password">Password</label>
            <input name="password" type="password" id="password" value="<?php echo htmlentities($current['password']); ?>" size="20" maxlength="20">
            <input type="submit" value="Login/Register"/>
        </form>
    </div>

    <p>If the email address you enter above is not recognised in the local database, a new account will be created automatically.</p>

    <p>
        This is a very basic login facility to associate a "Customer" to a "Payment".
        This feature is to demonstrate use of Sage Pay's Token system.
        Clearly your own web site will need much fuller functionality with proper security.
    </p>
</div>