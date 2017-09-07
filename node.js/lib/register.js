let Register = function () {
    let data = {};

    this.set = function (key, value) {
        if (typeof data[key] === 'undefined') {
            data[key] = value;
        } else {
            data[key] = value;
        }
    };
    this.unset = function (key) {
        if (typeof data[key] !== 'undefined') {
            delete data[key];
        }
    };
    this.unsetByVal = function (val) {
        for (let key in data) {
            if (data[key] == val) delete data[key];
        }
    };
    this.get = function (key) {
        return data[key];
    };
    this.getAll = function () {
        return data;
    }
};

module.exports = Register;
