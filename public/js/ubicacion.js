
document.addEventListener('DOMContentLoaded', function() {

    function resetSelect(select, placeholder) {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        select.disabled = true;
    }

    // Cuando cambia Región
    document.getElementById('id_region').addEventListener('change', function() {
        var regionID = this.value;
        var provincia = document.getElementById('id_provincia');
        var distrito = document.getElementById('id_distrito');

        resetSelect(provincia, 'Seleccione...');
        resetSelect(distrito, 'Seleccione...');

        if (regionID) {
            fetch('/provincias/' + regionID)
                .then(response => response.json())
                .then(data => {
                    provincia.disabled = false;
                    data.forEach(function(provinciaItem) {
                        var option = document.createElement('option');
                        option.value = provinciaItem.id_provincia;
                        option.textContent = provinciaItem.nombre;
                        provincia.appendChild(option);
                    });
                })
                .catch(() => {
                    alert('Error al cargar provincias.');
                });
        }
    });

    // Cuando cambia Provincia
    document.getElementById('id_provincia').addEventListener('change', function() {
        var provinciaID = this.value;
        var distrito = document.getElementById('id_distrito');

        resetSelect(distrito, 'Seleccione...');

        if (provinciaID) {
            fetch('/distritos/' + provinciaID)
                .then(response => response.json())
                .then(data => {
                    distrito.disabled = false;
                    data.forEach(function(distritoItem) {
                        var option = document.createElement('option');
                        option.value = distritoItem.id_distrito;
                        option.textContent = distritoItem.nombre;
                        distrito.appendChild(option);
                    });
                })
                .catch(() => {
                    alert('Error al cargar distritos.');
                });
        }
    });

});
document.addEventListener('DOMContentLoaded', function () {
    const regionSelect = document.getElementById('idRegion');
    const provinciaSelect = document.getElementById('idProvincia');
    const distritoSelect = document.getElementById('idDistrito');

    regionSelect.addEventListener('change', function () {
        const idRegion = this.value;
        provinciaSelect.innerHTML = '<option value="">Cargando provincias...</option>';
        distritoSelect.innerHTML = '<option value="">Seleccione un Distrito</option>';

        if (idRegion) {
            fetch('/provincias/' + idRegion)
                .then(response => response.json())
                .then(data => {
                    let options = '<option value="">Seleccione una Provincia</option>';
                    data.forEach(provincia => {
                        options += `<option value="${provincia.id_provincia}">${provincia.nombre}</option>`;
                    });
                    provinciaSelect.innerHTML = options;
                });
        } else {
            provinciaSelect.innerHTML = '<option value="">Seleccione una Provincia</option>';
        }
    });

    provinciaSelect.addEventListener('change', function () {
        const idProvincia = this.value;
        distritoSelect.innerHTML = '<option value="">Cargando distritos...</option>';

        if (idProvincia) {
            fetch('/distritos/' + idProvincia)
                .then(response => response.json())
                .then(data => {
                    let options = '<option value="">Seleccione un Distrito</option>';
                    data.forEach(distrito => {
                        options += `<option value="${distrito.id_distrito}">${distrito.nombre}</option>`;
                    });
                    distritoSelect.innerHTML = options;
                });
        } else {
            distritoSelect.innerHTML = '<option value="">Seleccione un Distrito</option>';
        }
    });
});
