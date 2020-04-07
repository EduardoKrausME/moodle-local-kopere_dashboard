/*
 * Reads the settings from settings.json and supplies defaults for any
 * missing settings.
 * */

var fs         = require("fs"),
    os         = require("os"),
    jsonminify = require('jsonminify');

//defaults
exports.defaults = {
    "port" : 8080,
    "ssl"  : false
};


exports.loadSettings = function() {
    var settings_file = "settings.json";
    var user_settings = {};

    try {
        user_settings = fs.readFileSync(settings_file).toString();
        //minify to remove comments and whitepsace before parsing
        user_settings = JSON.parse(JSON.minify(user_settings));
    } catch (e) {

        console.error("Verifique se você renomeou o 'settings.json.template' para 'settings.json'");

        console.error('There was an error processing your settings.json file: ' + e.message);
        process.exit(1);
    }

    //copy over defaults
    for (var k in exports.defaults) {
        exports[k] = exports.defaults[k];
    }

    //go through each key in the user supplied settings and replace the defaults
    //if a key is not in the defaults, warn the user and ignore it
    for (var k in user_settings) {
        if (k in exports.defaults) {
            //overwrite it
            exports[k] = user_settings[k];
        } else {
            console.warn("'Unknown Setting: '" + k + "'. This setting doesn't exist or it was removed");
        }
    }
};

exports.loadSettings();
