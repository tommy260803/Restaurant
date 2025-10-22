/*============= Asocia el click del ícono al input para abrir el calendario ============*/
const iconoCalendario = document.getElementById('iconoCalendario');
const inputFecha = document.getElementById('fechaNacimiento');

iconoCalendario.addEventListener('click', () => {
  inputFecha.showPicker();
  inputFecha.focus();
});

/*============= Cambia de formulario según el tipo de acta ============*/
document.addEventListener("DOMContentLoaded", () => {

   const radios = document.querySelectorAll('input[name="grupo1"]');

   radios.forEach(radio => {
   radio.addEventListener("change", () => {
      document.querySelectorAll(".login__form").forEach(form => {
         form.classList.add("hidden");
      });

      const selectedForm = document.getElementById(radio.value);

      if (selectedForm) {
         selectedForm.classList.remove("hidden");
      }
   });
   });
});

document.addEventListener("DOMContentLoaded", () => {
    const buscarBtn = document.getElementById("buscarActa");

    buscarBtn.addEventListener("click", (e) => {
        e.preventDefault();

        const forms = document.querySelectorAll(".login__form");
        let formToSend = null;

        forms.forEach(form => {
            if (!form.classList.contains("hidden")) {
                formToSend = form;
            }
        });

        if (formToSend) {
            const formData = new FormData(formToSend);

            let endpoint = "";
            if (formToSend.id === "nacimiento") {
                endpoint = "/buscar/nacimiento";
            } else if (formToSend.id === "matrimonio") {
                endpoint = "/buscar/matrimonio";
            } else if (formToSend.id === "defuncion") {
                endpoint = "/buscar/defuncion";
            }

            // CSRF Token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(endpoint, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                body: formData
            })
            .then(response => response.text()) // aquí cambiamos a .text() para recibir texto plano
            .then(message => {
                alert(message); // Muestra la alerta con el mensaje devuelto por el servidor
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }
    });
});
