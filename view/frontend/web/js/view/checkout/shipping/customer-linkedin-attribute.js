define([
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'ko'
], function (Component, quote, ko) {
    'use strict';

    console.log('CustomerLinkedin');

    return Component.extend({
        defaults: {
            template: 'Devall_Linkedin/checkout/shipping/customer-linkedin-attribute'
        },
        // getLinkedinProfile: function () {
        //     return quote.shippingAddress().customAttributes.linkedin_profile;
        // }
        // validateLinkedinProfile: function () {
        //     const formElement = $('.checkout-step-shipping form');
        //
        //     if (formElement.valid()) {
        //         console.log('CustomerLinkedin', 'CustomerLinkedin is valid');
        //
        //     } else {
        //         console.log('CustomerLinkedin', 'CustomerLinkedin is not valid');
        //     }
        // }
    });
});
