<main>
    <div>
        <h1>Iniciar Sesión</h1>
        <form action="?a=sign_in" method="post">
            <div>
                <label for="email">Correo</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
                <a href="?v=reset_password">Olvide Mi Contraseña</a>
            </div>
            <div>
                <button type="submit" name="sign_in">Ingresa</button>
            </div>
        </form>
        <a href="?v=sign_up"><button type="button">Registrate</button></a>
    </div>
</main>