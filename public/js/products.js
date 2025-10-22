// Búsqueda con debounce
(function () {
  const form = document.getElementById('filtersForm');
  if (!form) return;
  const q = form.querySelector('input[name="q"]');
  let tId;
  if (q) {
    q.addEventListener('input', () => {
      clearTimeout(tId);
      tId = setTimeout(() => form.submit(), 450);
    });
  }
})();

function autoSubmit() {
  const form = document.getElementById('filtersForm');
  if (form) form.submit();
}

function clearFilters() {
  const form = document.getElementById('filtersForm');
  if (!form) return;
  [...form.elements].forEach(el => {
    if (['q','min_price','max_price'].includes(el.name)) el.value = '';
    if (['category_id','sort','per_page'].includes(el.name)) el.selectedIndex = 0;
    if (['only_active','in_stock'].includes(el.name)) el.checked = false;
  });
  form.submit();
}

function setView(mode) {
  const list = document.getElementById('productList');
  if (!list) return;
  document.cookie = "view_mode=" + mode + ";path=/;max-age=" + (60*60*24*365);
  const gridBtn = document.getElementById('gridBtn');
  const listBtn = document.getElementById('listBtn');
  if (gridBtn && listBtn) {
    gridBtn.classList.toggle('active', mode === 'grid');
    listBtn.classList.toggle('active', mode === 'list');
  }
  list.classList.toggle('grid-view', mode === 'grid');
  list.classList.toggle('list-view', mode === 'list');
  list.querySelectorAll('.card').forEach(c => c.classList.toggle('list', mode === 'list'));
}

function addToCart(id) {
  alert('Demo: agregar producto ' + id + ' al carrito (implementa tu lógica).');
}

function preview(id) {
  alert('Demo: ver detalle del producto ' + id + ' (linkea a tu ruta show).');
}

// Chips de filtros activos
(function buildTags(){
  const tags = document.getElementById('activeTags');
  if (!tags) return;
  const params = new URLSearchParams(window.location.search);
  const map = { q:'Búsqueda', category_id:'Categoría', min_price:'Desde $', max_price:'Hasta $', only_active:'Activos', in_stock:'En stock', sort:'Orden', per_page:'Por página' };
  params.forEach((v,k)=>{
    if(!v) return;
    let label = map[k] ?? k;
    if(k==='only_active' || k==='in_stock') v='';
    const span = document.createElement('span');
    span.className='tag';
    span.textContent = label + (v?`: ${v}`:'');
    span.title = 'Quitar filtro';
    span.style.cursor='pointer';
    span.onclick = ()=>{ params.delete(k); location.search = params.toString(); };
    tags.appendChild(span);
  });
})();

// Vista inicial desde cookie
(function initView(){
  const m = (document.cookie.match(/(?:^|; )view_mode=([^;]+)/)||[])[1] || 'grid';
  setView(decodeURIComponent(m));
})();
// Mantener vista y hacer scroll-to-top al cambiar página
(function enhancePager(){
  const links = document.querySelectorAll('[data-page-nav]');
  if(!links.length) return;
  links.forEach(a=>{
    a.addEventListener('click', (e)=>{
      const disabled = a.classList.contains('is-disabled');
      if(disabled || !a.href) return;
      // Preservar cookie de vista (ya se guarda sola). Solo efecto de UX:
      // scroll al tope para que el usuario vea el inicio de la grilla.
      // Dejar que el link navegue normal.
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }, { passive:true });
  });
})();
