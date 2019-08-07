$(function() {
    const arr = $('.ft_html img');
    if("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in  window.IntersectionObserverEntry.prototype){
        const imageObserver = new IntersectionObserver((entries, imgObserver) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const lazyImage = entry.target;
                    lazyImage.removeAttribute("srcset");
                    imgObserver.unobserve(lazyImage);
                }
            });
        });
        arr.map((index, element) => {
            imageObserver.observe(element);
        });
    }else{
        arr.removeAttr('srcset');
    }
});