"use strict";
var currentPage = '';
if (document.body.contains(document.getElementById('login_form'))) {
    currentPage = 'login_form';
}
if (document.body.contains(document.getElementById('docForm'))) {
    currentPage = 'docForm';
}
