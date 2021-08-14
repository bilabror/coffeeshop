const nav = document.querySelector(".navbar");
window.addEventListener("scroll", function () {
  nav.classList.toggle("navLight", window.scrollY > 50);
});

// const navBottom = document.querySelector(".navBottom");
// const navBottomToggle = document.querySelector(".navBottomToggle");

// navBottomToggle.addEventListener("click", () => {
//   navBottom.classList.toggle("show");
// });

const showInfo = () => {
  const info = document.querySelector("p.info");
  info.classList.toggle("show");
};

// Filter
const list = document.querySelectorAll(".list");
const itemBox = document.querySelectorAll(".textBox");

for (let i = 0; i < list.length; i++) {
  list[i].addEventListener("click", () => {
    for (let j = 0; j < list.length; j++) {
      list[j].classList.remove("active");
    }
    list[i].classList.add("active");

    let dataFilter = list[i].getAttribute("data-filter");

    for (let k = 0; k < itemBox.length; k++) {
      itemBox[k].classList.remove("active");
      itemBox[k].classList.add("hide");

      if (
        itemBox[k].getAttribute("data-item") == dataFilter ||
        dataFilter == "all"
      ) {
        itemBox[k].classList.remove("hide");
        itemBox[k].classList.add("active");
      }
    }
  });
}

// swapDetailGambarProduk
const swapImgProduk = (img) => {
  document.getElementById("swapImg").src = img;
};

document.addEventListener("DOMContentLoaded", function () {
  new Splide(".splide", {
    cover: true,
    rewind: true,
    heightRatio: 0.35,
  }).mount();

  // window.addEventListener("load", function () {
  //   document
  //     .querySelector(".glider")
  //     .addEventListener("glider-slide-visible", function (event) {
  //       var glider = Glider(this);
  //       console.log("Slide Visible %s", event.detail.slide);
  //     });
  //   document
  //     .querySelector(".glider")
  //     .addEventListener("glider-slide-hidden", function (event) {
  //       console.log("Slide Hidden %s", event.detail.slide);
  //     });
  //   document
  //     .querySelector(".glider")
  //     .addEventListener("glider-refresh", function (event) {
  //       console.log("Refresh");
  //     });
  //   document
  //     .querySelector(".glider")
  //     .addEventListener("glider-loaded", function (event) {
  //       console.log("Loaded");
  //     });

  //   window._ = new Glider(document.querySelector(".glider"), {
  //     slidesToShow: 1, //'auto',
  //     slidesToScroll: 1,
  //     itemWidth: 150,
  //     draggable: true,
  //     scrollLock: false,
  //     dots: "#dots",
  //     rewind: true,
  //     arrows: {
  //       prev: ".glider-prev",
  //       next: ".glider-next",
  //     },
  //     responsive: [
  //       {
  //         breakpoint: 800,
  //         settings: {
  //           slidesToScroll: "auto",
  //           itemWidth: 300,
  //           slidesToShow: "auto",
  //           exactWidth: true,
  //         },
  //       },
  //       {
  //         breakpoint: 700,
  //         settings: {
  //           slidesToScroll: 4,
  //           slidesToShow: 4,
  //           dots: false,
  //           arrows: false,
  //         },
  //       },
  //       {
  //         breakpoint: 600,
  //         settings: {
  //           slidesToScroll: 3,
  //           slidesToShow: 3,
  //         },
  //       },
  //       {
  //         breakpoint: 500,
  //         settings: {
  //           slidesToScroll: 2,
  //           slidesToShow: 2,
  //           dots: false,
  //           arrows: false,
  //           scrollLock: true,
  //         },
  //       },
  //     ],
  //   });
  // });

  const next = document.querySelector(".next");
  const prev = document.querySelector(".prev");
  const slides = document.querySelectorAll(".slide");

  let index = 0;
  display(index);
  function display(index) {
    slides.forEach((slide) => {
      slide.style.display = "none";
    });
    slides[index].style.display = "flex";
  }

  function nextSlide() {
    index++;
    if (index > slides.length - 1) {
      index = 0;
    }
    display(index);
  }
  function prevSlide() {
    index--;
    if (index < 0) {
      index = slides.length - 1;
    }
    display(index);
  }

  next.addEventListener("click", nextSlide);
  prev.addEventListener("click", prevSlide);

  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});

// form validation
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  "use strict";

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll(".needs-validation");

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener(
      "submit",
      function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }

        form.classList.add("was-validated");
      },
      false
    );
  });
})();
