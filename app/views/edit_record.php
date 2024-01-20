<main>
    <div class="container-edit_record">
        <span>Editar Registro</span>
        <form class="form-edit_record" action="?c=record&a=update_record" method="post">
            <div class="input">
                <label for="website">Sitio Web</label>
                <input class="input-edit-record" type="text" name="website" id="website" value="<?= $info->sitio_web?>" required>
                <input type="hidden" name="id_website" value="<?= $info->id_portal_registro?>">
            </div>
            <div class="input">
                <label for="username">Nombre Usuario</label>
                <input class="input-edit-record" type="text" name="username" id="username" value="<?= $info->nombre_usuario?>" placeholder="Nombre usuario">
                <input type="hidden" name="id_username" value="<?= $info->id_nombre_registro?>">
            </div>
            <div class="input">
                <label for="email">Correo</label>
                <input class="input-edit-record" type="email" name="email" id="email" value="<?= $info->correo ?>" placeholder="Correo">
                <input type="hidden" name="id_email" value="<?= $info->id_correo_registro?>">
            </div>
            <div class="input">
                <label for="tel">Teléfono</label>
                <input class="input-edit-record" type="tel" name="tel" id="tel" value="<?= $info->telefono ?>" placeholder="Teléfono">
                <input type="hidden" name="id_tel" value="<?= $info->id_telefono_registro?>">
            </div>
            <div class="input">
                <label for="password">Contraseña</label>
                <input class="input-edit-record" type="text" name="password" id="password" value="<?= $info->contrasenia?>" required>
            </div>
            <div class="input">
                <label  for="recoveryKey">Clave de Recuperación</label>
                <textarea name="recoveryKey" id="recoveryKey" cols="20" rows="5" placeholder="Clave recuperación"><?= $info->clave_recuperacion?></textarea>
            </div>
            <div class="button-group">
                <button class="edit-record-button" type="submit" name="save_record">Guardar</button>
                <a href="?c=record&a=list_record"><button class="edit-record-button-2" type="button">Cancelar</button></a>
                <input type="hidden" name="id_record" value="<?= $info->id_registro?>">
            </div>
            
        </form>
        <div>
                
            </div>
    </div>
</main>