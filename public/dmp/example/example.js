window.onload = function() {
    addSubmitEvent();
}

function submitForm() {
    document.getElementById('dmp_api_result').innerHTML = 'Sending Data To API...';
}

function getAPIResponse() {
    if (DMP.remoteAPIStatus) {
        alert(DMP.remoteAPIMessage);

        setTimeout(function(){
            window.location.reload();
        }, 1000);

        return true;
    }

    document.getElementById('dmp_api_result').innerHTML = 'Error: ' + DMP.remoteAPIMessage;

    return false;
}

function addSubmitEvent() {
    var inputForm = document.getElementById('test_form');

    inputForm.addEventListener("submit", function(event) {
        if (DMP.remoteAPIStatus) {
            return true;
        }

        event.preventDefault();

        DMP.init(
            formID = 'test_form',
            localAPIAddr = '/dmp/',
            submitFunction = 'submitForm',
            callBackFunction = 'getAPIResponse'
        );

        DMP.send();

        return false;
    });
};