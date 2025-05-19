document.addEventListener("DOMContentLoaded", () => {
    let index = 0;
    const slides = document.getElementById('slides');
    const totalSlides = slides.children.length;

    function showSlide(i) {
    index = (i + totalSlides) % totalSlides;
    slides.style.transform = `translateX(-${index * 100}%)`;
    }

    function nextSlide() {
    showSlide(index + 1);
    }

    function prevSlide() {
    showSlide(index - 1);
    }

  // Mostrar la primera imagen
    showSlide(index);

  // Cambio automático cada 5 segundos
    setInterval(nextSlide, 5000);

  // Hacer que las funciones estén accesibles globalmente
    window.nextSlide = nextSlide;
    window.prevSlide = prevSlide;
});