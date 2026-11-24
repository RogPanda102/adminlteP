
<!-- =====================================================
     COMPONENTE GLOBAL - NOTAS RÁPIDAS
===================================================== -->

<div id="notas-rapidas">

    <!-- BOTÓN FLOTANTE -->
    <button
        type="button"
        id="notas-rapidas-toggle"
        class="notas-rapidas-toggle"
        aria-label="Abrir notas rápidas"
        aria-expanded="false"
        aria-controls="notas-rapidas-panel">

        <i class="bi bi-sticky"></i>

    </button>

    <!-- PANEL LATERAL -->
    <aside
        id="notas-rapidas-panel"
        class="notas-rapidas-panel"
        aria-hidden="true">

        <!-- HEADER -->
        <div class="notas-rapidas-header">

            <div>
                <h5 class="mb-0">
                    <i class="bi bi-sticky me-2"></i>
                    Notas rápidas
                </h5>

                <small class="text-body-secondary">
                    Tus notas personales
                </small>
            </div>

            <button
                type="button"
                id="notas-rapidas-cerrar"
                class="btn btn-sm btn-outline-secondary"
                aria-label="Cerrar notas rápidas">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        
        <!-- CONTENIDO -->
        <div class="notas-rapidas-contenido">

            <!-- ================= LISTADO DE NOTAS ================= -->

            <div id="notas-rapidas-vista-listado">

                <!-- BÚSQUEDA -->
                <div class="notas-rapidas-busqueda mb-3">

                    <input
                        type="search"
                        id="notas-rapidas-buscar"
                        class="form-control"
                        placeholder="Buscar notas..."
                        autocomplete="off">

                </div>

                <!-- NUEVA NOTA -->
                <button
                    type="button"
                    id="notas-rapidas-nueva"
                    class="btn btn-primary w-100 mb-3">

                    <i class="bi bi-plus-lg me-2"></i>
                    Nueva nota

                </button>

                <!-- LISTADO -->
                <div
                    id="notas-rapidas-listado"
                    class="notas-rapidas-listado">

                    <div class="notas-rapidas-vacio">

                        <i class="bi bi-journal-text"></i>

                        <p>No tienes notas todavía.</p>

                        <small>
                            Crea una nota para comenzar.
                        </small>

                    </div>

                </div>

            </div>

            <!-- ================= EDITOR DE NOTAS ================= -->

            <div
                id="notas-rapidas-vista-editor"
                class="d-none">

                <!-- EDITOR HEADER -->
                <div class="d-flex align-items-center justify-content-between mb-3">

                    <button
                        type="button"
                        id="notas-rapidas-volver"
                        class="btn btn-sm btn-outline-secondary">

                        <i class="bi bi-arrow-left me-1"></i>
                        Volver

                    </button>

                    <div class="d-flex gap-2">

                        <button
                            type="button"
                            id="notas-rapidas-fijar"
                            class="btn btn-sm btn-outline-secondary"
                            title="Fijar nota">

                            <i class="bi bi-pin"></i>

                        </button>

                        <button
                            type="button"
                            id="notas-rapidas-eliminar"
                            class="btn btn-sm btn-outline-danger"
                            title="Eliminar nota">

                            <i class="bi bi-trash"></i>

                        </button>

                    </div>

                </div>

                <!-- TÍTULO -->
                <div class="mb-3">

                    <input
                        type="text"
                        id="notas-rapidas-titulo"
                        class="form-control"
                        placeholder="Título de la nota"
                        maxlength="150">

                </div>

                <!-- COLOR -->
                <div class="notas-rapidas-colores mb-3">

                    <span class="text-body-secondary small me-2">
                        Color:
                    </span>

                    <button type="button" class="nota-color" data-color="amarillo" aria-label="Amarillo"></button>
                    <button type="button" class="nota-color" data-color="azul" aria-label="Azul"></button>
                    <button type="button" class="nota-color" data-color="verde" aria-label="Verde"></button>
                    <button type="button" class="nota-color" data-color="rosa" aria-label="Rosa"></button>
                    <button type="button" class="nota-color" data-color="morado" aria-label="Morado"></button>

                </div>

                <!-- CONTENIDO -->
                <div class="notas-rapidas-editor-contenido">

                    <textarea
                        id="notas-rapidas-texto"
                        class="form-control"
                        placeholder="Escribe aquí tu nota..."
                        spellcheck="true"></textarea>

                </div>

                <!-- ESTADO DE GUARDADO -->
                <div class="notas-rapidas-editor-footer">

                    <small id="notas-rapidas-estado">
                        Nueva nota
                    </small>

                </div>

            </div>

        </div>

    </aside>


</div>

<!-- =====================================================
     FIN COMPONENTE GLOBAL - NOTAS RÁPIDAS
===================================================== -->
