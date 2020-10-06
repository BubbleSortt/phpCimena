let isForm = document.querySelector('.main-form')


if (isForm) {
  let eye = document.querySelector('.show-password img')
  let password = document.querySelector('#password')
  eye.addEventListener('click', evt => {
    if (password.type === 'password') {
      password.type = 'text'
    } else {
      password.type = 'password'
    }
  })
}