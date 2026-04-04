document.addEventListener('DOMContentLoaded', () => {
  const typeField = document.querySelector('.js-session-type');
  const duaBlock = document.getElementById('dua-fields-block');
  const khatmBlock = document.getElementById('khatm-info-block');

  if (!typeField || !duaBlock || !khatmBlock) {
    return;
  }

  const toggleBlocks = () => {
    const selectedType = typeField.value;

    if (selectedType === 'dua') {
      duaBlock.style.display = 'block';
      khatmBlock.style.display = 'none';
    } else if (selectedType === 'khatm') {
      duaBlock.style.display = 'none';
      khatmBlock.style.display = 'block';
    } else {
      duaBlock.style.display = 'none';
      khatmBlock.style.display = 'none';
    }
  };

  toggleBlocks();
  typeField.addEventListener('change', toggleBlocks);
});