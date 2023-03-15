"use strit";
console.log('Pincode Credits Loaded!');
let selector = {
    cartForm: `[action="/cart"]`
}

const params = new Proxy(new URLSearchParams(window.location.search), {
    get: (searchParams, prop) => searchParams.get(prop),
});


const userid = (params.userid) || 'null';
const utm_source = (params.utm_source) || 'null';
const referral = (params.referral) || 'null';
if (utm_source.toUpperCase() === 'PINCODE_CREDITS') {
    sessionStorage.setItem("pindoceCreditsUserId", userid+':'+referral);
    postData("https://5fb9-106-202-154-202.in.ngrok.io/pincode-credit-private-app/API/VisitorsConfimation.php", { 'userid': userid, 'referral': referral }).then((data) => {
        console.log(data);
    });
}

let pindoceCreditsUserId = sessionStorage.getItem("pindoceCreditsUserId") || null;
if (pindoceCreditsUserId != null) {
    let interval = setInterval(function () {
        if (document.querySelectorAll(selector.cartForm).length) {
            document.querySelectorAll(selector.cartForm).forEach(cartForm => {
                (cartForm.querySelector('[sgpc_user]')) && cartForm.querySelector('[sgpc_user]').remove();
                let input = document.createElement("INPUT");
                input.setAttribute("type", "hidden");
                input.setAttribute("name", "attributes[pindoceCreditsUserId]");
                input.setAttribute("value", pindoceCreditsUserId);
                input.setAttribute("sgpc_user", "");
                cartForm.appendChild(input);
                console.log('Input Added');
            });
            clearInterval(interval);
        }
    }, 100);
}

async function postData(url = "", data = {}) {
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
}
