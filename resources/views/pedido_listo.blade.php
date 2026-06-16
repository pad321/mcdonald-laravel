<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pedido listo</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    min-height:100vh;
    background-image:
        linear-gradient(rgba(15,5,0,0.82), rgba(15,5,0,0.92)),
        url('{{ asset('img/fondo2.png') }}');
    background-size:cover;
    background-position:center;
    font-family:Arial, sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
}

.contenedor{
    width:520px;
    background:rgba(15,5,0,0.94);
    border-radius:36px;
    padding:50px;
    text-align:center;
    color:white;
    box-shadow:0 40px 90px rgba(0,0,0,0.75);
}

.icono{
    width:110px;
    height:110px;
    background:#FFC300;
    color:#1a0a00;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:60px;
    font-weight:900;
    margin:0 auto 28px;
}

h1{
    font-size:42px;
    margin-bottom:18px;
}

p{
    color:rgba(255,255,255,0.7);
    font-size:20px;
    line-height:1.5;
    margin-bottom:30px;
}

b{
    color:#FFC300;
}

button{
    width:100%;
    padding:18px;
    border:none;
    border-radius:20px;
    background:#FFC300;
    color:#1a0a00;
    font-size:18px;
    font-weight:900;
    cursor:pointer;
}

button:hover{
    background:#ffb000;
}

a{
    text-decoration:none;
}

</style>
</head>

<body>

<div class="contenedor">

    <div class="icono">🔔</div>

    <h1>¡Tu pedido está listo!</h1>

    <p>
        Hola, <b>{{ session('Nombre') }}</b><br>
        Puedes acercarte a recoger tu pedido.
    </p>

    <a href="/">

        <button>
            Finalizar
        </button>

    </a>

</div>

</body>
</html>