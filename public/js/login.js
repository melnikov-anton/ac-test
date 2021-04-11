const loginAction = () => {
  const usernameField = document.querySelector('#uname');
  const passwordField = document.querySelector('#pass');
  const rememberMeField = document.querySelector('#remember-me');
  const errorText = document.querySelector('.error');
  if (usernameField.value == '' || passwordField.value == '') {
    errorText.innerHTML = 'Username and password are required!';
  } else {
    errorText.innerHTML = '';
    const loginData = {
      username: usernameField.value,
      password: passwordField.value,
      remember_me: rememberMeField.checked ? 1 : 0
    };
    fetch(
      '/auth',
      {
        method: 'POST',
        body: JSON.stringify(loginData),
        headers: {'Content-Type': 'application/json'}
      }
    ).then(response => response.json())
      .then(data => {
        if (data.status == 'failed') {
          errorText.innerHTML = data.message;
        } else if (data.status == 'success') {
          usernameField.value = '';
          passwordField.value = '';
          rememberMeField.checked = false;
          if (data.expires == 0) {
            sessionStorage.setItem('token', data.token);
            localStorage.removeItem('token');
            localStorage.removeItem('expires');
          } else {
            localStorage.setItem('token', data.token);
            localStorage.setItem('expires', data.expires);
            sessionStorage.removeItem('token');
          }
          window.location.href = '/list.html';
        }
      })
      .catch(err => console.log(err))
  }
}

const loginBtn = document.querySelector('.btn-login');
loginBtn.addEventListener('click', loginAction);
