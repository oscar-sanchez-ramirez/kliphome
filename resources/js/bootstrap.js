window._ = require('lodash');
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

// try {
//     window.Popper = require('popper.js').default;
//     // window.$ = window.jQuery = require('jquery');

//     require('bootstrap');
// } catch (e) {}

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
// Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQwZDUxZWZhYWQ4MzgyNDUwMjdkNDFmNTY2ZmU3ZmQ4OGNkZTM4ZTIwMWNmNDNiMThiZjU0ZTIwMmYxZTRmN2I0NGYwYTBmYzUwOTQyNjExIn0.eyJhdWQiOiI2IiwianRpIjoiNDBkNTFlZmFhZDgzODI0NTAyN2Q0MWY1NjZmZTdmZDg4Y2RlMzhlMjAxY2Y0M2IxOGJmNTRlMjAyZjFlNGY3YjQ0ZjBhMGZjNTA5NDI2MTEiLCJpYXQiOjE1Nzg4NjQ3MjUsIm5iZiI6MTU3ODg2NDcyNSwiZXhwIjoxNTg0MDQ4NzI1LCJzdWIiOiIxNCIsInNjb3BlcyI6W119.ZgSd1m7YfEEhaJzWq1qcU_9mPuBEtadgHfcIot2xIzbTOJYMbm2L_O0oZJGYqrmaeljTFMbjhM-rm-hBg80EPFawE5muYnF8Oyi9htZo2LYAIzTCA_Fk4PivGML2rBbZm6S3SAc2CYmwLP7sVGDY-RSPxng3pSbWVzhgWeOankVyd5y0mqiI0x-awKqlfW9mzgfanGVYxx2wbxoURscWpUcXyfoKT7dtefhuQk3atotEQHoTgtcibZYM5jkILQkA7PfdnBRI1kjqvzz63kAwrR5iM2Ey1D4TYipISL2kvl5d13RMaQOs08QUH02hmgU6axuyq8i7fa1kpSUJUirop4p83TOzUMaDvhYb1RcQnQlUOkQWU0zL3BfYa6bslqJQM2QvjBiKD0Fm1XC8DSK0QekQmF-WzkiU1SslwpuycI01wRdP6bUTGr4_u4RZi7K6Cj1Vm8iIHA_s-LcAwmXcVVG9l1c2YRYKrhHxoL73VN1cYP61YadJJtkscxQzDwUApUDkCnd-dtK4Xc4ZTyxiTvgeGZJNrB2l-EFFWEELgxpyM2ZGFtwyafMYHvC2FX5OhQDPq_hg2_xux4J95JaLZW4HeBKYhCb1H1M40Lb9Q6LFLiJZ5WxyVW4vNaUQ5DOM_1tboJRWfYoSgRN3KYer8gQoGd_Jz1zKxMYvmm6FOt0',

import Echo from 'laravel-echo'
window.io = require('socket.io-client');

// window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: 'http://kliphome.com:6001',
    auth:{
        headers:{
            Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjZhYWYwOTRkYWNiNzI0ZDZiOTgzZGZmMjU2MDU4OTk3NzRlMjIwMzk1MmVmYjg2OGY5YzIxNTAyM2RhMzdmMWQ5ODViMzlmY2VhZmE4N2FlIn0.eyJhdWQiOiI4IiwianRpIjoiNmFhZjA5NGRhY2I3MjRkNmI5ODNkZmYyNTYwNTg5OTc3NGUyMjAzOTUyZWZiODY4ZjljMjE1MDIzZGEzN2YxZDk4NWIzOWZjZWFmYTg3YWUiLCJpYXQiOjE1NzkxMTM0MDAsIm5iZiI6MTU3OTExMzQwMCwiZXhwIjoxNTg0Mjk3NDAwLCJzdWIiOiIxOSIsInNjb3BlcyI6W119.NBnhxgbYXUNV8O1ir70VB3pftxQ5WYg5AIVPLDIBElWnNG4j-a9xfcih_z5exfRd6tCyt1Y-eWIW2G9HpmthU0O9NFEtMI0fP1GDKPNWWGQR5OPSLbnm_kCbhojucPAGYobG7fSQqGfJowqGp_H8YlPh-YvutzCy7iawDMTbbOjDp6c7Vqr74DdOZ4Bl89XeTfe6yvR1Iom4tFZXcpeXvTizLUQ611hUC2XJbzQELHalwLSw1XVIn85pV8flpGV_c2asnM4tcJd0nMjvxSCH-60Dj8H95vyWq41KB7mN5as2ugTD8P85w7mxSTbBAdILawFCqO2QxRASivKK6-sP3rh11s1284SMmIhQJ6_piHfw6Bv6n0DsrIgGX7Hd_FHKWORLNY7nhSzyjg6Pglfk52atoW5QcPq93Tz4GV1968DfYLu7r8s49yk29zpxeJSsGsXX1e8aVNBl0wTPTHBxM91vUQsu6jugR6YH-OeLpntpwbMc7Vf-ckXzTYwRx1v9ZgJz7gZm91nPZ9m3AeHX3g9ge20PwflqctJtpyWR_Wz9xA0-6Yee2_BdmnHCwjKb-setKInXQJnqTWLm--BoeatuuJm80rQgYz0TiQKFxVD1iXqlQl5ObPGNqnowEGjOHY-DXp2C8O0VW3k7OjRFZtK4FP4EnvIyupbFzoftH5w',
        }
    }
});
window.moment = require('moment');