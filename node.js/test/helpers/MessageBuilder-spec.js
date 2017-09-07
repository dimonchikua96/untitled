let MessageBuilder = require('./../../helpers/MessageBuilder');

let should = require('should'),
    assert = require('assert'),
    supertest = require('supertest');


describe('MessageBuilder helper', function () {

    let messBuilder;
    let methods = ["addInt8", "addInt16", "addInt32", "addUTF", "addUTFLen16", "addUTFLen32", "addBuffer"];
    const MAX_UINT_8 = 0xff;
    const MAX_UINT_16 = Math.pow(MAX_UINT_8 + 1, 2) - 1;
    const MAX_UINT_32 = Math.pow(MAX_UINT_8 + 1, 4) - 1;

    beforeEach(() => {
        messBuilder = new MessageBuilder;
    });

    it('should create new MessageBuilder object', function (done) {
        //this.timeout(4000); - set timeout for test
        assert.equal(messBuilder instanceof MessageBuilder, true);
        //or
        should(messBuilder).be.an.instanceOf(MessageBuilder);
        done();
    });

    it('should return empty buffer in the beginning', function (done) {
        should(messBuilder.get()).be.an.instanceOf(Buffer);
        should(messBuilder.get()).have.size(0);
        done();
    });

    for (let method of methods) {
        it('should have method ' + method, function (done) {
            should(messBuilder[method]).be.Function();
            done();
        });
    }

    it('should write to Buffer int8', function (done) {
        should.throws(function () {
                messBuilder.addInt8(MAX_UINT_8 + 1)
            }
            , function (err) {
                if ((err instanceof Error) && /value/.test(err)) {
                    return true;
                }
            }, 'addInt8(' + (MAX_UINT_8 + 1) + ')');

        assert.equal(messBuilder.addInt8(MAX_UINT_8).get().readUInt8(), MAX_UINT_8);
        done();
    });

    it('should write to Buffer int16', function (done) {
        should.throws(function () {
                messBuilder.addInt16(MAX_UINT_16 + 1)
            }
            , function (err) {
                if ((err instanceof Error) && /value/.test(err)) {
                    return true;
                }
            }, 'addInt16(' + (MAX_UINT_16 + 1) + ')');

        assert.equal(messBuilder.addInt16(MAX_UINT_16).get().readUInt16BE(), MAX_UINT_16);
        done();
    });

    it('should write to Buffer int32', function (done) {
        should.throws(function () {
                messBuilder.addInt32(MAX_UINT_32 + 1)
            }
            , function (err) {
                if ((err instanceof Error) && /value/.test(err)) {
                    return true;
                }
            }, 'addInt32(' + (MAX_UINT_32 + 1) + ')');

        assert.equal(messBuilder.addInt32(MAX_UINT_32).get().readUInt32BE(), MAX_UINT_32);
        done();
    });

    it('should write utf text', function (done) {
        assert.equal(messBuilder.addUTF('hello').get().toString('utf8'), 'hello');
        done();
    });

    it('should write utf len=16 text', function (done) {

        should.throws(function () {
                messBuilder.addUTFLen16('hello'.repeat(99999));
            }
            , function (err) {
                if ((err instanceof Error) && /value/.test(err)) {
                    return true;
                }
            }, 'addUTFLen16');

        let _value = Buffer.from('hello', 'utf8');
        let _len = Buffer.alloc(2);
        _len.writeUInt16BE(_value.length);
        let checkString = Buffer.concat([_len, _value]);

        assert.equal(messBuilder.addUTFLen16('hello').get().toString('utf8'), checkString);
        done();
    });

    it('should write utf len=32 text', function (done) {


        let _value = Buffer.from('hello', 'utf8');
        let _len = Buffer.alloc(4);
        _len.writeUInt32BE(_value.length);
        let checkString = Buffer.concat([_len, _value]);

        assert.equal(messBuilder.addUTFLen32('hello').get().toString('utf8'), checkString);
        done();
    });


});

