const errorDiv = document.querySelector('#error');
function closeMyToast() {
  document.querySelector('.mytoast').classList.remove('hide');
}

/**
 *
 * @param {*} resp
 * @returns
 */
function openModal(resp) {
  return resp;
}

const main = document.getElementById('main');
const footer = document.querySelector('footer');
function openNav() {
  document.getElementById('mySidenav').style.width = '300px';
  main.style.marginLeft = '300px';

  main.style.paddingRight = '50px';
  main.style.paddingLeft = '20px';
  main.style.paddingTop = '20px';
  main.style.paddingBottom = '20px';

  main.style.width = 'calc(100vw - 300px)';
}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0 */
function closeNav() {
  document.getElementById('mySidenav').style.width = '0';
  main.style.marginLeft = '0';
  main.style.width = '100vw';
  main.style.paddingLeft = '80px';
}

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl));

// bootstrap toast trigger
// const toastTrigger = document.getElementById('liveToastBtn');
const toastLiveExample = document.getElementById('liveToast');

if (toastLiveExample) {
  const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
  toastBootstrap.show();
}

// validacao do formulario;
/**
 *
 * @param {*} target
 * @returns boolean
 */
function validate(target) {
  const now = new Date().toLocaleString();

  const hoje = new Date(now);

  const data_hora_inicio = document.querySelector('#data_hora_inicio');
  const data_hora_fim = document.querySelector('#data_hora_fim');

  const date1 = new Date(data_hora_inicio.value);
  const date2 = new Date(data_hora_fim.value);

  if (hoje > date1) {
    alert(`A data de inicio nao pode ser menor que a data actual ${hoje.toLocaleString()}`);
    return false;
  }

  if (date1 > date2) {
    alert('A data de inicio nao pode ser maior que a data final');
    data_hora_inicio.focus();
  } else {
    return true;
  }

  return false;
}
