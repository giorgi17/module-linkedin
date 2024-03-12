define([
    'uiRegistry',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'jquery',
    'mage/storage',
    'mage/url',
    'Devall_Linkedin/js/model/linkedin-input-meta',
], function(
    registry,
    wrapper,
    quote,
    $,
    storage,
    url,
    linkedinInputMeta,
) {
    'use strict';

    const showErrors = (errorMessage) => {
        $('#linkedin_profile-error').html(errorMessage).show();
    }

    const clearErrors = () => {
        $('#linkedin_profile-error').hide();
    }

    const isLinkedinValidMethod = function (linkedinValue, callback) {
        const deferred = $.Deferred();

        const serviceUrl = url.build('linkedin/linkedinvalidate/isvalid');
        const postData = {
            linkedinLink: linkedinValue,
        };

        storage.post(serviceUrl, JSON.stringify(postData)).done(
            function (response) {
                if (response?.isValid) {
                    deferred.resolve(callback());
                    clearErrors();
                } else {
                    const message = response?.messages[0];
                    console.log("IsNotRequired", message);
                    showErrors(message);

                    deferred.reject();
                }
            }
        ).fail(
            function (response) {
                return response.value
            }
        );

        return deferred.promise();
    }

    return function(setShippingInformationAction) {
        return wrapper.wrap(setShippingInformationAction,  function(originalAction) {
            const attributeCode = 'linkedin_profile';
            const shippingAddress = quote.shippingAddress();
            const attribute = $(`#${attributeCode}`).val();

            shippingAddress.extension_attributes = shippingAddress.extension_attributes || {};

            if (attribute) {
                shippingAddress.extension_attributes[attributeCode] = attribute;
            }

            if (linkedinInputMeta.isVisible()) {
                if (!linkedinInputMeta.isRequired() && attribute === '')
                    return originalAction();
                else
                    return isLinkedinValidMethod(attribute, originalAction);
            } else {
                return originalAction();
            }
        });
    };
});
