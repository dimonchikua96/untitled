let config = require("./../config");
let Register = require("./register");
let storage = new Register();
let fs = require('fs');

//in seconds
let repeat_time_limit = {
    cache_update_period: 300
};

(function update_cache() {

    for (let url in config.external_services) {
        let data = {};

        for (let current_config in config.external_services[url]) {
            data[current_config] = config.external_services[url][current_config]
            if (['cert', 'ca', 'key'].indexOf(current_config) != -1 && config.external_services[url][current_config] != '') {
                try {
                    data[current_config] = fs.readFileSync(__dirname + '/../cert/' + config.external_services[url][current_config])
                } catch (e) {
                    delete data[current_config];
                    console.error('Certificate storage error: ' + e.message)
                }
            }
        }

        storage.set(url, data)
    }
    setTimeout(update_cache, repeat_time_limit.cache_update_period * 1000);
})();

module.exports.get = function (service_id) {
    return storage.get(service_id);
};