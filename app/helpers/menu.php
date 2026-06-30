<?php
    //funcion para configurar el menu

    function configurar_menu_panel($rol_actual = NULL)
    {
        $menu = array();
        $menu_item = array();
        $sub_menu_item = array();
        /*
        |--------------------------------------------------------------------------
        | SESION ACTUAL
        |--------------------------------------------------------------------------
        */
        $rol_actual = $_SESSION['rol_actual'] ?? null;
        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */
        $menu_item['is_active'] = FALSE;
        $menu_item['href'] = BASE_URL;
        $menu_item['icon'] = 'bi bi-speedometer2';
        $menu_item['text'] = 'Dashboard';
        $menu_item['submenu'] = array();
        $menu['dashboard'] = $menu_item;
        /*
        |--------------------------------------------------------------------------
        | Usuarios
        |--------------------------------------------------------------------------
        */
        if ($rol_actual == 1) {
            $menu_item['is_active'] = FALSE;
            $menu_item['href'] = BASE_URL . 'usuarios';
            $menu_item['icon'] = 'bi bi-people';
            $menu_item['text'] = 'Usuarios';
            $menu_item['submenu'] = array();
            $menu['usuarios'] = $menu_item;
        }
        /*
        |--------------------------------------------------------------------------
        | Cotizaciones
        |--------------------------------------------------------------------------
        */
        $menu_item['is_active'] = FALSE;
        $menu_item['href'] = '#';
        $menu_item['icon'] = 'bi bi-file-earmark-text';
        $menu_item['text'] = 'Cotizaciones';
        $menu_item['submenu'] = array(
            array(
                'is_active' => FALSE,
                'href' => BASE_URL . 'cotizaciones/2026',
                'icon' => 'bi bi-calendar3',
                'text' => '2026'
            ),
            array(
                'is_active' => FALSE,
                'href' => BASE_URL . 'cotizaciones/2025',
                'icon' => 'bi bi-calendar3',
                'text' => '2025'
            )
        );
        $menu['cotizaciones'] = $menu_item;
        /*
        |--------------------------------------------------------------------------
        | Adjudicados
        |--------------------------------------------------------------------------
        */
        $menu_item['is_active'] = FALSE;
        $menu_item['href'] = '#';
        $menu_item['icon'] = 'bi bi-patch-check';
        $menu_item['text'] = 'Adjudicados';
        $menu_item['submenu'] = array(
            array(
                'is_active' => FALSE,
                'href' => BASE_URL . 'adjudicados/2026',
                'icon' => 'bi bi-calendar3',
                'text' => '2026'
            ),
            array(
                'is_active' => FALSE,
                'href' => BASE_URL . 'adjudicados/2025',
                'icon' => 'bi bi-calendar3',
                'text' => '2025'
            )
        );
        $menu['adjudicados'] = $menu_item;
        /*
        |--------------------------------------------------------------------------
        | Servicios
        |--------------------------------------------------------------------------
        */
        $menu_item['is_active'] = FALSE;
        $menu_item['href'] = '#';
        $menu_item['icon'] = 'bi bi-tools';
        $menu_item['text'] = 'Servicios';
        $menu_item['submenu'] = array(
            array(
                'is_active' => FALSE,
                'href' => BASE_URL . 'servicios/2026',
                'icon' => 'bi bi-calendar3',
                'text' => '2026'
            ),
            array(
                'is_active' => FALSE,
                'href' => BASE_URL . 'servicios/2025',
                'icon' => 'bi bi-calendar3',
                'text' => '2025'
            )
        );
        $menu['servicios'] = $menu_item;
        /*
        |--------------------------------------------------------------------------
        | Proveedores
        |--------------------------------------------------------------------------
        */
        $menu_item['is_active'] = FALSE;
        $menu_item['href'] = BASE_URL . 'proveedores';
        $menu_item['icon'] = 'bi bi-truck';
        $menu_item['text'] = 'Proveedores';
        $menu_item['submenu'] = array();
        $menu['proveedores'] = $menu_item;
        /*
        |--------------------------------------------------------------------------
        | Contactos
        |--------------------------------------------------------------------------
        */
        $menu_item['is_active'] = FALSE;
        $menu_item['href'] = BASE_URL . 'contactos';
        $menu_item['icon'] = 'bi bi-people';
        $menu_item['text'] = 'Contactos';
        $menu_item['submenu'] = array();
        $menu['contactos'] = $menu_item;
        return $menu;
    }



   //==========================================
    //Función para activar una opción del menú
    //==========================================
    function activar_menu_item_panel($menu = NULL, $tarea_actual = NULL)
    {

        switch ($tarea_actual) {

            //SECCIÓN DASHBOARD
            case TAREA_DASHBOARD:
                $menu['dashboard']['is_active'] = TRUE;
            break;


            //SECCIÓN OPERACIONES
            case TAREA_COTIZACIONES:
                $menu['cotizaciones']['is_active'] = TRUE;
            break;


            case TAREA_ADJUDICADOS:
                $menu['adjudicados']['is_active'] = TRUE;
            break;


            case TAREA_SERVICIOS:
                $menu['servicios']['is_active'] = TRUE;
            break;


            //SECCIÓN CATALOGO
            case TAREA_PROVEEDORES:
                $menu['proveedores']['is_active'] = TRUE;
            break;


            case TAREA_CONTACTOS:
                $menu['contactos']['is_active'] = TRUE;
            break;


            //SECCIÓN USUARIOS
            case TAREA_USUARIOS:
            case TAREA_USUARIO_NUEVO:
            case TAREA_USUARIO_DETALLES:
                $menu['usuarios']['is_active'] = TRUE;
            break;


            default:
            break;

        }//end switch tarea actual


        return $menu;

    }//end activar_menu_item_panel


    //Funcion para crear el menu
    function crear_menu_panel()
    {
        //INSTANCIA DE LA VARIABLE DE SESSION
        $tarea_actual = $_SESSION['tarea_actual'] ?? null;
        //Opcion para generar el arreglo de todo mi menú
        $menu = configurar_menu_panel();
        //Opción para activar dicha opcion de cada módulo
        if ($tarea_actual) {
            $menu = activar_menu_item_panel($menu, $tarea_actual);
        }

        $html = '
        <ul class="nav sidebar-menu flex-column"
            data-lte-toggle="treeview"
            role="navigation"
            aria-label="Main navigation"
            data-accordion="false"
            id="navigation">';
        foreach ($menu as $item) {
            if (isset($item['href'])) {
                //ITEM NORMAL
                if ($item['href'] != '#') {
                    $html .= '
                    <li class="nav-item">
                        <a href="'.$item['href'].'" 
                        class="nav-link '.($item['is_active'] ? 'active' : '').'">
                            <i class="nav-icon '.$item['icon'].'"></i>
                            <p>
                                '.$item['text'].'
                            </p>
                        </a>
                    </li>';
                } else {
                    //ITEM CON SUBMENU
                    if (count($item['submenu']) > 0) {
                        $html .= '
                        <li class="nav-item '.($item['is_active'] ? 'menu-open' : '').'">
                            <a href="#" 
                            class="nav-link '.($item['is_active'] ? 'active' : '').'">
                                <i class="nav-icon '.$item['icon'].'"></i>
                                <p>
                                    '.$item['text'].'
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">';
                        foreach ($item['submenu'] as $sub) {
                            $html .= '
                            <li class="nav-item">
                                <a href="'.$sub['href'].'" 
                                class="nav-link '.($sub['is_active'] ? 'active' : '').'">
                                    <i class="nav-icon '.$sub['icon'].'"></i>
                                    <p>
                                        '.$sub['text'].'
                                    </p>
                                </a>
                            </li>';
                        }
                        $html .= '
                            </ul>
                        </li>';
                    }
                }
            }
        }
        $html .= '</ul>';
        return $html;
    }