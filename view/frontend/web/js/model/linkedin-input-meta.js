define([
    'ko',
    'mage/storage',
    'mage/url',
    'jquery',
], function (
    ko,
    storage,
    url,
    $,
) {
    return {
        isVisible: ko.observable(false),
        isRequired: ko.observable(false),
        inputError: ko.observable(false),
        fetchAndSetData: function () {
            const serviceUrl = url.build('linkedin/linkedinvalidate/requiredvisible');

            storage.get(serviceUrl).done(
                (response) => {
                    this.isVisible(response.isVisible);
                    this.isRequired(response.isRequired);
                }
            ).fail(
                (response) => {
                    return response.value
                }
            );
        },
        fetchSetLoggedInValue: function (inputValue) {
            const serviceUrl = url.build('linkedin/linkedinvalidate/loggedinvalue');

            storage.get(serviceUrl).done(
                (response) => {
                    if (response?.isLoggedIn) {
                        if (inputValue) {
                            inputValue(response?.loggedUserValue);
                        }
                    }
                }
            ).fail(
                (response) => {
                    return response.value
                }
            );
        }
    }
})
