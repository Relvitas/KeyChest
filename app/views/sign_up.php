<main>
    <div class="container-sign_up">
        <?= alert($info)?>
        <div class="form-sign_up">
            <div class="form">
                <h1>Registrarse</h1>
                <form action="?c=user&a=sign_up" method="post">
                    <div class="input">
                        <input type="text" id="username" name="username" placeholder="Nombre" required autofocus>
                    </div>
                    <div class="input">
                        <input type="email" id="email" name="email" placeholder="Correo" required>
                    </div>
                    <div class="input">
                        <input type="password" id="password" name="password" placeholder="Contraseña" required>
                    </div>
                    <div>
                        <button class="form-button" type="submit" name="sign_up">Regístrate</button>
                        <a href="?v=sign_in"><button class="form-button" type="button">Ingresa</button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>