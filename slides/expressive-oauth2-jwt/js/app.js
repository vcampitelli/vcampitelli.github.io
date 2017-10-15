Reveal.initialize({
    dependencies: [
        { src: 'js/prism.js', async: true }
    ],
    slideNumber: true,
    transition: 'convex'
});
Reveal.addEventListener('fragmentshown', function(event) {
    if (event.fragment.classList.contains('fragment-jwt-code')) {
        event.fragment.style.marginLeft = -event.fragment.clientWidth / 2 + 'px';
        event.fragment.style.marginTop = -event.fragment.clientHeight / 2 + 'px';
    }
});
