/**
 * Created by kudlaty01 on 07.08.18.
 */
ajax = {
    post: function (address, data, callback) {
        var r = new XMLHttpRequest();
        r.open("POST", address, true);
        r.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        r.onreadystatechange = function () {
            if (this.readyState != 4 || this.status != 200) return;
            callback(this.responseText);
        };
        r.send(ajax.param(data));
    },
    param: function (object) {
        var encodedString = '';
        if (object) {
            for (var prop in object) {
                if (object.hasOwnProperty(prop)) {
                    if (encodedString.length > 0) {
                        encodedString += '&';
                    }
                    encodedString += encodeURI(prop + '=' + object[prop]);
                }
            }
        }
        return encodedString;
    }
};
