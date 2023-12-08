<main>
    <div>
        <h1>Registrarse</h1>
    </div>
    <div>
        <form action="?c=user&a=sign_up" method="post">
            <div>
                <label for="username">Nombre</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div>
                <label for="email">Correo</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit" name="sign_up">Registrate</button>
            </div>
        </form>
        <a href="?v=sign_in"><button type="button">Ingresa</button></a>
    </div>
</main>