/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./createBooking');
require('chosen-js');

$(document).ready(function() {
    $(".chosen-select").chosen();
});
