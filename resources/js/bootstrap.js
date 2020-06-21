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
    host: 'https://kliphome.com:6001',
    auth:{
        headers:{
            Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImE3N2E0OTQ1OGEzNWY2OTVlMDVjYjEwMWRhMDllYzMwMWQ5OGE5M2U3YmM0MzY5NGJhMjU1ZTEzZjI2ZDVjOTE1ZDBkODI1ZWE4Yzc2NTlhIn0.eyJhdWQiOiIyIiwianRpIjoiYTc3YTQ5NDU4YTM1ZjY5NWUwNWNiMTAxZGEwOWVjMzAxZDk4YTkzZTdiYzQzNjk0YmEyNTVlMTNmMjZkNWM5MTVkMGQ4MjVlYThjNzY1OWEiLCJpYXQiOjE1ODIwNTc1NzYsIm5iZiI6MTU4MjA1NzU3NiwiZXhwIjoxNTg3MjQxNTc2LCJzdWIiOiIxOSIsInNjb3BlcyI6W119.Uy9eUTbbat5xRso4jBGiO2-8HTRu3FLoy826UqquXFJoIVkrIwDp37vSbaEFBVqDhr8fByHba-4AxWhPSqF-rSLWcYdN5c3N-uQEHF73Ar43DPcXPyJbjY7ARNOjRNJz9qSsxNcRwZSgiSqFTF0mQuhul0WHB-ECuYVgzWmbfSysf88KZe6ylYqVOVC_4vR0OsX1xldAUDf9YLm3z2ZshFEsF5K-vyJxIfXG7gfFjzGvX6TFoCHYCbgbcZhC6iRGunfty4z-avt9U7cuk9CEaPTdU9NhnUB3p7R8RP8MFVXRe9PWE0s_RUZMwMevgjOfOIisQvpMXqXHrcOb-0hzTgQyaPBzCYn2VbeVjovwnuAHnw_hRFiuqC203xaJN2aKV6kJnX0J2NNztRP7M1hdDVbGbtXuzoH5PTit5Erg75I7poCEVsBj6rVK5tnpoFFYkeuNaYUOplwBVz9vo3F0LkOOD5DoAIwNviFY5HfnCnSgwofuCnBo7EAfBX6WAZcpDPoVSNHSgMmYBHWDB5YQmJwLICuYxve-lXQE1yam6dnZVeOywcZvGqfB7dRgh2buDYHKUJSNh3IpBLb0KW0uVijtnhqlXh9-QRSKbKFKt95PXWV4SY2wSeNSSjttbXeq1aLsca10dWCOTa5gfkOX8Rk78yyWraHNE8vG07MoZL0',
        }
    }
});
window.moment = require('moment');
