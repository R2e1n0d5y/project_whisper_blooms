document.getElementById('keluarQuizBtn').addEventListener('click', function(e) {
e.preventDefault();

Swal.fire({
    title: 'Yakin ingin keluar Quiz?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#c5922d',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, Keluar',
    cancelButtonText: 'Batal'
}).then((result) => {
    if (result.isConfirmed) {
    window.location.href = '../html/menuQuiz.php';
    }
});
});

function shuffleArray(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
}

shuffleArray(questions);
questions.forEach((q, index) => {
  q.question = `${index + 1}. ${q.question}`;
});

let currentQuestion = 0;
let score = 0;
let selectedAnswer = null;

document.addEventListener("DOMContentLoaded", () => {
  showQuestion();
  document.getElementById("next-button").addEventListener("click", nextQuestion);
});

function showQuestion() {
  const questionElement = document.getElementById("question");
  const optionsContainer = document.getElementById("options");
  const questionMedia = document.getElementById("question-media");
  const nextButton = document.getElementById("next-button");

  questionElement.textContent = questions[currentQuestion].question;
  optionsContainer.innerHTML = "";
  questionMedia.innerHTML = "";

  const media = questions[currentQuestion].media;
  if (media) {
    let mediaElement;
    if (media.endsWith(".webp")) {
      mediaElement = document.createElement("img");
      mediaElement.src = "../assets/img/" + media;
      mediaElement.alt = "Question Media";
      mediaElement.style.cssText = "width: 90px; height: auto; background-color: #EEECEF; border-radius: 5px;";
    } else if (media.endsWith(".mp3")) {
      mediaElement = document.createElement("audio");
      mediaElement.src = "../assets/audio/" + media;
      mediaElement.controls = true;
    }
    questionMedia.appendChild(mediaElement);
  }

  questions[currentQuestion].options.forEach((option, index) => {
    let button = document.createElement("button");
    button.classList.add("option-button");
    button.style.cssText = "display: flex; align-items: center; gap: 10px; padding: 10px;";

    let label = document.createElement("span");
    label.textContent = String.fromCharCode(65 + index) + ". ";
    button.appendChild(label);

    let selectedValue;

    if (typeof option === "string") {
      button.appendChild(document.createTextNode(option));
      selectedValue = option;
    } else if (option.type === "image") {
      let img = document.createElement("img");
      img.src = "../assets/img/" + option.src;
      img.alt = "Option Image";
      img.style.cssText = "width: 100px; height: auto;";
      button.appendChild(img);
      selectedValue = option.src;
    } else if (option.type === "audio") {
      let audio = document.createElement("audio");
      audio.src = "../assets/audio/" + option.src;
      audio.controls = true;
      audio.style.cssText = "width: 150px;";
      button.appendChild(audio);
      selectedValue = option.src;
    }

    button.addEventListener("click", () => selectAnswer(button, selectedValue));
    optionsContainer.appendChild(button);
  });

  nextButton.disabled = true;
}

function selectAnswer(button, selectedOption) {
  const allButtons = document.querySelectorAll(".option-button");
  allButtons.forEach(btn => btn.classList.remove("selected", "correct", "wrong"));

  selectedAnswer = selectedOption;
  const correctAnswer = questions[currentQuestion].answer;

  allButtons.forEach(btn => {
    let btnValue = btn.querySelector("img")?.src.split("/").pop() ||
                   btn.querySelector("audio")?.src.split("/").pop() ||
                   btn.textContent.trim().substring(3);
    if (btnValue === correctAnswer) {
      btn.classList.add("correct");
    }
    if (btn === button) {
      if (btnValue === correctAnswer) {
        btn.classList.add("correct");
      } else {
        btn.classList.add("wrong");
      }
    }
  });

  document.getElementById("next-button").disabled = false;
}

function nextQuestion() {
  if (selectedAnswer === questions[currentQuestion].answer) {
    score += parseInt(questions[currentQuestion].nilai);
  }

  if (currentQuestion + 1 < questions.length) {
    currentQuestion++;
    selectedAnswer = null;
    showQuestion();
  } else {
    showResult();
  }
}

function showResult() {
  document.getElementById("quiz-card").style.display = "none";
  const resultElement = document.getElementById("result");
  resultElement.textContent = `Hasil Akhir: ${score} / 100`;
  resultElement.style.display = "block";

  const formData = new FormData();
  formData.append('score', score); // pastikan score sudah dihitung

  fetch('../config/saveHistory.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(data => console.log('Respon server:', data))
  .catch(error => console.error('Error:', error));

}
