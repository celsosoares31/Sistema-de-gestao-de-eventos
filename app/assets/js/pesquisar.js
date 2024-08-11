const search = document.querySelector('#search');
const span = document.querySelector('#pesquisa');
search.addEventListener('keyup', async (e) => {
  e.preventDefault();
  let pesquisa = search.value;
  const dados = await fetch(`class/controller/search.php?text=${pesquisa}`);

  // fetch(`class/controller/search.php?text=${pesquisa}`)
  //   .then((response) => response.text()) // Get the raw response text
  //   .then((text) => {
  //     console.log('Response Text:', text); // Log the raw response
  //     try {
  //       const data = JSON.parse(text); // Try parsing it manually
  //       console.log(data);
  //     } catch (e) {
  //       console.error('Failed to parse JSON:', e);
  //     }
  //   })
  //   .catch((error) => {
  //     console.error('Fetch error:', error);
  //   });

  const ul = document.createElement('ul');
  ul.classList.add('list-group');
  span.innerHTML = '';

  const resp = await dados.json();

  if (search.value) {
    if (resp.status) {
      const resultados = resp.dados;

      let content = '';

      for (let i = 0; i < resultados.length; i++) {
        ul.innerHTML += `
        <div class='d-flex flex-column p-4 border-bottom border-5 border-black w-100'>
          <a href="index.php?page=home&action=view&id=${resultados[i].evento_id}" class="d-flex icon-link-hover bg-transparent list-group-item list-group-item-action border-0">
            <img class='usr-profile-image-pesquisa me-3' src='./images/eventos/${resultados[i].evento_id}/${resultados[i].background}'/>
            <h1 class='h1'>${resultados[i].nome}</h1>
          </a>
          <hr>
          <p class='fs-6'>
            <strong class='me-2'>Local:</strong>
            <span class='fs-7'>${resultados[i].local}</span>
          </p>
          <p class='fs-6 descricao'>
            <strong class='me-2'>Descrição:</strong>
            <span class='fs-7'>${resultados[i].descricao}</span>
          </p>
        </div>
        `;
      }
    } else {
      ul.innerHTML = `<li class="list-group-item list-group-item-action">Nenhum resultado encontrado</li>`;
    }
    span.appendChild(ul);
  }
});
main.addEventListener('click', () => {
  search.value = '';
  span.innerHTML = '';
});
