<main>
    <div class="container-new_password">
        <?= alert($info)?>
        <div class="form-new_password">
            <div class="form">
                <h1>Nueva Contraseña</h1>
                <form action="?a=set_password" method="post">
                    <div class="input">
                        <input type="password" id="password" name="password" placeholder="Nueva Contraseña" required autofocus>
                    </div>
                    <div class="input">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmar Contraseña" required>
                    </div>
                    <div>
                        <input type="hidden" name="idUser" value=<?= '"' . $info['idUser'] . '"' ?>>
                        <input type="hidden" name="token" value=<?= '"' . $info['token'] . '"' ?>>
                    </div>
                    <div>
                        <button class="form-button" type="submit" name="set_password">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>