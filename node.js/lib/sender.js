const zlib = require('zlib');

const sender = (function () {
    return {
        send: function (socket, value) {

            if (!socket) {
                console.log(socket);
                throw new TypeError("Socket is not available now");
            }

            let body = zlib.deflateRawSync(value.getMessage());

            let preparedHeader = createHeaderBuffer(body.length, value.getId(), value.getHandler());

            if (!socket) {

                console.log(socket);
                throw new TypeError("Socket is not available now (after header creation)");
            }

            socket.write(Buffer.concat([preparedHeader, body]));
        }
    };

    function createHeaderBuffer(len, id, fn) {

        if (id > 0xFFFFFF || fn > 0xFFFFFF) {
            throw new RangeError("ERROR createHeader max value");
        }

        let lenBA = valueToBuffer(len);
        let idBA = valueToBuffer(id);
        let fnBA = valueToBuffer(fn);
        let b = new Buffer(lenBA.length + idBA.length + fnBA.length + 1);
        //b.writeUInt8((lenBA.length << 4) + (idBA.length << 2) + (fnBA.length << 0));
        b.writeUInt8((lenBA.length << 5) + (idBA.length << 3) + (fnBA.length << 1));
        lenBA.copy(b, 1);
        idBA.copy(b, 1 + lenBA.length);
        fnBA.copy(b, 1 + lenBA.length + idBA.length);
        return new Buffer(b);
    }

    function valueToBuffer(value) {
        let arr = [];
        let b = 0;
        let i = 4;
        while (i--) {
            b = (value >> i * 8) & 0xff;
            if (b !== 0 || arr.length > 0) {
                arr.push(b)
            }
        }
        return new Buffer(arr);
    }
}());
module.exports = sender;
