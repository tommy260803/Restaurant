
// Función para expandir/colapsar un menú con animación
const customToggleDropdown = (dropdown, menu, isOpen) => {
    dropdown.classList.toggle("open", isOpen);

    if (isOpen) {
        // Medimos la altura real del contenido
        const height = menu.scrollHeight + "px";
        menu.style.height = height;
        menu.style.overflow = "hidden";

        // Después de que termine la transición, dejar height en auto
        const onTransitionEnd = () => {
            menu.style.height = "auto";
            menu.removeEventListener("transitionend", onTransitionEnd);
        };
        menu.addEventListener("transitionend", onTransitionEnd);
    } else {
        // Para cerrar: ponemos la altura actual, luego la colapsamos a 0
        const height = menu.scrollHeight + "px";
        menu.style.height = height;

        // Forzar reflow antes de colapsar
        void menu.offsetHeight;

        menu.style.height = "0px";
        menu.style.overflow = "hidden";
    }
};

// Cierra todos los dropdowns abiertos del mismo nivel
const customCloseDropdownsOfSameLevel = (currentDropdown, parent) => {
    const selector = parent === null
        ? document.querySelectorAll(".custom-sidebar > nav .custom-dropdown-container.open")
        : parent.querySelectorAll(".custom-dropdown-container.open");

    selector.forEach(el => {
        if (el !== currentDropdown) {
            const menu = el.querySelector(".custom-dropdown-menu");
            customToggleDropdown(el, menu, false);
        }
    });
};

// Maneja clicks en toggles de dropdown
document.querySelectorAll(".custom-dropdown-toggle").forEach((toggle) => {
    toggle.addEventListener("click", (e) => {
        e.preventDefault();

        const dropdown = toggle.closest(".custom-dropdown-container");
        const menu = dropdown.querySelector(".custom-dropdown-menu");
        const isOpen = dropdown.classList.contains("open");

        const isPrimary = toggle.classList.contains("primario");
        const parent = isPrimary ? null : dropdown.parentElement;

        // Cierra el otro menú primario si está abierto (Actas vs Administración)
        if (isPrimary) {
            document.querySelectorAll(".custom-dropdown-toggle.primario").forEach(otherToggle => {
                const otherDropdown = otherToggle.closest(".custom-dropdown-container");
                if (otherDropdown !== dropdown && otherDropdown.classList.contains("open")) {
                    const otherMenu = otherDropdown.querySelector(".custom-dropdown-menu");
                    customToggleDropdown(otherDropdown, otherMenu, false);
                }
            });
        }

        // Cierra los dropdowns del mismo nivel y luego abre/cierra el actual
        customCloseDropdownsOfSameLevel(dropdown, parent);
        customToggleDropdown(dropdown, menu, !isOpen);
    });
});

// Maneja toggle de sidebar colapsable
document.querySelectorAll(".hamburger-click").forEach((button) => {
    button.addEventListener("click", () => {
        document.querySelector(".custom-sidebar").classList.toggle("collapsed");
        document.querySelector(".custom-main-content").classList.toggle("collapsed");
    });
});

const hamburger_click = document.getElementById('hamburger-click');

// En pantallas pequeñas colapsa sidebar por defecto
if (window.innerWidth <= 1024) {
    document.querySelector(".custom-sidebar")?.classList.add("collapsed");
    document.querySelector(".custom-main-content")?.classList.add("collapsed");
}

const sidebar = document.getElementById("customSidebar");
const hamburger = document.querySelector(".hamburger-click");
const mainContent = document.querySelector(".custom-main-content");

let clickListenerAdded = false;

const handleClickOutside = (event) => {
    const clickedInsideSidebar = sidebar.contains(event.target);
    const clickedOnHamburger = hamburger.contains(event.target);

    if (!clickedInsideSidebar && !clickedOnHamburger) {
        sidebar.classList.add("collapsed");
        mainContent?.classList.add("collapsed");
        hamburger_click.classList.remove("active");
    }
};

const updateClickListener = () => {
    if (window.innerWidth <= 1024) {
        if (!clickListenerAdded) {
            document.addEventListener("click", handleClickOutside);
            clickListenerAdded = true;
        }
    } else {
        if (clickListenerAdded) {
            document.removeEventListener("click", handleClickOutside);
            clickListenerAdded = false;
        }
    }
};

updateClickListener();
window.addEventListener("resize", updateClickListener);