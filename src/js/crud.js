const isCrud = document.querySelector('main[crud]');

if (isCrud) {
  const buttonAddFilm = document.querySelector('.crud__add');
  const crudTableBody = document.querySelector('.crud__body');

  //https://cinemalo.000webhostapp.com/DataManager/DataManager.php?action=getAll
  //https://jsonplaceholder.typicode.com/users

  getLastId();


  fetch('https://cinemalo.000webhostapp.com/DataManager/DataManager.php?action=getAll', {
    method: 'GET',
    // mode: 'no-cors'
  }).then(res => {
    //console.log(res)
    res.json()
      .then(allFilms => {

        allFilms.forEach(film => {
          renderFilm(film)
        })
      })
      .catch(err => {
        console.log(err)
      })
  })

  buttonAddFilm.addEventListener('click', renderFilm)

  function renderFilm(film) {

    let idFilm;
    let isNew;
    if (!film.target) {
      idFilm = film.id
      isNew = false;
    } else {
      idFilm = film.target.dataset.filmId;
      isNew = true;
      let isExist = crudTableBody.querySelector(`div[film-id="${idFilm}"]`);
      if (isExist) {
        return;
      }
    }
    let newFilm = document.createElement('div');
    //console.log(idFilm)
    newFilm.setAttribute('film-id', `${idFilm}`)
    newFilm.classList.add('crud__item');
    if (!isNew) {
      newFilm.classList.add('cannot-edit');
    }
    newFilm.insertAdjacentHTML('afterbegin',
      `
      <input class="id"  type="text" disabled value="${idFilm}">
                    <textarea type="text" ${!isNew ? 'disabled' : ''}  class="title textarea" >${!isNew ? `${film.title}` : ''}</textarea>
                    <textarea name="description" ${!isNew ? 'disabled' : ''} class="textarea description" id="textarea" cols="30" rows="10">${!isNew ? `${film.description}` : ''}</textarea>
                    <input type="text" ${!isNew ? 'disabled' : ''} ${!isNew ? `value="${film.categorie_id}"` : ''}  class="categorie">
                    <div class="crud__file-wrap">
                        <input class="file-input" type="file" id="file-${idFilm}"  ${!isNew ? 'disabled' : ''}>
                        <label for="file-${idFilm}"> ${!isNew ? `${film.image}` : 'Выберите фото'}</label>
                    </div>

                    <div class="crud__actions">
<!--                        <button class="hidden" data-crud-action="remove">-</button>-->
                        <button class="hidden" data-crud-action="edit">P</button>
                        <button class="remove-button" data-crud-action="remove">X</button>
                        <button class="save-button" data-crud-action="save">S</button>
                    </div>
      
           `)
    crudTableBody.append(newFilm);

    let newRecordFilm = document.querySelector(`div[film-id="${idFilm}"]`);
    let newRecordFilmButtons = newRecordFilm.querySelectorAll('.crud__actions button');
    newRecordFilmButtons.forEach(button => {
      button.addEventListener('click', crudAction);
    })

    const inputFile = newRecordFilm.querySelector('.file-input');

    inputFile.addEventListener('change', evt => {
      if (evt.target.files[0]) {
        let fileName = evt.target.files[0].name;
        console.log(fileName);
        let label = newRecordFilm.querySelector('.crud__file-wrap label');
        label.innerHTML = fileName;
      }

    })

    if (!isNew) {
      inputFile.files.photo = film.image;
    }

    if (isNew) {
      newRecordFilm.querySelector('.title').focus()
    }

  }

  async function crudAction(evt) {
    const errorBlock = document.querySelector('.error span');
    errorBlock.innerHTML = '';
    const whichAction = evt.target.dataset.crudAction;
    //console.log(whichAction);

    switch (whichAction) {
      case 'save':
        actionSave(evt);
        break;
      case 'remove':
        actionRemove(evt);
        break;
    }

    function actionSave(evt) {
      const currentFilm = evt.toElement.offsetParent.offsetParent;
      if (currentFilm.classList.contains('cannot-edit')) {
        return;
      }
      //const errorBlock = document.querySelector('.error span');
      let formData = new FormData();
      const idCurrentFilm = currentFilm.querySelector('.id').value;
      const titleCurrentFilm = currentFilm.querySelector('.title');
      const descriptionCurrentFilm = currentFilm.querySelector('.description');
      const categorieCurrentFilm = currentFilm.querySelector('.categorie');
      let photoCurrentFilm = currentFilm.querySelector('.file-input');
      const todayDate = getTodayDate();

      // photoCurrentFilm.files.photo = 'kfkfkfjjgsl';

      console.log(titleCurrentFilm.value)

      if (titleCurrentFilm.value.trim() === '') {
        errorBlock.innerHTML = 'Заполните поле title'
        return;
      }
      if (descriptionCurrentFilm.value.trim() === '') {
        errorBlock.innerHTML = 'Заполните поле description'
        return;
      }
      if (categorieCurrentFilm.value.trim() === '') {
        errorBlock.innerHTML = 'Заполните поле categorie_id'
        return;
      }
      if ((!photoCurrentFilm.files[0]) && (!photoCurrentFilm.files.photo)) {
        errorBlock.innerHTML = 'Добавьте фотографию'
        return;
      }




      titleCurrentFilm.setAttribute('disabled', '');
      descriptionCurrentFilm.setAttribute('disabled', '');
      categorieCurrentFilm.setAttribute('disabled', '');
      photoCurrentFilm.setAttribute('disabled', '');

      if (photoCurrentFilm.files[0]) {
        photoCurrentFilm = photoCurrentFilm.files[0];
        console.log(photoCurrentFilm)
      } else {
        photoCurrentFilm = photoCurrentFilm.files.photo;
      }

      formData.append('id', idCurrentFilm);
      formData.append('title', titleCurrentFilm.value);
      formData.append('description', descriptionCurrentFilm.value);
      formData.append('category', categorieCurrentFilm.value);
      formData.append('date', todayDate);
      formData.append('image', photoCurrentFilm)

      currentFilm.classList.add('cannot-edit');
      getLastId();

      fetch('https://cinemalo.000webhostapp.com/DataManager/DataManager.php?action=add', {
        method: 'POST',
        body: formData
      })
        .then(res => {
          console.log(res)
        })
        .catch(err => {
          console.log(err)
        })

    }

    function actionRemove(evt) {
      const currentFilm = evt.toElement.offsetParent.offsetParent;
      if (currentFilm.classList.contains('cannot-edit')) {
        return;
      }
      currentFilm.remove();
    }
  }

  function getTodayDate() {
    let d = new Date();
    let day = d.getDate();
    let month = d.getMonth() + 1;
    let year = d.getFullYear();
    if (day < 10) {
      day = '0' + day;
    }
    if (month < 10) {
      month = '0' + month;
    }

    return day + '-' + month + '-' + year;
  }

  async function getLastId() {
    let getLast = await fetch('https://cinemalo.000webhostapp.com/DataManager/DataManager.php?action=getLast', {
      method: 'GET'
    })

    let response = await getLast.json()
    let buttonAdd = document.querySelector('.crud__add');
    buttonAdd.dataset.filmId = (response.id + 1);
    console.log(buttonAdd.dataset.filmId);
  }

}