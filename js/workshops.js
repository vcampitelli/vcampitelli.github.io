function unpack(input) {
    const len = input.length;
    let str = '';
    for (let i = 0; i < len; i += 2) {
        const v = parseInt(input.substr(i, 2), 16);
        if (v) {
            str += String.fromCharCode(v);
        }
    }
    return str;
}

document.getElementById('footer-1').innerHTML = unpack('283131292039333437372d32303135');
document.getElementById('footer-1-url').href = unpack('68747470733a2f2f6170692e77686174736170702e636f6d2f73656e643f70686f6e653d35353131393334373732303135');
document.getElementById('footer-2').innerHTML = unpack('65754076696e696369757363616d706974656c6c692e636f6d');
document.getElementById('footer-2-url').href = unpack('6d61696c746f3a65754076696e696369757363616d706974656c6c692e636f6d');
const $floating = document.getElementById('floating-btn');
$floating.href = unpack('68747470733a2f2f77612e6d652f35353131393334373732303135');
$floating.style.display = 'block';

const openCalendly = function(e) {
    e.preventDefault();
    e.stopPropagation();
    Calendly.initPopupWidget({url: 'https://calendly.com/viniciuscampitelli/demo?hide_landing_page_details=1'});
    return false;
};
document.querySelectorAll('.schedule-demo').forEach(n => n.addEventListener('click', openCalendly));
