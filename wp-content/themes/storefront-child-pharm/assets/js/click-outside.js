const cardButtons = document.querySelectorAll('.card button');
const modalOuter = document.querySelector('.modal-outer');
const modalInner = document.querySelector('.modal-inner');

function handleCardButtonClick(event) {
  const button = event.currentTarget;
  const card = button.closest('.card');
  const scrollY = document.documentElement.style.getPropertyValue('--scroll-y');
  const body = document.body;
  //body.style.position = 'fixed';
  body.style.top = `-${scrollY}`;
  // Grab the image src
  //const imgSrc = card.querySelector('img').src;
  const desc = card.dataset.description;
  const name = card.querySelector('h4').textContent;
  // populate the modal with the new info
  modalInner.innerHTML = `

    <h2>${name}</h2>
    <p>${desc}</p>
  `;
  // show the modal
  modalOuter.classList.add('open');
}

cardButtons.forEach((button) =>
  button.addEventListener('click', handleCardButtonClick)
);

function closeModal() {
  modalOuter.classList.remove('open');
  const body = document.body;
  const scrollY = body.style.top;
  body.style.position = '';
  body.style.top = '';
  //window.scrollTo(0, parseInt(scrollY || '0') * -1);
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
