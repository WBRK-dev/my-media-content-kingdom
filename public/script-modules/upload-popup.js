let uploadPopup;



document.addEventListener('DOMContentLoaded', () => {

    document.querySelector('#upload-popup').addEventListener('click', (e) => {
        if (e.target.id === 'upload-popup') toggleUploadPopup();
    });

});




function toggleUploadPopup() {
    if (!uploadPopup) uploadPopup = document.querySelector("#upload-popup");
    uploadPopup.classList.toggle('show');
}

function nextUploadPopupPage() {
    if (!uploadPopup) uploadPopup = document.querySelector("#upload-popup");

    const currentPageNumber = uploadPopup.getAttribute('data-page');
    const currentPage = uploadPopup.querySelector(`[data-page="${currentPageNumber}"]`);
    const prevPage = uploadPopup.querySelector(`[data-page="${parseInt(currentPageNumber) - 1}"]`);
    const nextPage = uploadPopup.querySelector(`[data-page="${parseInt(currentPageNumber) + 1}"]`);
    const nextNextPage = uploadPopup.querySelector(`[data-page="${parseInt(currentPageNumber) + 2}"]`);

    if (!nextPage) throw new Error('No next page found');

    currentPage.classList.add('upload-popup-page-prev');

    nextPage.classList.remove('upload-popup-page-next');
    nextPage.classList.add('upload-popup-page-ontop');

    if (nextNextPage) nextNextPage.classList.add('upload-popup-page-next');

    if (prevPage) prevPage.classList.remove('upload-popup-page-prev');
    if (prevPage) prevPage.classList.remove('upload-popup-page-ontop');

    uploadPopup.setAttribute('data-page', parseInt(currentPageNumber) + 1);
}

function prevUploadPopupPage() {
    if (!uploadPopup) uploadPopup = document.querySelector("#upload-popup");

    const currentPageNumber = uploadPopup.getAttribute('data-page');
    const currentPage = uploadPopup.querySelector(`[data-page="${currentPageNumber}"]`);
    const prevPage = uploadPopup.querySelector(`[data-page="${parseInt(currentPageNumber) - 1}"]`);
    const prevPrevPage = uploadPopup.querySelector(`[data-page="${parseInt(currentPageNumber) - 2}"]`);
    const nextPage = uploadPopup.querySelector(`[data-page="${parseInt(currentPageNumber) + 1}"]`);

    if (!prevPage) throw new Error('No prev page found');

    currentPage.classList.add('upload-popup-page-next');

    prevPage.classList.remove('upload-popup-page-prev');
    prevPage.classList.add('upload-popup-page-ontop');

    if (prevPrevPage) prevPrevPage.classList.add('upload-popup-page-prev');

    if (nextPage) nextPage.classList.remove('upload-popup-page-next');
    if (nextPage) nextPage.classList.remove('upload-popup-page-ontop');

    uploadPopup.setAttribute('data-page', parseInt(currentPageNumber) - 1);
}

function uploadPopupOpenFileDialog(type) {
    if (!uploadPopup) uploadPopup = document.querySelector("#upload-popup");

    uploadPopup.querySelector(`input#${type}`).click();
}

function uploadPopupFileChange(input, type) {
    if (!uploadPopup) uploadPopup = document.querySelector("#upload-popup");

    nextUploadPopupPage();
    uploadPopup.querySelector(`p#${type}-title`).innerText = input.files[0].name;
}

function uploadPopupSubmitFromYoutube() {
    if (!uploadPopup) uploadPopup = document.querySelector("#upload-popup");

    const youtubeId = uploadPopup.querySelector('input#youtube-id').value;

    if (!youtubeId) return;

    uploadPopup.querySelector('form#youtube-form #youtube').value = youtubeId;
    uploadPopup.querySelector('form#youtube-form').submit();
}