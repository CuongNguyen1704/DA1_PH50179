let slideIndex = 1;
showSlide(slideIndex);

// Function to handle next/previous controls
function plusSlide(n) {
    clearInterval(autoSlide);  // Stop auto slide when user interacts
    showSlide(slideIndex += n);

    // Restart auto slide after interaction
    autoSlide = setInterval(function() {
        plusSlide(1);
    }, 2000);
}

// Function to display the slides
function showSlide(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides-category");

    if (n > slides.length) {
        slideIndex = 1;
    }
    if (n < 1) {
        slideIndex = slides.length;
    }

    // Hide all slides
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    // Show the current slide
    slides[slideIndex - 1].style.display = "block";
}

// Auto slide every 2 seconds
let autoSlide = setInterval(function() {
    plusSlide(1);
}, 2000);
