"use strit";
console.log("Pincode Credits Loaded!");
let selector = {
  cartForm: `[action="/cart"]`,
};

const params = new Proxy(new URLSearchParams(window.location.search), {
  get: (searchParams, prop) => searchParams.get(prop),
});

const userid = params.userid || getCookies().__pc_userid || null;
const utm_source = params.utm_source || getCookies().__pc_utm_source || null;
const referral = params.referral || getCookies().__pc_referral || null;
const shop = window.Shopify.shop || Shopify.shop;
let pincodecredits = {};

if (userid) {
  pincodecredits.userid = userid;
  createCookie("__pc_userid", userid, 30);
}

if (utm_source) { 
  pincodecredits.utm_source = utm_source;
  createCookie("__pc_utm_source", utm_source, 30);
}

if (referral) {
  pincodecredits.referral = referral;
  createCookie("__pc_referral", referral, 30);
}

console.log("Pincode Credits Object:", pincodecredits);

if (utm_source?.toUpperCase() === "PINCODE_CREDITS") {
  sessionStorage.setItem("pindoceCreditsUserId", userid);
  pincodecredit_postData(
    "https://pincodecredits.in/pincodecredit-private/API/VisitorsConfimation.php",
    { userid: userid, referral: referral, shop: shop }
  ).then((data) => {
    console.log(data);
  });
}

let pindoceCreditsUserId =
  sessionStorage.getItem("pindoceCreditsUserId") || null;
if (pindoceCreditsUserId != null) {
  let interval = setInterval(function () {
    if (document.querySelectorAll(selector.cartForm).length) {
      document.querySelectorAll(selector.cartForm).forEach((cartForm) => {
        cartForm.querySelector("[sgpc_user]") &&
          cartForm.querySelector("[sgpc_user]").remove();
        let input = document.createElement("INPUT");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", "attributes[pindoceCreditsUserId]");
        input.setAttribute("value", pindoceCreditsUserId);
        input.setAttribute("sgpc_user", "");
        cartForm.appendChild(input);
        console.log("Input Added");
      });
      clearInterval(interval);
    }
  }, 100);
  let intvl_2 = setInterval(function () {
    pincodecredit_postData("/cart/update.js", {
      attributes: { pindoceCreditsUserId: pindoceCreditsUserId },
    }).then((data) => {
      console.log(data, "<<<pincodecredits data");
    });
  }, 1000);
  setTimeout(function () {
    clearInterval(intvl_2);
  }, 15000);
}

async function pincodecredit_postData(url = "", data = {}) {
  try {
    const response = await fetch(url, {
      method: "POST",
      mode: "cors",
      cache: "no-cache",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
      },
      redirect: "follow",
      referrerPolicy: "no-referrer",
      body: JSON.stringify(data),
    });
    return response.json();
  } catch (err) {
    return { Errors: err };
  }
}

function createCookie(name, value, days) {
  let expires = "";
  if (days) {
    const date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function getCookies() {
  const cookies = document.cookie.split(";").map((cookie) => cookie.trim());
  const cookieObject = {};

  cookies.forEach((cookie) => {
    const [name, value] = cookie.split("=");
    cookieObject[name] = decodeURIComponent(value);
  });

  return cookieObject;
}
