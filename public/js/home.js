

document.addEventListener('DOMContentLoaded', () => {
    // Mobile toggle
    document.getElementById('mobileToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('open');
    });

    // Location selectors
    const els = ['Region_defuncion', 'Provincia_defuncion', 'Distrito_defuncion'].map(id => document
        .getElementById(id)).filter(Boolean);
    if (els.length) {
        let data = [],
            savedValues = ['region_actual', 'provincia_actual', 'distrito_actual'].map(id => document
                .getElementById(id)?.value || '');

        Promise.all(['/json/departamento.json', '/json/provincia.json', '/json/distrito.json'].map(url => fetch(url).then(
            r => r.json())))
            .then(result => {
                data = result;
                populateSelect(els[0], data[0], 'Seleccione región');
                els[0].onchange = () => handleChange(0);
                if (els[1]) els[1].onchange = () => handleChange(1);
                if (savedValues[0]) setTimeout(restoreValues, 100);
            });

        function populateSelect(select, options, placeholder) {
            select.innerHTML = < option value="" > $ {
                placeholder
            } </option> +
                options.map(opt => < option value="${opt.name}"
                    data-id="${opt.id}" >${
                        opt.name
                    } </option >).join('');
            select.disabled = false;
        }

        function handleChange(level) {
            const id = els[level].selectedOptions[0]?.dataset.id;
            if (id && level < 2) {
                const key = level === 0 ? 'department_id' : 'province_id';
                populateSelect(els[level + 1], data[level + 1].filter(x => x[key] === id), level === 0 ?
                    'Seleccione provincia' : 'Seleccione distrito');
            }
            if (level === 0 && els[2]) {
                els[2].innerHTML = '<option value="">Seleccione distrito</option>';
                els[2].disabled = true;
            }
        }

        function restoreValues() {
            if (savedValues[0]) {
                els[0].value = savedValues[0];
                const dept = data[0].find(d => d.name === savedValues[0]);
                if (dept) {
                    const provs = data[1].filter(p => p.department_id === dept.id);
                    populateSelect(els[1], provs, 'Seleccione provincia');
                    if (savedValues[1]) {
                        setTimeout(() => {
                            els[1].value = savedValues[1];
                            const prov = provs.find(p => p.name === savedValues[1]);
                            if (prov) {
                                populateSelect(els[2], data[2].filter(d => d.province_id ===
                                    prov.id), 'Seleccione distrito');
                                if (savedValues[2]) setTimeout(() => els[2].value = savedValues[
                                    2], 50);
                            }
                        }, 50);
                    }
                }
            }
        }
    }
});

// Modal selectors & PDF viewer & Modal search
document.addEventListener('click', e => {
    const match = ['fallecido', 'registrador', 'alcalde'].find(type => e.target.classList.contains(`seleccionar - ${type}`));
    if (match) {
        const key = match === 'fallecido' ? 'dni' : 'id';
        const idField = match === 'fallecido' ? 'DNI_fallecido' :
            `Id${match.charAt(0).toUpperCase() + match.slice(1)}`;
        document.getElementById(idField).value = e.target.dataset[key];
        document.getElementById(`Nombre$ {match.charAt(0).toUpperCase() + match.slice(1)}`).value = e.target.dataset.nombre;
        bootstrap.Modal.getInstance(document.getElementById(`${match}Modal`))?.hide();
    }
});

document.getElementById('archivo_pdf')?.addEventListener('change', e => {
    const file = e.target.files[0];
    if (file?.type === 'application/pdf') {
        document.getElementById('nombre-archivo').textContent = file.name;
        document.getElementById('tamano-archivo').textContent = (file.size / 1024 / 1024)
            .toFixed(2) + ' MB';
        document.getElementById('info-archivo')?.classList.remove('d-none');
        document.getElementById('visor-pdf').src = URL.createObjectURL(file);
        document.getElementById('visor-container').style.display = 'block';
    }
});

['fallecido', 'registrador', 'alcalde'].forEach(type => {
    const modal = document.getElementById(`${type}Modal`);
    if (modal) {
        modal.querySelector('.modal-body').insertAdjacentHTML('afterbegin', `
            <div class="search-box">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search" ></i></span>
                    <input type="text" class="form-control" placeholder="Buscar..." id="search-${type}" >
                </div>
            </div >`
        );
        const input = document.getElementById(`search - $ { type }`);
        input.addEventListener('input', e => {
            const query = e.target.value.toLowerCase();
            modal.querySelectorAll([data - nombre], [data - dni]).forEach(item => {
                const matches = !query || (item.dataset.nombre || '')
                    .toLowerCase().includes(query) || (item.dataset.dni ||
                        '').toLowerCase().includes(query);
                (item.closest('tr') || item).style.display = matches ? '' :
                    'none';
            });
        });
        modal.addEventListener('shown.bs.modal', () => input.value = '');
    }
});
