const images = [
  { src: "../assets/img/imgHa.webp", text: "HA" },
  { src: "../assets/img/imgNa.webp", text: "NA" },
  { src: "../assets/img/imgCa.webp", text: "CA" },
  { src: "../assets/img/imgRa.webp", text: "RA" },
  { src: "../assets/img/imgKa.webp", text: "KA" },
  { src: "../assets/img/imgDa.webp", text: "DA" },
  { src: "../assets/img/imgTa.webp", text: "TA" },
  { src: "../assets/img/imgSa.webp", text: "SA" },
  { src: "../assets/img/imgWa.webp", text: "WA" },
  { src: "../assets/img/imgLa.webp", text: "LA" },
  { src: "../assets/img/imgMa.webp", text: "MA" },
  { src: "../assets/img/imgGa.webp", text: "GA" },
  { src: "../assets/img/imgBa.webp", text: "BA" },
  { src: "../assets/img/imgNga.webp", text: "NGA" },
  { src: "../assets/img/imgPa.webp", text: "PA" },
  { src: "../assets/img/imgJa.webp", text: "JA" },
  { src: "../assets/img/imgYa.webp", text: "YA" },
  { src: "../assets/img/imgNya.webp", text: "NYA" }
]; 

let currentIndex = 0;

const displayImage = document.getElementById("displayImage");
const displayText = document.getElementById("displayText");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");
const loader = document.getElementById("loader");

// Preload all images
const preloadImages = () => {
  images.forEach(image => {
    const img = new Image();
    img.src = image.src;
  });
};

// Update content with loading effect
function updateContent() {
  loader.style.display = "block";

  const tempImg = new Image();
  tempImg.onload = () => {
    displayImage.src = tempImg.src;
    displayText.textContent = images[currentIndex].text;
    loader.style.display = "none";
  };
  tempImg.src = images[currentIndex].src;
}

// Navigation buttons
nextBtn.addEventListener("click", () => {
  currentIndex = (currentIndex + 1) % images.length;
  updateContent();
});

prevBtn.addEventListener("click", () => {
  currentIndex = (currentIndex - 1 + images.length) % images.length;
  updateContent();
});

// Initialize
preloadImages();
updateContent();

// CANVAS FUNCTIONALITY
const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");
const colorPicker = document.getElementById("colorPicker");
const sizePicker = document.getElementById("sizePicker");

let drawing = false;
let isErasing = false;

const penBtn = document.getElementById("penBtn");
const eraserBtn = document.getElementById("eraserBtn");

penBtn.addEventListener("click", () => {
  isErasing = false;
  penBtn.classList.add("active");
  eraserBtn.classList.remove("active");
});

eraserBtn.addEventListener("click", () => {
  isErasing = true;
  eraserBtn.classList.add("active");
  penBtn.classList.remove("active");
});

// Resize canvas to match displayed size
function resizeCanvasToDisplaySize(canvas) {
  const rect = canvas.getBoundingClientRect();
  const scale = window.devicePixelRatio || 1;

  canvas.width = rect.width * scale;
  canvas.height = rect.height * scale;
  canvas.style.width = rect.width + "px";
  canvas.style.height = rect.height + "px";
  ctx.setTransform(1, 0, 0, 1, 0, 0); // reset transform
  ctx.scale(scale, scale);
}

// Draw function
function draw(event) {
  if (!drawing) return;
  ctx.lineWidth = sizePicker.value;
  ctx.lineCap = "round";
  ctx.strokeStyle = isErasing ? "#eeecef" : colorPicker.value;

  ctx.lineTo(event.offsetX, event.offsetY);
  ctx.stroke();
  ctx.beginPath();
  ctx.moveTo(event.offsetX, event.offsetY);
}

// Mouse Events
canvas.addEventListener("mousedown", () => (drawing = true));
canvas.addEventListener("mouseup", () => {
  drawing = false;
  ctx.beginPath();
});
canvas.addEventListener("mouseleave", () => {
  drawing = false;
  ctx.beginPath();
});
canvas.addEventListener("mousemove", draw);

// Touch Events
canvas.addEventListener("touchstart", (event) => {
  drawing = true;
  event.preventDefault();
});
canvas.addEventListener("touchend", () => {
  drawing = false;
  ctx.beginPath();
});
canvas.addEventListener("touchmove", (event) => {
  if (!drawing) return;
  event.preventDefault();
  const touch = event.touches[0];
  const rect = canvas.getBoundingClientRect();
  const offsetX = touch.clientX - rect.left;
  const offsetY = touch.clientY - rect.top;

  ctx.lineWidth = sizePicker.value;
  ctx.lineCap = "round";
  ctx.strokeStyle = isErasing ? "#eeecef" : colorPicker.value;

  ctx.lineTo(offsetX, offsetY);
  ctx.stroke();
  ctx.beginPath();
  ctx.moveTo(offsetX, offsetY);
});

// Clear canvas
function clearCanvas() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.beginPath();
}

// Setup on load
window.addEventListener("load", () => {
  penBtn.classList.add("active");
  eraserBtn.classList.remove("active");
  isErasing = false;
  resizeCanvasToDisplaySize(canvas);
});

// Resize handler
window.addEventListener("resize", () => {
  resizeCanvasToDisplaySize(canvas);
});
