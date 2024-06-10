let searchbar;
let rightClickAmount = 0;

function clearSearchBar() {
    searchbar.value = "";
}

document.addEventListener('DOMContentLoaded', function() {
    searchbar = document.getElementById("search-bar");

    searchbar.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        rightClickAmount++;
        if (rightClickAmount >= 2) {
            clearSearchBar();
        }
        setTimeout(() => {
            rightClickAmount -= 1;
        }, 200);
    });
});

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