var uuidid;
var DMP = {
    localAPIAddr: '/dmp/',
    formID:       'mainForm',
    submitFunction: '',
    callBackFunction: '',
    remoteAPIStatus: false,
    remoteAPIMessage: 'Not Set',
    remoteAPIResponse: '',

    init: function(formID = 'mainForm', localAPIAddr = '/dmp/', submitFunction = '',callBackFunction = '') {
        DMP.localAPIAddr     = localAPIAddr;
        DMP.formID           = formID;
        DMP.submitFunction   = submitFunction;
        DMP.callBackFunction = callBackFunction;
    },

    send: function() {
        var formData = DMP.getFormData();

        DMP.sendRequest(formData);
        DMP.execSubmitFunction();
    },

    execSubmitFunction: function() {
        if (DMP.submitFunction.length > 0) {
            eval(DMP.submitFunction + '();');
        }
    },

    submitCallBack: function() {
        if (DMP.callBackFunction.length > 0) {
            eval(DMP.callBackFunction + '();');
        }
    },

    sendRequest: function(formData) {
        var host = window.location.protocol + '//' + window.location.hostname;
        var xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                DMP.setRemoteResponse(xhr.responseText);
                DMP.submitCallBack();
            }
        }

        xhr.open("POST", host + DMP.localAPIAddr, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(formData);
    },

    setRemoteResponse: function(remoteResponse) {
        var status  = false;
        var message, response = '';

        if (typeof(remoteResponse) == "string" && remoteResponse.length > 0){
          
            
           
            remoteResponse = JSON.parse(remoteResponse);
            uuidid = remoteResponse;
        }

        if (typeof(remoteResponse.status) != 'undefined') {
            status = !(String(remoteResponse.status) != 'true'); 
        }

        if (typeof(remoteResponse.data) != 'undefined') {
            message = String(remoteResponse.data);
          
        }

        if (message.length < 1 && status) {
            message = 'Successfully Saved';
        }

        if (message.length < 1) {
            message = 'Unknown Remote API Error';
        }

        if (typeof(remoteResponse.response) != 'undefined') {
            
            response = String(remoteResponse.response);
          
        }

        DMP.remoteAPIStatus = status;
        DMP.remoteAPIMessage = message;
        DMP.remoteAPIResponse = response;
    },

    getFormData: function () {
        var form = document.getElementById(DMP.formID);
        var serialized = [];

        for (var i = 0; i < form.elements.length; i++) {
            var field = form.elements[i];

            if (
                !field.name ||
                field.disabled ||
                field.type === 'file' ||
                field.type === 'reset' ||
                field.type === 'submit' ||
                field.type === 'button'
            ) {
                continue;
            }

            if (field.type === 'select-multiple') {
                for (var n = 0; n < field.options.length; n++) {
                    if (!field.options[n].selected) {
                        continue;
                    }

                    serialized.push(encodeURIComponent(field.name) + "=" + encodeURIComponent(field.options[n].value));
                }
            } else if ((field.type !== 'checkbox' && field.type !== 'radio') || field.checked) {
                serialized.push(encodeURIComponent(field.name) + "=" + encodeURIComponent(field.value));
            }
        }

        return serialized.join('&');
    }
}