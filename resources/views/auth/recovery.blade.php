<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperación de Cuenta</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    
    <div class="bg-white rounded-[35px] shadow-2xl w-full h-auto xl:h-[370px] mx-16 grid sm:mx-20 
      md:mx-40 lg:mx-60 xl:grid-cols-2 text-white relative overflow-hidden">
      {{-- Loader visible solo dentro del div --}}
      <div id="googleLoader" class="loader-wrapper" style="display: none;">
        <div class="loader-bar">
          <div class="loader-progress"></div>
        </div>
      </div>
      @yield('contenido')
    </div>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const loader = document.getElementById("googleLoader");

        document.querySelectorAll("form").forEach(form => {
          form.addEventListener("submit", function () {
            if (loader) {
              loader.style.display = "block";
            }
          });
        });
      });

      window.addEventListener("load", function () {
        const loader = document.getElementById("googleLoader");
        if (loader) {
          setTimeout(() => {
            loader.style.display = "none";
          }, 500);
        }
      });
    </script>
</body>
</html>