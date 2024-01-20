<main>
    <div class="container-alert">
        <?= alert($info)?>
    </div>
    <div class="container-new_record">
        <span>Nuevo Registro</span>
        <form class="form-new_record" action="?c=record&a=save_record" method="post">
            <div class="input">
                <input class="input-new-record" type="text" name="website" id="website" placeholder="Sitio Web" autofocus required>
            </div>
            <div class="input">
                <input class="input-new-record" type="text" name="username" id="username" placeholder="Nombre Usuario">
            </div>
            <div class="input">
                <input class="input-new-record" type="email" name="email" id="email" placeholder="Correo">
            </div>
            <div class="input">
                <input class="input-new-record" type="tel" name="tel" id="tel" placeholder="Teléfono">
            </div>
            <div class="input">
                <input class="input-new-record" type="password" name="password" id="password" placeholder="Contraseña" required>
            </div>
            <div class="input">
                <textarea name="recoveryKey" id="recoveryKey" cols="21" rows="5" placeholder="Clave recuperación"></textarea>
            </div>
            <div class="button-group-new-record">
                <button class="new-record-button" type="submit" name="save_record">Guardar</button>
            </div>
        </form>
    </div>
</main>