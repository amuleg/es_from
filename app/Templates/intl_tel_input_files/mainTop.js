function intlTelInputToForm(dataId, countryCode) {
    const form = document.querySelector(`form[data-local-form=${dataId}]`);
    const input = form.querySelector('input[type=tel]');
    
    window.intlTelInput(input, {
        autoFormat: true,
        autoPlaceholder: "aggressive",
        defaultCountry: countryCode,
        initialCountry: "auto",
        separateDialCode: true,
        geoIpLookup: function(success, failure) {
            success(countryCode);
        },
        nationalMode: true,
        hiddenInput: "phone",
        numberType: "MOBILE",
        utilsScript: "/api/intl-tel-input/utils.js",
    });
};

function beforeCloseBody() {
    let inputs = document.querySelectorAll("input[type=tel]");
    if (inputs.length > 0) {
        document.querySelectorAll('form').forEach(function(el) {
            el.addEventListener('submit', function() {
                document.querySelector('body').classList.add("unavailable");
            });
        });
    }
}