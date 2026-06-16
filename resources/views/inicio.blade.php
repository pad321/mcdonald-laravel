<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inicio - McDonald's</title>

<script>localStorage.removeItem("carrito");</script>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background-image:url('{{ asset('img/fondo2.png') }}');
    background-size:cover;
    background-position:center;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:Arial,sans-serif;
}

.tarjeta{
    width:390px;
    height:780px;
    border-radius:40px;
    overflow:hidden;
    position:relative;
    box-shadow:0 40px 80px rgba(0,0,0,0.7);
    background:#0f0500;
}

.emoji-grid{
    position:absolute;
    inset:0;
    display:grid;
    grid-template-columns:repeat(5,78px);
    grid-template-rows:repeat(9,86px);
    opacity:.15;
    pointer-events:none;
}

.emoji-grid span{
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    filter:saturate(0) brightness(1.4);
}

.gradiente{
    position:absolute;
    inset:0;
    background:linear-gradient(to bottom,rgba(10,4,0,0) 0%,rgba(10,4,0,0) 25%,rgba(10,4,0,.9) 55%,rgba(10,4,0,1) 100%);
}

.formulario{
    position:absolute;
    bottom:0;
    left:0;
    right:0;
    padding:0 32px 44px;
    display:flex;
    flex-direction:column;
}

.logo{
    font-size:52px;
    font-weight:900;
    color:#FFC300;
    line-height:1;
    margin-bottom:4px;
    font-family:Arial Black,Arial,sans-serif;
}

.tagline{
    font-size:11px;
    color:rgba(255,195,0,.55);
    letter-spacing:3.5px;
    text-transform:uppercase;
    margin-bottom:28px;
}

.titulo{
    font-size:34px;
    font-weight:800;
    color:#fff;
    line-height:1.15;
    margin-bottom:8px;
}

.subtitulo{
    font-size:15px;
    color:rgba(255,255,255,.45);
    margin-bottom:28px;
}

.campo{
    width:100%;
    padding:16px 20px;
    border-radius:16px;
    border:1px solid rgba(255,255,255,.12);
    background:rgba(255,255,255,.08);
    color:#fff;
    font-size:16px;
    outline:none;
    margin-bottom:12px;
}

.campo:focus{
    border-color:rgba(255,195,0,.6);
    background:rgba(255,255,255,.12);
}

.boton{
    width:100%;
    padding:17px;
    border-radius:16px;
    border:none;
    background:#FFC300;
    color:#1a0a00;
    font-size:17px;
    font-weight:800;
    cursor:pointer;
}

.boton-admin{
    margin-top:10px;
    background:transparent;
    color:#FFC300;
    border:1px solid rgba(255,195,0,.45);
}

.boton:hover{
    background:#ffb000;
}

.boton-admin:hover{
    background:rgba(255,195,0,.12);
}

.dots{
    display:flex;
    justify-content:center;
    gap:6px;
    margin-top:22px;
}

.dot{
    width:6px;
    height:6px;
    border-radius:50%;
    background:rgba(255,255,255,.2);
}

.dot.activo{
    width:20px;
    border-radius:3px;
    background:#FFC300;
}

@media(max-width:480px){
    body{
        padding:0;
    }

    .tarjeta{
        width:100%;
        height:100vh;
        border-radius:0;
    }
}
</style>
</head>

<script>
document.getElementById('formInicio').addEventListener('submit', function(e){

    const nombre = document.getElementById('Nombre').value.trim();

    if(!/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/.test(nombre)){
        e.preventDefault();
        alert('Solo se permiten letras');
    }

});
</script>

<body>

<div class="tarjeta">
    <div class="emoji-grid" id="emojiGrid"></div>
    <div class="gradiente"></div>

    <form class="formulario" id="formInicio" action="/menu" method="POST">
        @csrf

        <div class="logo">M</div>
        <div class="tagline">Me encanta</div>

        <div class="titulo">¡Hola!<br>Bienvenido</div>
        <div class="subtitulo">Ingresa tu nombre para comenzar</div>

        <input
            class="campo"
            type="text"
            name="Nombre"
            id="Nombre"
            placeholder="¿Cómo te llamas?"
            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+"
            title="Solo se permiten letras"
            required>

        <button type="submit" class="boton">Continuar →</button>
        <button type="button" class="boton boton-admin" id="btnAdmin">Ingresar como administrador</button>

        <div class="dots">
            <div class="dot activo"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </form>
</div>

<script>
const emojis = ['🍔','🍟','🥤','🍦','🥗','🍗','🧁','☕'];
const grid = document.getElementById('emojiGrid');

for(let i = 0; i < 45; i++){
    const s = document.createElement('span');
    s.textContent = emojis[i % emojis.length];
    grid.appendChild(s);
}

document.getElementById('btnAdmin').addEventListener('click', function(){
    window.location.href = '/admin/login';
});
</script>

</body>
</html>