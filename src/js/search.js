import fetchRequest from "@/js/fetch";

let headerSearch = document.querySelector('.header-search');
let headerSearchInput = headerSearch.querySelector('.header-search__input');
let headerSearchDropdown = headerSearch.querySelector('.header-search__dropdown');

headerSearchInput.addEventListener('keyup', evt => {
  if (evt.code === 'Enter') {
    let valueInput = headerSearchInput.value;
    let formData = new FormData();
    formData.append('referal', valueInput)

    fetchRequest('search', 'search', 'POST', formData)
      .then(res => {
        res.json().then(allSearch => {
          if (allSearch.length > 0) {
            document.querySelector('.header-search__error').style.display = 'none';
            let headerSearchList = document.querySelector('.header-search__list');
            console.log(headerSearchList, 'list');
            headerSearchList.querySelectorAll('.search-item').forEach(item => {
              console.log(item, 'removed');
              item.remove()
            })
            allSearch.forEach(search => {
              renderSearched(search)
            })
          } else {
            let headerSearchList = document.querySelector('.header-search__list');
            console.log(headerSearchList, 'list');
            headerSearchList.querySelectorAll('.search-item').forEach(item => {
              console.log(item, 'removed');
              item.remove()
            })
            document.querySelector('.header-search__error').style.display = 'block';
          }
        })
      })
      .catch(err => {
          console.log(err)
        }
      )
  }
});

headerSearchInput.addEventListener('focus', evt => {
  headerSearchDropdown.style.display = 'block';
    // document.addEventListener('click', closeDropdown);
})
headerSearchInput.addEventListener('blur', evt => {
  headerSearchDropdown.style.display = 'none';
    // document.removeEventListener('click', closeDropdown);
})

function renderSearched(film) {

  let headerSearchList = document.querySelector('.header-search__list');


  let newItem = document.createElement('div');
  newItem.classList.add('search-item');
  newItem.classList.add('header-search__item');
  newItem.insertAdjacentHTML('afterbegin', `
                                <a class="search-item__title" href="#">${film.title}</a>
                                <span class="search-item__category">Категория: ${film.categorie_id}</span>
  `)
  headerSearchList.append(newItem);
  headerSearchDropdown.style.display = 'block';

}

function renderError() {

}
