"use strict";

(function () {
  // Input mask and date picker initializations
  var phoneMasks = document.querySelectorAll(".phone-mask"),
    creditCardMask = document.querySelector(".credit-card-mask"),
    expiryDateMask = document.querySelector(".expiry-date-mask"),
    cvvCodeMask = document.querySelector(".cvv-code-mask"),
    dobPickers = document.querySelectorAll(".dob-picker"),
    paymentOptions = document.querySelectorAll(".form-check-input-payment");

  // Initialize Cleave.js for phone masks
  phoneMasks.forEach(function (element) {
    new Cleave(element, { phone: true, phoneRegionCode: "US" });
  });

  // Initialize Cleave.js for credit card mask
  if (creditCardMask) {
    new Cleave(creditCardMask, {
      creditCard: true,
      onCreditCardTypeChanged: function (type) {
        document.querySelector(".card-type").innerHTML =
          type && type !== "unknown"
            ? '<img src="' + assetsPath + 'img/icons/payments/' + type + '-cc.png" height="28"/>'
            : "";
      },
    });
  }

  // Initialize Cleave.js for expiry date mask
  if (expiryDateMask) {
    new Cleave(expiryDateMask, { date: true, delimiter: "/", datePattern: ["m", "y"] });
  }

  // Initialize Cleave.js for CVV code mask
  if (cvvCodeMask) {
    new Cleave(cvvCodeMask, { numeral: true, numeralPositiveOnly: true });
  }

  // Initialize flatpickr for date of birth pickers
  dobPickers.forEach(function (element) {
    element.flatpickr({ monthSelectorType: "static" });
  });

  // Add event listeners for payment options
  paymentOptions.forEach(function (element) {
    element.addEventListener("change", function (event) {
      var creditCardForm = document.querySelector("#form-credit-card");
      if (event.target.value === "credit-card") {
        creditCardForm.classList.remove("d-none");
      } else {
        creditCardForm.classList.add("d-none");
      }
    });
  });
})();

$(function () {
  // Sticky element initialization
  var stickyElement = $(".sticky-element");
  var topSpacing = window.Helpers.isNavbarFixed() ? $(".layout-navbar").height() - 3 : 0;

  if (stickyElement.length) {
    stickyElement.sticky({ topSpacing: topSpacing, zIndex: 9 });
  }

  // Select2 initialization
  var select2Elements = $(".select2");
  select2Elements.each(function () {
    var element = $(this);
    element.wrap('<div class="position-relative"></div>').select2({
      placeholder: "Select value",
      dropdownParent: element.parent(),
    });
  });
});
