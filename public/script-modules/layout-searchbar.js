function clearSearchBar() {
    let elem = document.getElementById("search-bar");
    elem.value = "";
}

// document.addEventListener('DOMContentLoaded', function() {
//     const searchBar = document.querySelector('.search-bar');
//     const searchBarInput = document.querySelector('.search-bar-input');

//     searchBarInput.addEventListener('focus', function() {
//         searchBar.classList.add('active');
//     });

//     searchBarInput.addEventListener('blur', function() {
//         searchBar.classList.remove('active');
//     });
// });

// document.addEventListener('DOMContentLoaded', function() {
//     const deleteElement = document.querySelector('.delete');
//     const deleteInput = document.querySelector('.delete-input');

//     deleteInput.addEventListener('focus', function() {
//        deleteElement.classList.add('active');
//     });

//     deleteInput.addEventListener('blur', function() {
//         deleteElement.classList.remove('active');
//     });
// });