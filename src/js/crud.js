const isCrud = document.querySelector('main[crud]');
import fetchRequest from "@/js/fetch";

if (isCrud) {
  const buttonAddFilm = document.querySelector('.films__add');
  const filmsTableBody = document.querySelector('.films__body');
  const buttonAddCategory = document.querySelector('.categories__add');
  const categoryTableBody = document.querySelector('.categories__body');


  getAllFilms();
  getLastFilmId();
  getAllCategories();
  getLastCategoryId()


  // fetch('https://cinemalo.000webhostapp.com/DataManager/FilmsController.php?action=getAll', {
  //   method: 'GET',
  // }).then(res => {
  //   res.json()
  //     .then(allFilms => {
  //       allFilms.forEach(film => {
  //         renderFilm(film)
  //       })
  //     })
  //     .catch(err => {
  //       console.log(err)
  //     })
  // })

  buttonAddFilm.addEventListener('click', renderFilm)
  buttonAddCategory.addEventListener('click', renderCategory)

  function renderFilm(film) {
    let idFilm;
    let isNew;
    if (!film.target) {
      idFilm = film.id
      isNew = false;
    } else {
      idFilm = film.target.dataset.filmId;
      isNew = true;
      let isExist = filmsTableBody.querySelector(`div[film-id="${idFilm}"]`);
      if (isExist) {
        return;
      }
    }
    let newFilm = document.createElement('div');
    newFilm.setAttribute('film-id', `${idFilm}`);
    newFilm.setAttribute('data-which-table', 'films');
    newFilm.setAttribute('data-which-category', `${film.categorie_id}`)
    newFilm.classList.add('films__item');
    if (!isNew) {
      newFilm.classList.add('cannot-edit');
    }
    newFilm.insertAdjacentHTML('afterbegin',
      `
      <input class="id"  type="text" disabled value="${idFilm}">
                    <textarea type="text" ${!isNew ? 'disabled' : ''}   class="title textarea" caneditable>${!isNew ? `${film.title}` : ''} </textarea>
                    <textarea name="description" ${!isNew ? 'disabled' : ''}  class="textarea description" id="textarea" caneditable>${!isNew ? `${film.description}` : ''}</textarea>
                    <input type="text" ${!isNew ? 'disabled' : ''} ${!isNew ? `value="${film.categorie_id}"` : ''}  class="categorie" caneditable>
                    <div class="films__file-wrap">
                        <input class="file-input" type="file" accept="image/*" id="file-${idFilm}"  ${!isNew ? 'disabled' : ''} caneditable>
                        <label for="file-${idFilm}"> ${!isNew ? `${film.image}` : 'Выберите фото'}</label>
                    </div>
                    <div class="films__actions">
<!--                        <button class="hidden" data-crud-action="remove">-</button>-->
                        <button class="edit-button" data-crud-action="edit"></button>
                        <button class="remove-button" data-crud-action="remove"></button>
                        <button class="save-button" data-crud-action="save"></button>
                    </div>
      
           `)
    filmsTableBody.append(newFilm);

    let newRecordFilm = document.querySelector(`div[film-id="${idFilm}"]`);
    let newRecordFilmButtons = newRecordFilm.querySelectorAll('.films__actions button');
    newRecordFilmButtons.forEach(button => {
      button.addEventListener('click', crudAction);
    })

    const inputFile = newRecordFilm.querySelector('.file-input');

    inputFile.addEventListener('change', evt => {
      if (evt.target.files[0]) {
        let fileName = evt.target.files[0].name;
       // console.log(fileName);
        let label = newRecordFilm.querySelector('.films__file-wrap label');
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

  function renderCategory(category) {
    //console.log(category.id, category.categorie_title)
    let idCategory;
    let isNew;
    if (!category.target) {
      idCategory = category.id
      isNew = false;
    } else {
      idCategory = category.target.dataset.categoryId;
      isNew = true;
      let isExist = categoryTableBody.querySelector(`div[category-id="${idCategory}"]`);
      if (isExist) {
        return;
      }
    }
    let newCategory = document.createElement('div');
    newCategory.setAttribute('category-id', `${idCategory}`);
    newCategory.setAttribute('data-which-table', 'categories')
    newCategory.classList.add('categories__item');
    if (!isNew) {
      newCategory.classList.add('cannot-edit');
    }
    newCategory.insertAdjacentHTML('afterbegin',
      `
      <input type="text" class="id" disabled value="${idCategory}">
      <input type="text" class="category" ${!isNew ? 'disabled' : ''} ${!isNew ? `value="${category.categorie_title}"` : ''} caneditable>
                    <div class="categories__actions">
                        <button class="show-films-by-category" data-which-category="${category.categorie_title}" ></button>
                        <button class="edit-button" data-crud-action="edit"></button>
                        <button class="remove-button" data-crud-action="remove"></button>
                        <button class="save-button" data-crud-action="save"></button>
                    </div>
      
           `)
    categoryTableBody.append(newCategory);

    let newRecordCategory = document.querySelector(`div[category-id="${idCategory}"]`);
    let showFilmsByCategory = newRecordCategory.querySelector('.show-films-by-category');
    let newRecordCategoryButtons = newRecordCategory.querySelectorAll('.categories__actions button');

    showFilmsByCategory.addEventListener('click', scrollByCategory)

    newRecordCategoryButtons.forEach(button => {
      button.addEventListener('click', crudAction);
    })

    if (isNew) {
      newRecordCategory.querySelector('.category').focus()
    }
  }

  async function crudAction(evt) {
    const whichAction = evt.target.dataset.crudAction;

    switch (whichAction) {
      case 'save':
        actionSave(evt);
        break;
      case 'remove':
        actionRemove(evt);
        break;
      case 'edit':
        actionEdit(evt);
        break;
    }

    function actionSave(evt) {
      const currentItem = evt.toElement.offsetParent;
      if (currentItem.classList.contains('cannot-edit')) {
        return;
      }
      const whichTable = currentItem.dataset.whichTable;

      if (whichTable === 'films') {
        const errorBlock = document.querySelector('.error-films span');
        errorBlock.innerHTML = '';

        let currentFilm = currentItem;
        let formData = new FormData();
        const idCurrentFilm = currentFilm.querySelector('.id').value;
        const titleCurrentFilm = currentFilm.querySelector('.title');
        const descriptionCurrentFilm = currentFilm.querySelector('.description');
        const categorieCurrentFilm = currentFilm.querySelector('.categorie');
        let photoCurrentFilm = currentFilm.querySelector('.file-input');
        const todayDate = getTodayDate();


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
          //console.log(photoCurrentFilm)
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
        getLastFilmId();

        fetchRequest('films', 'add', 'POST', formData)
          .then(res => {
           // console.log(res)
          })
          .catch(err => {
           // console.log(err)
          })
          .finally(() => {
            getLastFilmId()
            getAllCategories()
          })

        // fetch('https://cinemalo.000webhostapp.com/DataManager/FilmsController.php?action=add', {
        //   method: 'POST',
        //   body: formData
        // })
      }
      if (whichTable === 'categories') {
        const errorBlock = document.querySelector('.error-categories span');
        errorBlock.innerHTML = '';
        let currentCategory = currentItem;
        let formData = new FormData()
        const idCurrentCategory = currentCategory.querySelector('.id').value;
        const titleCurrentCategory = currentCategory.querySelector('.category');

        if (titleCurrentCategory.value.trim() === '') {
          errorBlock.innerHTML = 'Введите название категории';
          return;
        }

        titleCurrentCategory.setAttribute('disabled', '');

        formData.append('id', `${idCurrentCategory}`);
        formData.append('categoryName', `${titleCurrentCategory.value}`);

        currentCategory.classList.add('cannot-edit');
        getLastCategoryId();

        fetchRequest('category', 'add', 'POST', formData)
          .then(res => {
           // console.log(res)
          })
          .catch(err => {
           // console.log(err)
          })
          .finally(() => {
            getLastCategoryId()
            getAllFilms()
          })

      }


    }

    function actionRemove(evt) {
      const currentItem = evt.toElement.offsetParent;
      if (currentItem.classList.contains('cannot-edit')) {
        return;
      }
      const whichTable = currentItem.dataset.whichTable;

      if (whichTable === 'films') {
        let currentFilm = currentItem;
        const currentId = currentFilm.getAttribute('film-id');
        let formData = new FormData();
        formData.append('id', currentId);
        currentFilm.remove();


        fetchRequest('films', 'delete', 'POST', formData)
          .then(res => {
          //  console.log(res, 'successful')
          })
          .catch(err => {
           // console.log(err, 'error')
          })
          .finally(() => {
            getLastFilmId()
            getAllCategories()
          })
      }

      if (whichTable === 'categories') {
        let currentCategory = currentItem;
        const currentId = currentCategory.getAttribute('category-id');
        let formData = new FormData();
        formData.append('id', currentId);
        currentCategory.remove();

        fetchRequest('category', 'delete', 'POST', formData)
          .then(res => {
            console.log(res, 'successful')
          })
          .catch(err => {
            console.log(err, 'error')
          })
          .finally(() => {
            getLastCategoryId()
            getAllFilms()
          })

      }


    }

    function actionEdit(evt) {
      const currentItem = evt.toElement.offsetParent;
      console.log(currentItem);
      currentItem.classList.remove('cannot-edit');
      let allField = currentItem.querySelectorAll('[caneditable]');
      allField.forEach(field => {
        field.removeAttribute('disabled')
      })
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

  async function getLastFilmId() {
    let getLast = await fetchRequest('films', 'getLast', 'GET')
    let response = await getLast.json()
    let buttonAdd = document.querySelector('.films__add');
    buttonAdd.dataset.filmId = (response.next_id);
  }

  async function getAllFilms() {
    let isExistFilm = document.querySelectorAll('.films__item');
    if (isExistFilm) {
      isExistFilm.forEach(film => {
        film.remove();
      })
    }
    let response = await fetchRequest('films', 'getAll', 'GET');
    let allFilms = await response.json();
    allFilms.forEach(film => {
      renderFilm(film)
    })
  }

  async function getAllCategories() {
    let isExistFilm = document.querySelectorAll('.categories__item');
    if (isExistFilm) {
      isExistFilm.forEach(category => {
        category.remove();
      })
    }
    let response = await fetchRequest('category', 'getAll', 'GET');
    let allCategories = await response.json();
    allCategories.forEach(category => {
      renderCategory(category)
    })
  }

  async function getLastCategoryId() {
    let getLast = await fetchRequest('category', 'getLast', 'GET')
    let response = await getLast.json()
    let buttonAdd = document.querySelector('.categories__add');
    buttonAdd.dataset.categoryId = (response.next_id);
  }

  function scrollByCategory(evt) {
    const currentItem = evt.target;
    const whichCategory = currentItem.dataset.whichCategory;
    const filmsByCategory = filmsTableBody.querySelectorAll(`div[data-which-category='${whichCategory}']`);
    const filmByCategory = filmsByCategory[0];

    if (filmByCategory) {
      filmsByCategory.forEach((film)=> {
        film.classList.add('highlight');
      })

      setTimeout(()=> {
        filmsByCategory.forEach((film)=> {
          film.classList.remove('highlight');
        })
      }, 2500)

      filmByCategory.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
      })
    }


  }

  // let allButtons = document.querySelectorAll('.test')
  // allButtons.forEach((button)=> {
  //   setTimeout(() => {
  //     button.innerHTML = 'DONE'
  //   }, 3000)
  // })

}