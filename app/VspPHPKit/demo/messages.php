<?php defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Messages for validation's errors
 */
return array(
    'regex' => '%s is invalid',
    'email' => '%s is invalid',
    'notEmpty' => '%s is missing ',
    'validUsa' => '%s is illegal ',
    'notEmptyZipCodeUK' => '%s (UK only) is missing ',
    'maxLength' => '%s is too large',
    'minLength' => '%s is too short',
    'exactLength' => '%s is invalid',
    'creditCard' => '%s is invalid',
    'numeric' => '%s is not numeric',
    'range' => '%s is out of range',
    'OK' => 'Transaction completed successfully with authorisation.',
    'NOTAUTHED' => 'The Sage Pay system could not authorise the transaction because the details provided by the Customer were incorrect, or insufficient funds
        were available. However the Transaction has completed through the Sage Pay System.',
    'ABORT' => 'The Transaction could not be completed because the user clicked the CANCEL button on the payment pages.',
    'REJECTED' => 'The Sage Pay System rejected the transaction because of the fraud screening rules you have set on your account.',
    'AUTHENTICATED' => 'The 3D-Secure checks were performed successfully and the card details secured at Sage Pay.',
    'REGISTERED' => '3D-Secure checks failed or were not performed, but the card details are still secured at Sage Pay.',
    'ERROR' => 'A problem occurred at Sage Pay which prevented transaction completion.',
    'CANCEL' => 'You chose to Cancel your order on the payment pages.'
);