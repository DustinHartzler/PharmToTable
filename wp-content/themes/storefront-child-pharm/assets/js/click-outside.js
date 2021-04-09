const cardButtons = document.querySelectorAll('.card button');
const modalOuter = document.querySelector('.modal-outer');
const modalInner = document.querySelector('.modal-inner');

function handleCardButtonClick(event) {
  const button = event.currentTarget;
  const card = button.closest('.card');
  // Grab the image src
  //const imgSrc = card.querySelector('img').src;
  const desc = card.dataset.description;
  const name = card.querySelector('h4').textContent;
  const title = card.querySelector('h5').textContent;
  // populate the modal with the new info
  modalInner.innerHTML = `

    <h2>${name}</h2>
    <h5>${title}</h5>
    <p>${desc}</p>
    <button onclick="closeModal()">Close</button>
  `;
  // show the modal
  modalOuter.classList.add('open');
}

cardButtons.forEach((button) =>
  button.addEventListener('click', handleCardButtonClick)
);

function closeModal() {
  modalOuter.classList.remove('open');
}

modalOuter.addEventListener('click', function (event) {
  const isOutside = !event.target.closest('.modal-inner');
  if (isOutside) {
    closeModal();
  }
});

window.addEventListener('keydown', (event) => {
  console.log(event);
  if (event.key === 'Escape') {
    closeModal();
  }
});


// Try to stop scrolling when modal is open:
