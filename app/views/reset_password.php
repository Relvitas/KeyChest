<main>
    <div class="container-reset_password">
        <?= alert($info)?>
        <div class="form-reset_password">
            <div class="form">
                <h1>Restablecer</h1>
                <form action="?c=user&a=reset_password" method="post">
                    <div class="input">
                        <input type="email" id="email" name="email" placeholder="Correo" required autofocus>
                    </div>
                    <div>
                        <button class="form-button" type="submit" name="reset_password">Restablecer</button>
                        <a href="?v=sign_in"><button class="form-button" type="button">Ingresa</button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>