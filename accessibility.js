
function toggleColorBlindMode() {
  document.body.classList.toggle("colorblind-mode");

  const button = document.querySelector('.btn[onclick="toggleColorBlindMode()"]');
  const isColorblindMode = document.body.classList.contains("colorblind-mode");

  if (button) {
    button.setAttribute('aria-pressed', isColorblindMode);
  }

  localStorage.setItem('colorblindMode', isColorblindMode);
  announceColorblindMode(isColorblindMode);
}

function announceColorblindMode(isEnabled) {
  const message = isEnabled ? 'Modo daltonismo activado' : 'Modo daltonismo desactivado';
  const announcement = document.createElement('div');
  announcement.setAttribute('role', 'alert');
  announcement.setAttribute('aria-live', 'polite');
  announcement.className = 'sr-only';
  announcement.textContent = message;
  document.body.appendChild(announcement);

  setTimeout(() => announcement.remove(), 1000);
}

document.addEventListener('DOMContentLoaded', () => {
  const savedMode = localStorage.getItem('colorblindMode');
  if (savedMode === 'true') {
    document.body.classList.add('colorblind-mode');
  }

  const colorblindButton = document.querySelector('.btn[onclick="toggleColorBlindMode()"]');
  if (colorblindButton) {
    colorblindButton.setAttribute('aria-pressed', savedMode === 'true');
  }
});