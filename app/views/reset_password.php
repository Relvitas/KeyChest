<main>
    <div>
        <h1>Restablecer Contraseña</h1>
        <form action="?c=user&a=reset_password" method="post">
            <div>
                <label for="email">Correo</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>
            <div>
                <button type="submit" name="reset_password">Restablecer</button>
            </div>
        </form>
        <a href="?v=sign_in"><button type="button">Ingresa</button></a>
    </div>
</main>