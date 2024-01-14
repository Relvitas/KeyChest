<main>
    <div class="container-login">
        <?= alert($info)?>
        <div class="form-login">
            <div class="form">
                <h1>Iniciar Sesión</h1>
                <form action="?a=sign_in" method="post">
                    <div class="input">
                        <input type="email" name="email" placeholder="Correo" required autofocus>
                    </div>
                    <div class="input">
                        <input type="password" name="password" placeholder="Contraseña" required>
                        <a href="?v=reset_password">Olvide Mi Contraseña</a>
                    </div>
                        <button class="form-button" type="submit" name="sign_in">Ingresa</button>
                        <a href="?v=sign_up"><button class="form-button" type="button">Registrate</button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>