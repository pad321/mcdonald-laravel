<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login Admin</title>
<style>
body{
    background:#120600;
    color:white;
    font-family:Arial,sans-serif;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login{
    width:380px;
    background:#2a1200;
    padding:34px;
    border-radius:24px;
}

h1{
    color:#FFC300;
    margin-bottom:20px;
}

input{
    width:100%;
    padding:15px;
    margin-bottom:14px;
    border-radius:14px;
    border:none;
    outline:none;
}

button{
    width:100%;
    padding:15px;
    border:none;
    border-radius:14px;
    background:#FFC300;
    font-weight:900;
    cursor:pointer;
}

.error{
    color:#ff6b6b;
    margin-bottom:12px;
}
</style>
</head>
<body>

<form class="login" method="POST" action="/admin/login">
    @csrf

    <h1>Admin</h1>

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <input type="text" name="usuario" placeholder="Usuario" required>
    <input type="password" name="clave" placeholder="Contraseña" required>

    <button type="submit">Ingresar</button>
</form>

</body>
</html>