let token = false;
let tokenExp = 0;
if (sessionStorage.getItem('token')) {
	token = sessionStorage.getItem('token');
	tokenExp = 0;
} else if (localStorage.getItem('token')) {
	token = localStorage.getItem('token');
	tokenExp = localStorage.getItem('expires');
} else {
	if (location.pathname != '/login.html') {
		window.location.href = '/login.html';
	}
}
if (token && location.pathname == '/login.html') {
	window.location.href = '/list.html';
}
