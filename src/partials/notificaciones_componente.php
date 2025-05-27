<div class="relative mb-4">
    <button id="btn-notificaciones" class="relative focus:outline-none">
        <i class="fas fa-bell"></i>
        <span id="contador-notificaciones" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-2 hidden">0</span>
    </button>
    <div id="dropdown-notificaciones" class="hidden absolute right-0 mt-2 w-80 bg-white text-gray-800 rounded shadow-lg z-50">
        <div class="p-4 border-b font-bold">Notificaciones</div>
        <ul id="lista-notificaciones" class="max-h-64 overflow-y-auto"></ul>
        <div class="p-2 text-center text-xs text-gray-500">Solo se muestran las 10 más recientes</div>
    </div>
</div>
<script src="https://kit.fontawesome.com/4e4b8b8e8b.js" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('btn-notificaciones');
    const dropdown = document.getElementById('dropdown-notificaciones');
    const contador = document.getElementById('contador-notificaciones');
    const lista = document.getElementById('lista-notificaciones');

    btn.addEventListener('click', function() {
        dropdown.classList.toggle('hidden');
    });

    // Cierra el dropdown si se hace click fuera
    document.addEventListener('click', function(e) {
        if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Cargar notificaciones por AJAX
    fetch('notificaciones_controlador.php')
        .then(res => res.json())
        .then(data => {
            let noLeidas = 0;
            lista.innerHTML = '';
            data.forEach(n => {
                if (n.leida == 0) noLeidas++;
                lista.innerHTML += `<li class="p-3 border-b ${n.leida == 0 ? 'bg-blue-100' : ''}">
                    <div class="text-sm">${n.mensaje}</div>
                    <div class="text-xs text-gray-500">${n.fecha}</div>
                </li>`;
            });
            if (noLeidas > 0) {
                contador.textContent = noLeidas;
                contador.classList.remove('hidden');
            } else {
                contador.classList.add('hidden');
            }
            if (data.length === 0) {
                lista.innerHTML = '<li class="p-3 text-center text-gray-500">Sin notificaciones</li>';
            }
        });
});
</script>