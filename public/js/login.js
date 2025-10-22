
/*=============== SHOW HIDE PASSWORD LOGIN ===============*/
const passwordAccess = (loginPass, loginEye) =>{
   const input = document.getElementById(loginPass),
         iconEye = document.getElementById(loginEye)

   iconEye.addEventListener('click', () =>{
      // Change password to text
      input.type === 'password' ? input.type = 'text'
						              : input.type = 'password'

      // Icon change
      iconEye.classList.toggle('ri-eye-fill')
      iconEye.classList.toggle('ri-eye-off-fill')
   })
}
passwordAccess('password','loginPassword')

/*=============== CHANGE LOGO  ===============*/
let mostrarA = true;

setInterval(() => {
  const imgA = document.getElementById("imagenA");
  const imgB = document.getElementById("imagenB");

  if (mostrarA) {
    imgA.classList.remove("mostrar");
    imgB.classList.add("mostrar");
    imgB.style.backgroundColor = "white";
  } else {
    imgB.classList.remove("mostrar");
    imgA.classList.add("mostrar");
    imgA.style.backgroundColor = "hsl(208, 92%, 54%)";
  }

  mostrarA = !mostrarA;
}, 6000);

document.addEventListener("DOMContentLoaded", function () {
   document.querySelectorAll(".error-text").forEach(elem => {
      if (elem.textContent.trim() !== '') {
         setTimeout(() => {
            elem.classList.add("active");
         }, 50);
      }
   });

   const inputs = document.querySelectorAll(".login__input");

   inputs.forEach(input => {
      input.addEventListener("focus", function () {
         const errorContainer = this.closest('.login__box').nextElementSibling;
         if (errorContainer && errorContainer.classList.contains('error-container')) {
            const errorText = errorContainer.querySelector('.error-text');
            if (errorText && errorText.classList.contains('active')) {
               errorText.classList.remove('active');
               errorText.classList.add('exit');

               setTimeout(() => {
                  errorText.innerHTML = '&nbsp;';
                  errorText.classList.remove('exit');
               }, 300);
            }
         }
      });
   });
});