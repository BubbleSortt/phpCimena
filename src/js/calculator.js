const isTable = document.querySelector('main').dataset.table;

if (isTable) {
  const ALL_KEYBOARD = document.querySelectorAll('.keyboard__td');
  const form = document.querySelector('.calculator__form');
  const input = document.querySelector('#expression');

  ALL_KEYBOARD.forEach(key => {
    key.addEventListener('click', touchKeyboard)
  })

  input.addEventListener('input', evt => {
    input.value = input.value.replace(/[A-Za-zА-Яа-яЁё]/, '')
  })



  form.addEventListener('submit', evt => {
    const inputField = document.querySelector('#expression');

    console.log(typeof inputField.value);
    if (!inputField.value) {
      evt.preventDefault()
      console.log('hool')
    }
  })

  function touchKeyboard(evt) {
    const touchedKey = evt.target.dataset.keyboard;
    const inputField = document.querySelector('#expression');
    const lastValue = inputField.value.slice(-1);
    console.log(lastValue);
    let prevNumber = false;
    inputField.focus()
    if (touchedKey === 'delete-all') {
      inputField.value = '';
      return;
    }
    if (touchedKey === 'delete') {
      inputField.value = inputField.value.slice(0, -1);
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
      case '':
        prevNumber = false;
        break;
      default:
        prevNumber = true;
        break;
    }
    if (checkDoubleOperation(prevNumber, touchedKey, lastValue)) {
      inputField.value +=  touchedKey;
    }
  }

  function checkDoubleOperation(prevNumber, touchedKey, lastValue) {
    if (prevNumber === false) {
      if ((lastValue === '') && (touchedKey === ')' || touchedKey === '(')) {
        return true;
      }
      if (
        (touchedKey === '÷') ||
        (touchedKey === '+') ||
        (touchedKey === '−') ||
        (touchedKey === '×') ||
        (touchedKey === ',') ||
        (touchedKey === ')') ||
        (touchedKey === '(')
      ) {
        return false;
      }
    }
    return true;
  }
}