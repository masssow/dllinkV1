// import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss';

import 'bootstrap';
import './js/khatmAnimation.js';
import './js/sessionFormToggle.js';


// import $ from 'jquery';

// window.$ = window.jQuery = $;
console.log('Bootstrap and jQuery have been loaded successfully!');
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
