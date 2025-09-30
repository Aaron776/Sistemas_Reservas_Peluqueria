</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Funcionalidad para mostrar/ocultar sidebar en móviles
    const toggleSidebar = document.getElementById('toggle-sidebar');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('overlay');

    toggleSidebar.addEventListener('click', function() {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', function() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });

    // Funcionalidad para los botones de reserva
    document.querySelectorAll('.service-card .btn').forEach(button => {
        button.addEventListener('click', function() {
            const serviceName = this.parentElement.querySelector('h3').textContent;
            alert(`Redirigiendo a reserva de: ${serviceName}`);
            // En una implementación real, redirigiría a la página de reserva
        });
    });

    // Funcionalidad para los elementos del menú
    document.querySelectorAll('.sidebar-menu-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.sidebar-menu-item').forEach(i => {
                i.classList.remove('active');
            });
            this.classList.add('active');

            // En una implementación real, aquí se cargaría el contenido correspondiente
            if (this.querySelector('span').textContent === 'Cerrar Sesión') {
                window.location.href = 'login.html';
            }
        });
    });
</script>
</body>

</html>