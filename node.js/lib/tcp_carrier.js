const util = require('util'),
    events = require('events'),
    zlib = require('zlib');

function Message(reader, callback) {

    let self = this,
        mask = null,
        headersLen = null,
        headers = null,
        headersData = null,
        bodyLen = null;

    if (callback) {
        self.addListener('line', callback);
    }

    function readPacket() {
        if (bodyLen === null) {

            //читаем маску, в которой укзаны длины занимаемые заголовками и сообщениями
            if (!mask) {
                mask = reader.read(1);
            }
            if (!mask) {
                return false;
            }

            //разбираем маску, в которой указано сколько байт отведено под заголовки (длина|id|handler),
            // если маска разобрана, повторно это делать не надо
            if (!headersLen) {
                headersLen = getHeadersLen(mask);
            }

            //вычитываем заголовки, зная их суммарную длину
            if (!headers) {
                headers = reader.read(headersLen.totalBytesLen);
            }

            if (!headers) {
                return false;
            }

            //получаем метаданные запроса (длину сообщения|id|handler)
            headersData = getHeaderData(headers, headersLen);

            //указываем длину, которую необходимо перехватить
            bodyLen = headersData.messLen;
        }


        //если длина укзана, обрабатываем сообщение
        if (bodyLen === 0) {
            headersData.handler = headersData.handler > 9 ? headersData.handler.toString(16).toUpperCase() : "0" + headersData.handler.toString(16).toUpperCase();
            self.emit('line', {id: headersData.id, handler: headersData.handler, message: new Buffer(0)});
        } else {
            const body = reader.read(bodyLen);
            if (!body) {
                return false;
            }
            headersData.handler = headersData.handler > 9 ? headersData.handler.toString(16).toUpperCase() : "0" + headersData.handler.toString(16).toUpperCase();
            /*
             console.log('---------------------------' );
             console.log('compressed:' + incoming_data.message.length);
             console.log('Fin host START----------:' + Date.now());
             incoming_data.message = zlib.inflateRawSync(incoming_data.message);
             console.log('uncompressed:' + incoming_data.message.length);
             console.log('Fin host END----------:' + Date.now());
             */
            self.emit('line', {
                id: headersData.id,
                handler: headersData.handler,
                message: zlib.inflateRawSync(body)
            });
        }
        cleanMessageFlags();
        return true;
    }

    function cleanMessageFlags() {
        mask = null;
        headersLen = null;
        headers = null;
        headersData = null;
        bodyLen = null;
    }

    reader.on('readable', () => {
        try {
            let remaining = false;
            do {
                remaining = readPacket();
            }
            while (remaining);
        } catch (err) {
            //console.error(err);
        }
    });
}

//получение данных из заголовков
function getHeaderData(headers, headersLen) {
    return {
        'messLen': valueFromBA(headers, 0, headersLen.messBytesLen),
        'id': valueFromBA(headers, headersLen.messBytesLen, headersLen.idBytesLen),
        'handler': (valueFromBA(headers, headersLen.messBytesLen + headersLen.idBytesLen, headersLen.handlerBytesLen))
    };
}

//получение данных из буффера
function valueFromBA(b, startPos, len) {
    let i = len;
    let res = 0;
    while (i--) {
        res += b.readUInt8(startPos++) << i * 8;
    }
    return res;
}

//получение длин заголовков
function getHeadersLen(a) {
    if (a instanceof Buffer) {
        a = a.readUInt8();
    }

    let len = {
        'messBytesLen': a >> 5,
        'idBytesLen': (a & 0x18) >> 3,
        'handlerBytesLen': (a & 0x06) >> 1
    };
    len.totalBytesLen = len.messBytesLen + len.idBytesLen + len.handlerBytesLen;
    return len;
}

util.inherits(Message, events.EventEmitter);

exports.handle = function (reader, listener) {
    return new Message(reader, listener);
};