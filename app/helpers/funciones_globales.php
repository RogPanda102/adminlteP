<?php
    
     // =======================
     // B R E A D   C R U M B
     // =======================
    function breadcrumb($tarea = '', $breadcrumb = array()){
        $html = '';
        if(sizeof($breadcrumb)> 0){
            $html.= '
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="m-0">'.$tarea.'</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="'.BASE_URL.'">Inicio</a></li>';
                            foreach ($breadcrumb as $nav) {
                                if(isset($nav['href'])){
                                    if($nav["href"] != '#'){
                                        $html.= '<li class="breadcrumb-item active"><a href="'.$nav["href"].'">'.$nav["tarea"].'</a></li>';
                                    }//end nav
                                    else{
                                        $html.= '<li class="breadcrumb-item text-black">'.$nav["tarea"].'</li>';
                                    }//end else
                                }//end if isset
                            }//end foreach
                            $html.='</ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            ';
        }//end if sizeof
        return $html;
    }//end breadcrumb

    // ================================
    // C R E A R    M E N S A J E
    // ================================
    function mensaje($texto = "", $tipo = 5, $tiempo = 1000)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $_SESSION['mensaje'] = [
            'texto'  => $texto,
            'tipo'   => $tipo,
            'tiempo' => $tiempo
        ];
    }

    // ================================
    // M O S T R A R    M E N S A J E
    // ================================

    function mostrar_mensaje()
    {

        $html = '';

        $mensaje = $_SESSION['mensaje'] ?? null;

        unset($_SESSION['mensaje']);

        if ($mensaje == null) {

            return "";

        }

        switch($mensaje['tipo']){

            case ALERT_SUCCESS:
                $tipoMensaje = "success";
                $titulo = "¡Correcto!";
            break;

            case ALERT_DANGER:
                $tipoMensaje = "error";
                $titulo = "¡Error!";
            break;

            case ALERT_WARNING:
                $tipoMensaje = "warning";
                $titulo = "¡Atención!";
            break;

            default:
                $tipoMensaje = "info";
                $titulo = "¡Bienvenido!";
            break;

        }

        $html = '
            toastr["'.$tipoMensaje.'"]("'.$mensaje["texto"].'","'.$titulo.'", {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-center",
                "showDuration": "'.$mensaje["tiempo"].'",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });
        ';

        return $html;

    }

    // ================================
    // L I M P I A R   T E X T O
    // ================================
    function limpiarTexto($texto = '')
    {
        $texto = trim($texto);

        // elimina espacios dobles o triples
        $texto = preg_replace('/\s+/', ' ', $texto);

        return $texto;
    }
    // ================================
    // T E X T O   M A Y U S C U L A S
    // ================================
    function limpiarTextoMayusculas($texto = '')
    {
        $texto = limpiarTexto($texto);

        return mb_strtoupper($texto, 'UTF-8');
    }
    function redirect($ruta)
    {
        header('Location: ' . BASE_URL . $ruta);
        exit;
    }
    function validarRequerido($valor, $mensaje)
    {
        if (empty(trim($valor))) {

            mensaje(
                $mensaje,
                ALERT_DANGER,
                3000
            );

            return false;
        }

        return true;
    }