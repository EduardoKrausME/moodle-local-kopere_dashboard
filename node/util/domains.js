/*
 * Load as existing settings in domains.txt
 * */



var fs = require("fs");

//defaults
exports.allowed = ['config.php'];
exports.old_size = 0;

exports.loadDomains = function() {
    var domains_file = "domains.txt";

    try {
        var status = fs.statSync(domains_file);
    } catch (e) {
        console.error('There was an error processing your domains.txt file: ' + e.message);
        exports.allowed = ['config.php'];
        return;
    }

    if (exports.old_size == status.size) {
        console.log("size is equals");
    }
    exports.old_size = status.size;
    exports.allowed = []; // If the variable already has a value, clear.

    var config_domains = "";
    try {
        config_domains = fs.readFileSync(domains_file).toString();
    } catch (e) {
        console.error('There was an error processing your domains.txt file: ' + e.message);
        exports.allowed = ['config.php'];
        return;
    }

    var list_domains = config_domains.split("\n");

    for (var k in list_domains) {
        var domain = list_domains[k];
        domain = domain.trim();

        if (domain.length > 0) {
            if (domain[0] != '#') {
                exports.allowed.push(domain);
            }
        }
    }
};

exports.loadDomains();