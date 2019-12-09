# Paystation 2-party sample

This demonstrates how to setup a simple purchase request to the our [api](https://docs.paystation.co.nz/)

## Requirements

You'll need to supply your own Paystation gateway credentials, if you don't have these please [contact us](https://www2.paystation.co.nz/contact-us)

## Installation

Configuration variables are all hardcoded at the top of the `index.php` file and should be filled with details supplied by Paystation.
This is purely for proof of concent, we recommend not copying this practice and using a secure way of storing these variables in your environments.

- PaystationAccount - Test Paystation Account ID - (e.g 600000)
- PaystationGateway - Test Paystation Gateway ID - (e.g PAYSTATION)
- HmacKey - Test HMAC key (only required for HMAC authenticated endpoints)
- CardNumber - Test card - [List of test cards](https://www2.paystation.co.nz/developers/test-cards/)
- Expiry - Test expiry
