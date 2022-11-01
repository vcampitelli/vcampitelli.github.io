const $links = document.getElementById('contact').children;
$links[0].querySelector('span > span').innerHTML = '&#040;&#049;&#049;&#041;&#032;&#057;&#057;&#049;&#054;&#054;&#045;&#057;&#049;&#057;&#048;';
$links[1].querySelector('span > span').innerHTML = '&#101;&#117;&#064;&#118;&#105;&#110;&#105;&#099;&#105;&#117;&#115;&#099;&#097;&#109;&#112;&#105;&#116;&#101;&#108;&#108;&#105;&#046;&#099;&#111;&#109;';

const $content = document.getElementById('content');
document.querySelector('#header-intro > a').onclick = function (e) {
    e.preventDefault();
    e.stopPropagation();
    $content.scrollIntoView({behavior: 'smooth'});
};
