document.addEventListener('DOMContentLoaded', () => {
  const filterButtons = document.querySelectorAll('.filter-btn');
  const intensitySlider = document.getElementById('intensityFilter');
  const productos = document.querySelectorAll('.producto-card');

  let currentCategory = 'all';
  let currentIntensity = 3;

  function filtrarProductos() {
    productos.forEach(producto => {
      const categoria = producto.getAttribute('data-category');
      const intensidad = parseInt(producto.getAttribute('data-intensity'), 10);

      const categoriaMatch = (currentCategory === 'all' || categoria === currentCategory);
      const intensidadMatch = intensidad <= currentIntensity;

      producto.style.display = (categoriaMatch && intensidadMatch) ? '' : 'none';
    });
  }

  filterButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      filterButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      currentCategory = btn.getAttribute('data-filter');
      filtrarProductos();
    });
  });

  intensitySlider.addEventListener('input', e => {
    currentIntensity = parseInt(e.target.value, 10);
    filtrarProductos();
  });

  filtrarProductos();

  // Cantidad
  document.querySelectorAll('.cantidad-btn').forEach(btn => {
    btn.addEventListener('click', () => cambiarCantidad(btn, btn.textContent === '+' ? 1 : -1));
  });

  // Carrito en memoria
  const carrito = [];

  function cambiarCantidad(button, delta) {
    const cantidadSpan = button.parentElement.querySelector('.cantidad');
    let cantidad = parseInt(cantidadSpan.textContent, 10);
    cantidad += delta;
    if (cantidad < 1) cantidad = 1;
    cantidadSpan.textContent = cantidad;
  }

  function agregarAlCarrito(event) {
    const btn = event.target;
    const card = btn.closest('.producto-card');
    const nombre = card.querySelector('h3').textContent;
    const precioText = card.querySelector('.precio').textContent;
    const precio = parseFloat(precioText.replace(/[^\d.]/g, ''));
    const cantidad = parseInt(card.querySelector('.cantidad').textContent, 10);

    const index = carrito.findIndex(item => item.nombre === nombre);
    if (index !== -1) {
      carrito[index].cantidad += cantidad;
    } else {
      carrito.push({ nombre, precio, cantidad });
    }

    alert(`${cantidad} x ${nombre} agregado(s) al carrito.`);
    // Aquí puedes actualizar un carrito visual o enviarlo a backend
  }

  document.querySelectorAll('.btn-comprar').forEach(btn => {
    btn.addEventListener('click', agregarAlCarrito);
  });
});
