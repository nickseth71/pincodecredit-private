(function () {
  'use strict';

  var _gatForm = document.querySelector('#getAccessToken'),
    invalid_shop = document.querySelector('#invalid_shop'),
    _togglePass = document.querySelector('.toggle-pass');
  invalid_shop && invalid_shop.classList.remove("active");
  if (_gatForm) {
    _gatForm.addEventListener('submit', function (event) {
      let _shopURL = _gatForm.querySelector('[name="shop_url"]').value.toLowerCase(),
        _btn = _gatForm.querySelector('[type="submit"]');
      _btn.setAttribute("disabled", "");
      _btn.querySelector('.spinner-grow').removeAttribute("hidden");
      if (!_shopURL.includes('.myshopify.com')) {
        event.preventDefault();
        event.stopPropagation();
        invalid_shop && invalid_shop.classList.add("active");
        _btn.removeAttribute("disabled");
        _btn.querySelector('.spinner-grow').setAttribute("hidden", "");
      }
    }, false);
  }

  if(_togglePass) {
    _togglePass.addEventListener('click', function (event) {
      let passwordField = document.querySelector('#password')
      _togglePass.classList.toggle("active");
      if(_togglePass.classList.contains('active')) {
        passwordField.type = "text";
      } else {
        passwordField.type = "password";
      }
    }, false);
  }
})()