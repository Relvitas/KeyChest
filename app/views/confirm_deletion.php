<main>
    <div class="container-confirm_deletion">
        <i class="fa-solid fa-bell"></i>
        <p class="message_confirm">
            Por favor, confirme que esta seguro    
            de la eliminación del registro.
        </p>
        <div class="buttons_confirm_deletion">
            <a href="?c=record&a=delete_record&id_record=<?= $info?>"title="eliminar registro">Eliminar</a>
            <a href="?c=record&a=list_record" title="cancelar proceso">Cancelar</a>
        </div>
    </div>
</main>