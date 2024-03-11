define([
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'jquery',
], function(
    wrapper,
    quote,
    $,
) {
    'use strict';

    $.validator.addMethod("linkedinProfileValidation", function(value, element) {
        // Regular expression to validate URL format
        const urlPattern = /^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([/\w .-]*)*\/?$/;

        // Check if the value is a valid URL and has a maximum length of 250 characters
        return urlPattern.test(value) && value.length <= 250;
    }, "Please enter a valid LinkedIn profile");

    const validateLinkedinProfile = function () {
        const formElement = $('.form-shipping-address');

        formElement.validate({
            rules: {
                'linkedin_profile': {
                    required: true,
                    linkedinProfileValidation: true // Use the custom validation method
                }
            }
        });

        return formElement.valid();
    }

    return function(setShippingInformationAction) {
        return wrapper.wrap(setShippingInformationAction, function(originalAction) {
            const attributeCode = 'linkedin_profile';
            const shippingAddress = quote.shippingAddress();
            const attribute = $(`#${attributeCode}`).val();

            shippingAddress.extension_attributes = shippingAddress.extension_attributes || {};

            if (attribute) {
                shippingAddress.extension_attributes[attributeCode] = attribute;
            }

            const isInputValid = validateLinkedinProfile();

            if (isInputValid) {
                return originalAction();
            } else {
                console.log('CustomerLinkedinValidation', 'CustomerLinkedin is not valid');
            }
        });
    };
});
