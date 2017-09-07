class MessageBuilder {
    constructor() {
        this._buffer = Buffer.alloc(0);
        return this;
    }

    addInt8(value) {
        let _value = Buffer.alloc(1);
        _value.writeUInt8(value);
        this._buffer = Buffer.concat([this._buffer, _value]);
        return this;
    }

    addInt16(value) {
        let _value = Buffer.alloc(2);
        _value.writeUInt16BE(value);
        this._buffer = Buffer.concat([this._buffer, _value]);
        return this;
    }

    addInt32(value) {
        let _value = Buffer.alloc(4);
        _value.writeUInt32BE(value);
        this._buffer = Buffer.concat([this._buffer, _value]);
        return this;
    }

    addUTF(value) {
        if(!value){
            value = '';
        }
        let _value = Buffer.from(value.toString(),'utf8');
        this._buffer = Buffer.concat([this._buffer, _value]);
        return this;
    }

    addUTFLen16(value) {
        if(!value){
            value = '';
        }
        let _value = Buffer.from(value.toString(),'utf8');
        let _len = Buffer.alloc(2);
        _len.writeUInt16BE(_value.length);
        this._buffer = Buffer.concat([this._buffer, _len, _value]);
        return this;
    }

    addUTFLen32(value) {
        if(!value){
            value = '';
        }
        let _value = Buffer.from(value.toString(),'utf8');
        let _len = Buffer.alloc(4);
        _len.writeUInt32BE(_value.length);
        this._buffer = Buffer.concat([this._buffer, _len, _value]);
        return this;
    }

    addBuffer(value) {
        this._buffer = Buffer.concat([this._buffer, value]);
        return this;
    }

    get() {
        return this._buffer;
    }
}

module.exports = MessageBuilder;
