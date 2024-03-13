define([
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'ko',
    'mage/storage',
    'mage/url',
    'Devall_Linkedin/js/model/linkedin-input-meta',
], function (
    Component,
    quote,
    ko,
    storage,
    url,
    linkedinInputMeta,
    ) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Devall_Linkedin/checkout/shipping/customer-linkedin-attribute',
            isVisible: linkedinInputMeta.isVisible,
            isRequired: linkedinInputMeta.isRequired,
            inputError: linkedinInputMeta.inputError,
            linkedinProfileValue: ko.observable(''),
        },
        initialize() {
            this._super();

            linkedinInputMeta.fetchAndSetData();
            linkedinInputMeta.fetchSetLoggedInValue(this.linkedinProfileValue);
        },
    });
});
