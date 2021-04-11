const logoutAction = () => {
  localStorage.removeItem('token');
  localStorage.removeItem('expires');
  sessionStorage.removeItem('token');
  fetch(
    '/auth',
    {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
        'X-Expires': tokenExp
      }
    }
  )
  .then(response => response.json())
  .then(data => console.log(data.message))
  .catch(err => console.log(err));
  window.location.href = '/login.html';
}

const getStudents = (page=1, limit=5) => {
  fetch(
    `/users?page=${page}&limit=${limit}`,
    {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${token}`,
        'X-Expires': tokenExp
      }
    }
  )
  .then(response => response.json())
  .then(data => {
    if (data.status == 'success') {
      let tableHtml = '';
      const students = data.students;
      from = data.from;
      students.forEach((student) => {
        tableHtml += `<tr><td>${student.full_name}</td><td>${student.group}</td></tr>`;
      });
      table.innerHTML = tableHtml;
      renderPaginator(page, limit, from);
    }
  })
  .catch(err => console.log(err));
}

const loadPage = (pageNum) => {
  getStudents(pageNum, limit);
}

const renderPaginator = (currPage, limit, amount) => {
  let paginatorHtml = '';
  const items = Math.floor(amount / limit) + 1;
  for (let i = 1; i <= items; i++) {
    paginatorHtml += `<span class="page" data-page="${i}">${i}</span>`
  }
  paginator.innerHTML = paginatorHtml;
  document.querySelectorAll('.page').forEach((pageElement) => {
    pageElement.addEventListener('click', () => {
      loadPage(pageElement.dataset.page);
    });
    if (pageElement.dataset.page == currPage) {
      pageElement.classList.add('current-page');
    }
  })
}

const logoutBtn = document.querySelector('.btn-logout');
logoutBtn.addEventListener('click', logoutAction);
const table = document.querySelector('table');
const paginator = document.querySelector('.pagination');
table.innerHTML = '';
let currentPage = 1;
const limit = 5;
let from = 0;

window.onload = () => {
  getStudents(currentPage, limit);
}
