const Register = require('./../lib/register');
const requestIdPool = new Register();


function getId(compassname) {
    let id = requestIdPool.get(compassname);
    if( typeof id === 'undefined'){
        id = 0;
    }

    id++;

    if (id > 0xFFFFFF) {
        id = 1;
    }
    requestIdPool.set(compassname, id);
    return id;
}

let Request = function Request(compassname, handler, message) {

    let _id = getId(compassname);
    let _handler = handler;
    let _message = new Buffer(0);

    if (message && message.length > 0) {
        _message = new Buffer.from(message);
    }

    this.getId = function () {
        return _id;
    };

    this.getHandler = function () {
        return _handler;
    };

    this.getMessage = function () {
        return _message;
    };
};

let Response = function Request(id, handler, message) {

    let _id = id;
    let _handler = handler;
    let _message = new Buffer(0);

    if (message && message.length > 0) {
        _message = new Buffer.from(message);
    }

    this.getId = function () {
        return _id;
    };

    this.getHandler = function () {
        return _handler;
    };

    this.getMessage = function () {
        return _message;
    };

};

exports.request = function (compassname, handler, message) {
    return new Request(compassname, handler, message);
};

exports.response = function (id, handler, message) {
    return new Response(id, handler, message);
};