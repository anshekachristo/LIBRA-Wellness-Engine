// 🎵 Play music based on selected mood
function playMusic(mood) {
  const audio = document.getElementById("audioPlayer");
  const source = document.getElementById("audioSource");

  // Mood-to-file map
  const moodMap = {
    relax: '../assets/media/music/relax.mp3',
    focus: '../assets/media/music/focus.mp3',
    nature: '../assets/media/music/nature.mp3',
    sleep: '../assets/media/music/sleep.mp3'
  };

  if (moodMap[mood]) {
    source.src = moodMap[mood];
    audio.load();
    audio.style.display = 'block';
    audio.play();
  } else {
    stopMusic(); // If unknown mood, stop/hide
  }
}

// ✋ Stop and hide music player manually
function stopMusic() {
  const audio = document.getElementById("audioPlayer");
  audio.pause();
  audio.style.display = 'none';
}

// 🔍 Filter cards by search input (all tabs)
function filterSuggestions() {
  const input = document.getElementById('searchInput').value.toLowerCase();
  const cards = document.querySelectorAll('.cards div');

  cards.forEach(card => {
    const text = card.innerText.toLowerCase();
    card.style.display = text.includes(input) ? 'block' : 'none';
  });
}
