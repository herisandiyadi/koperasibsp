
window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });

// window.PNotify = require('pnotify/dist/es/PNotify.js');
// window.PNotifyButtons = require('pnotify/dist/es/PNotifyButtons');
// window.PNotifyStyleMaterial = require('pnotify/dist/es/PNotifyStyleMaterial.js');
import PNotify from 'pnotify/dist/es/PNotify';
import PNotifyButtons from 'pnotify/dist/es/PNotifyButtons';
import PNotifyStyleMaterial from 'pnotify/dist/es/PNotifyStyleMaterial';
import PNotifyConfirm from 'pnotify/dist/es/PNotifyConfirm';

window.PNotify = PNotify;
window.PNotifyButtons = PNotifyButtons;
window.PNotifyStyleMaterial = PNotifyStyleMaterial;
window.PNotifyConfirm = PNotifyConfirm;
// Set default styling.
PNotify.defaults.styling = 'material';
// // This icon setting requires the Material Icons font. (See below.)
PNotify.defaults.icons = 'material';

window.errors = [];
window.flashed = {
    success: [],
    errors:[],
    warning:[],
    info:[],
    notice:[]
};

import moment from 'moment';
window.moment = moment;

var notice = PNotify.notice({
    title: 'Confirmation Needed',
    text: 'Are you sure?',
    // icon: 'fas fa-question-circle',
    hide: false,
    stack: {
        'dir1': 'down',
        'modal': true,
        'firstpos1': 25
    },
    modules: {
        Confirm: {
            confirm: true
        },
        Buttons: {
            closer: false,
            sticker: false
        },
        History: {
            history: false
        },
    }
});
notice.on('pnotify.confirm', function() {
    alert('Ok, cool.');
});
notice.on('pnotify.cancel', function() {
    alert('Oh ok. Chicken, I see.');
});