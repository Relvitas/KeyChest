<?php
/*
Esta función permite realizar paginación.
Recibe dos argumentos: $page es la página seleccionada
por el usuario (1 por defecto), y $total_pages es el total 
de páginas posibles a mostrar.
*/
function paginator($page, $total_pages, $path) {
    // Variable contenedora de paginado
    $configure_paging = '';

    /*
    Establecer configuración inicial del paginado en
    caso de que $total_page <=3
    */
    if (($page > 0 && $page <= 3) && $total_pages <= 3) {

        for ($i = 1; $i <= $total_pages; $i ++) {
            
            if (!($i == 1 && $total_pages == 1)) {
                $configure_paging .= '<a class="page-number" href="' . $path . $i . '">' . $i . '</a>' . ' ';
            }
        }
    
    /*
    // Establecer configuración inicial del paginado en
    caso de que $total_pages > 3
    */
    } elseif (($page > 0 && $page < 3) && $total_pages > 3) {
        
        for ($i = 1; $i <= 3; $i++ ) {
            $configure_paging .= '<a class="page-number" href="' . $path . $i . '">' . $i . '</a>' . ' '; 
        }
        $configure_paging .='<a class="next-page" href="' . $path . ($page + 1) . '">Siguiente &gt;</a>';

    // Configuración de paginación
    } elseif ($page > 2 && $page <= $total_pages) {
        
        // Se encarga de mantener los tres últimos resultados
        if ($page == $total_pages) {

            $configure_paging .= '<a class="previous-page" href="' . $path . ($page - 1) . '">&lt; Anterior</a>' . ' ';
            for ($i = $page -2; $i <= $total_pages; $i++) {
                $configure_paging .= '<a class="page-number" href="' . $path . $i . '">' . $i . '</a>' . ' '; 
            }

        // Se encarga de mantener los tres últimos resultados
        } elseif ($page + 1 == $total_pages) {
    
            $configure_paging .= '<a class="previous-page" href="' . $path . ($page - 1) . '">&lt; Anterior</a>' . ' ';
            for ($i = $page -1; $i <= $total_pages; $i++) {
                $configure_paging .= '<a class="page-number" href="' . $path . $i . '">' . $i . '</a>' . ' '; 
            }

        // Se encarga de mantener los tres últimos resultados
        } elseif ($page + 2 == $total_pages) {
    
            $configure_paging .= '<a class="previous-page" href="' . $path . ($page - 1) . '">&lt; Anterior</a>' . ' ';
            for ($i = $page; $i <= $total_pages; $i++) {
                $configure_paging .= '<a class="page-number" href="' . $path . $i . '">' . $i . '</a>' . ' '; 
            }
        
        // Se encarga de mostrar paginación completa
        } else {

            $configure_paging .= '<a class="previous-page" href="' . $path . ($page - 1) . '">&lt; Anterior</a>' . ' ';
            for ($i = 1; $i <= 3; $i++) {
                $configure_paging .= '<a class="page-number" href="' . $path . $i . '">' . $page++ . '</a>' . ' '; 
            }
            $configure_paging .='<a class="next-page" href="' . $path . ($page - 2) . '">Siguiente &gt;</a>';

        }
    }

    return $configure_paging;
}