webpackJsonp(["app/js/settings/password/index"],{0:function(e,s){e.exports=jQuery},a14532ac6a04b0793db9:function(e,s,a){"use strict";var r=a("b334fd7e4c5a19234db2"),n=function(e){return e&&e.__esModule?e:{default:e}}(r);$("#settings-password-form").validate({currentDom:"#password-save-btn",ajax:!0,rules:{currentPassword:{required:!0},newPassword:{required:!0,minlength:5,maxlength:20,visible_character:!0},confirmPassword:{required:!0,equalTo:"#form_newPassword",visible_character:!0}},submitSuccess:function(e){(0,n.default)("success",Translator.trans(e.message)),$(".modal").modal("hide"),window.location.reload()},submitError:function(e){(0,n.default)("danger",Translator.trans(e.responseJSON.message))}})}},["a14532ac6a04b0793db9"]);