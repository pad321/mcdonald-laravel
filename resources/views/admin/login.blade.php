<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login - McDonald</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    min-height: 100vh;
    background-image: 
        linear-gradient(rgba(10, 5, 0, 0.85), rgba(10, 5, 0, 0.95)),
        url('{{ asset("img/fondo2.png") }}');
    background-size: cover;
    background-position: center;
    font-family: 'Arial', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.login-container {
    width: 100%;
    max-width: 420px;
    background: rgba(25, 15, 10, 0.65);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-radius: 32px;
    padding: 45px 35px;
    box-shadow: 0 30px 70px rgba(0, 0, 0, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.06);
    text-align: center;
    transition: transform 0.3s ease;
}

.login-container:hover {
    transform: translateY(-5px);
}

/* El icónico logo flotante */
.brand-logo {
    font-size: 56px;
    font-weight: 900;
    color: #FFC300;
    font-family: 'Arial Black', sans-serif;
    line-height: 1;
    margin-bottom: 5px;
    text-shadow: 0 4px 15px rgba(255, 195, 0, 0.3);
}

.brand-subtitle {
    color: rgba(255, 195, 0, 0.5);
    font-size: 11px;
    letter-spacing: 5px;
    text-transform: uppercase;
    margin-bottom: 35px;
}

h2 {
    color: white;
    font-size: 24px;
    font-weight: 800;
    margin-bottom: 25px;
    text-align: left;
    padding-left: 5px;
}

.input-group {
    position: relative;
    margin-bottom: 22px;
}

.input-group input {
    width: 100%;
    padding: 16px 20px;
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(255, 255, 255, 0.05);
    color: white;
    font-size: 16px;
    outline: none;
    transition: all 0.3s ease;
}

/* Efecto focus moderno iluminado */
.input-group input:focus {
    border-color: #FFC300;
    background: rgba(255, 255, 255, 0.09);
    box-shadow: 0 0 15px rgba(255, 195, 0, 0.15);
}

.input-group input::placeholder {
    color: rgba(255, 255, 255, 0.35);
}

.btn-submit {
    width: 100%;
    padding: 16px;
    border: none;
    border-radius: 16px;
    background: #FFC300;
    color: #1a0a00;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 8px 20px rgba(255, 195, 0, 0.2);
    margin-top: 10px;
}

.btn-submit:hover {
    background: #FFE066;
    box-shadow: 0 10px 25px rgba(255, 195, 0, 0.35);
    transform: translateY(-2px);
}

.btn-submit:active {
    transform: translateY(0);
}

/* Alertas de error elegantes */
.alert-error {
    background: rgba(233, 30, 99, 0.15);
    border: 1px solid rgba(233, 30, 99, 0.3);
    color: #FF5252;
    padding: 12px;
    border-radius: 14px;
    font-size: 14px;
    margin-bottom: 20px;
    font-weight: 600;
    text-align: left;
}
</style>
</head>
<body>

<div class="login-container">
    <div class="brand-logo">M</div>
    <div class="brand-subtitle">Dashboard</div>
    
    <h2>Panel de Control</h2>

    @if(session('error'))
        <div class="alert-error">
            ⚠ {{ session('error') }}
        </div>
    @endif

    <form action="/admin/login" method="POST">
        @csrf
        <div class="input-group">
            <input 
                type="text" 
                name="usuario" 
                placeholder="Usuario de administrador" 
                required 
                autocomplete="off"
            >
        </div>

        <div class="input-group">
            <input 
                type="password" 
                name="clave" 
                placeholder="Contraseña" 
                required
            >
        </div>

        <button type="submit" class="btn-submit">
            Ingresar al Panel
        </button>
    </form>
</div>

</body>
</html>