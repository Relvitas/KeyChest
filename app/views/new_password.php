<main>
    <div>
        <h1>Nueva Contraseña</h1>
        <form action="?a=set_password" method="post">
            <div>
                <label for="password">Nueva Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="confirm_password">Confirmar Contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div>
                <input type="hidden" name="idUser" value=<?= '"' . $info['idUser'] . '"' ?>>
                <input type="hidden" name="token" value=<?= '"' . $info['token'] . '"' ?>>
            </div>
            <div>
                <button type="submit" name="set_password">Guardar</button>
            </div>
        </form>
    </div>
</main>