document.addEventListener('DOMContentLoaded', function(){
    
   eventListeners();

   darkMode();
});

function darkMode() {
    
    const prefiereDarkmode = window.matchMedia('(prefers-color-scheme: dark)');
    // console.log(prefiereDarkmode.matches);
    if(prefiereDarkmode.matches) {
        document.body.classList.add('dark-mode');
    }else{
        document.body.classList.remove('dark-mode');
    }

    prefiereDarkmode.addEventListener('change', function(){
        if(prefiereDarkmode.matches) {
            document.body.classList.add('dark-mode');
        }else{
            document.body.classList.remove('dark-mode');
        }
    });

    const botonDarkMode = document.querySelector('.dark-mode-boton');
    
    botonDarkMode.addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
    });
}

function eventListeners() {
    const mobileMenu = document.querySelector('.mobile-menu');

    mobileMenu.addEventListener('click', navegacionResponsive);

    //  muestra campos condicionales
    const metodoContacto = document.querySelectorAll('input[name="contacto[contacto]"]');

    metodoContacto.forEach(input => input.addEventListener('click', mostrarMetodosContacto)); //
}

function navegacionResponsive() {
    const navegacion = document.querySelector('.navegacion');

    navegacion.classList.toggle('mostrar')
} 

function mostrarMetodosContacto(e) {
    const contactoDiv = document.querySelector('#contacto');

    if( e.target.value === 'telefono' ) {
    contactoDiv.innerHTML = `
        <label for="telefono">Numero de Telefono</label>
        <input type="tel" placeholder="Tu Telefono" id="telefono"name="contacto[telefono]">

        <p>Elija la fecha y la hora para la llamada</p>
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha"name="contacto[fecha]">

            <label for="hora">Hora</label>
            <input type="time" id="hora" min="09:00" max="18:00" name="contacto[hora]">
    `;
    } else {
        contactoDiv.innerHTML = `
        
            <label for="email">Tu Email</label>
            <input type="text" placeholder="Tu Email" id="email" name="contacto[email]">
            `;
    }
}
