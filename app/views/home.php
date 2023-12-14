<main>
    <div>
        <div>
            <h1>Nuevo Registro</h1>
        </div>
        <form action="?c=home&a=save_record" method="post">
            <div>
                <label for="website">Sitio Web</label>
                <input type="text" name="website" id="website" required>
            </div>
            <div>
                <label for="username">Nombre Usuario</label>
                <input type="text" name="username" id="username">
            </div>
            <div>
                <label for="email">Correo</label>
                <input type="email" name="email" id="email">
            </div>
            <div>
                <label for="tel">Teléfono</label>
                <input type="tel" name="tel" id="tel">
            </div>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div>
                <label for="recoveryKey">Clave de Recuperación</label>
                <textarea name="recoveryKey" id="recoveryKey" cols="20" rows="5"></textarea>
            </div>
            <div>
                <button type="submit" name="save_record">Guardar</button>
            </div>
        </form>
    </div>
</main>