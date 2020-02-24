import Echo from "laravel-echo"
window.io = require('socket.io-client');


try {
    window.$ = window.jQuery = require('jquery');
    // eslint-disable-next-line no-empty
} catch (e) { }


// Have this in case you stop running your laravel echo server
if (typeof io !== 'undefined') {
    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: window.location.hostname + ':6001',
    });
}
