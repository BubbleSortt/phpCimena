const isTable = document.querySelector('main').dataset.table;

if (isTable) {
  const ALL_KEYBOARD = document.querySelectorAll('.keyboard__td');
  const form = document.querySelector('.calculator__form');

  ALL_KEYBOARD.forEach(key => {
    key.addEventListener('click', touchKeyboard)
  })



  form.addEventListener('submit', evt => {
    const inputField = document.querySelector('#expression');
    evt.preventDefault()

    console.log(typeof inputField.value);
    if (!inputField.value) {
      console.log('hool')
    }
  })

  function touchKeyboard(evt) {
    const touchedKey = evt.target.dataset.keyboard;
    const inputField = document.querySelector('#expression');
    const lastValue = inputField.value.slice(-1);
    let prevNumber;
    inputField.focus()
    if (touchedKey === 'delete') {
      inputField.value = inputField.value.slice(0, -1)
      return;
    }

    switch (lastValue) {
      case '×':
        prevNumber = false;
        break;
      case '−':
        prevNumber = false;
        break;
      case '+':
        prevNumber = false;
        break;
      case '÷':
        prevNumber = false;
        break;
      case ',':
        prevNumber = false;
        break;
      default:
        prevNumber = true;
        break;
    }
    if (checkDoubleOperation(prevNumber, touchedKey)) {
      inputField.value +=  touchedKey;
    }
  }

  function checkDoubleOperation(prevNumber, touchedKey) {
    if (prevNumber === false) {
      if ((touchedKey === '÷') || (touchedKey === '+') || (touchedKey === '−') || (touchedKey === '×') || (touchedKey === ',')) {
        return false;
      }
    }
    return true;
  }
}