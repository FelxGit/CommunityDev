window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

 try {
  window.Popper = require('popper.js').default;
  window.$ = window.jQuery = require('jquery');

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  require('bootstrap');
} catch (e) {}

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: process.env.MIX_PUSHER_APP_KEY,
  cluster: process.env.MIX_PUSHER_APP_CLUSTER,
  forceTLS: false,
  wsPort: 6001,
  wssPort: 6001,
  enabledTransports: ['ws', 'wss'],
  wsHost: window.location.hostname,
//   csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//   auth: {
//       headers: {
//           Authorization: 'Bearer ' + localStorage.getItem('chronoknowledge.jwt')
//       },
//   },
});

/**
 * This loads the Facebook SDK/components such as facebook login
 *
 * However, on this app we are using the Socialite package
 */

// window.fbAsyncInit = function() {
//   FB.init({
//     appId      : '987334829344378',
//     cookie     : true,
//     xfbml      : true,
//     version    : '{api-version}'
//   });

//   FB.AppEvents.logPageView();

// };

// (function(d, s, id){
//     var js, fjs = d.getElementsByTagName(s)[0];
//     if (d.getElementById(id)) {return;}
//     js = d.createElement(s); js.id = id;
//     js.src = "https://connect.facebook.net/en_US/sdk.js";
//     fjs.parentNode.insertBefore(js, fjs);
//   }(document, 'script', 'facebook-jssdk'));