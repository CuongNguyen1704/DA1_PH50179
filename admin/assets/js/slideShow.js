let slideIndex = 1;
showSlides(slideIndex);
function plusSlides(n) {
    showSlides(slideIndex += n);
}
function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides");

    if (n > slides.length) {
        slideIndex = 1;
    }
    if (n < 1) {
        slideIndex = slides.length;
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    slides[slideIndex - 1].style.display = "block";
}


let autoSlide = setInterval(function() {
    plusSlides(1);
}, 2000);

function plusSlides(n) {
    clearInterval(autoSlide);  
    showSlides(slideIndex += n);
    autoSlide = setInterval(function() {
        plusSlides(1);
    }, 2000);
}
