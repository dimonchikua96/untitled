const EventEmitter = require('events');

let total = 0;
let clientList = {};
let ipList = {};

class Clients extends EventEmitter{
    addClient (clientID, data) {
        if (typeof clientID !== 'undefined' && clientID != '') {
            this.emit('add_client', clientID);
            if (!(clientID in clientList)) {
                ++total;
            }
            let ip = data.remoteAddress.replace(/^.*:/, '');
            ipList[ip] = clientID;
            clientList[clientID] = data;
        }
    };

    removeClient (clientID) {
        if (typeof clientID !== 'undefined' && clientID != '') {
            if (typeof clientList[clientID] !== 'undefined') {
                this.emit('remove_client', clientID);
                let ip = clientList[clientID].remoteAddress.replace(/^.*:/, '');
                delete ipList[ip];
                delete clientList[clientID];
                --total;
            }
        }
    };

    getClient (clientID) {
        return clientList[clientID];
    };

    getCompassnameByIp (ip) {
        return ipList[ip];
    };

    getClients () {
        return clientList;
    };

    getClientsTSK () {
        let tsk = [];
        let deviceList = Object.keys(clientList);
        for (let i = 0; i < deviceList.length; i++) {
            if (deviceList[i].substring(0, 3) == "TSK") {
                tsk.push(deviceList[i])
            }
        }
        return tsk;
    };

    getIPs () {
        return ipList;
    };

    getDeviceList () {
        return Object.keys(clientList);
    };

    getTotal () {
        return total;
    };
}



module.exports = new Clients();