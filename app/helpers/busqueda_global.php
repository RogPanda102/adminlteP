
<?php

require_once __DIR__ . '/../config/database.php';

if (!function_exists('buscarGlobalmente')) {

    function buscarGlobalmente(
        string $termino,
        int $limitePorModulo = 10
    ): array {
        $termino = trim($termino);

        if ($termino === '') {
            return [];
        }

        $limitePorModulo = max(1, min($limitePorModulo, 30));
        $busqueda = '%' . $termino . '%';

        $db = Database::connect();

        /*
        |--------------------------------------------------------------------------
        | Cotizaciones con sus adjudicaciones y servicios relacionados
        |--------------------------------------------------------------------------
        */

        $sqlCotizaciones = "
            SELECT
                c.id AS cotizacion_id,
                c.folio AS cotizacion_folio,
                c.req AS cotizacion_req,
                c.anio AS cotizacion_anio,
                c.proveedor AS proveedor,
                c.estatus AS cotizacion_estatus,

                a.id AS adjudicado_id,
                a.folio AS adjudicado_folio,
                a.req AS adjudicado_req,
                a.anio AS adjudicado_anio,
                a.pago AS adjudicado_pago,

                s.id AS servicio_id,
                s.folio AS servicio_folio,
                s.req AS servicio_req,
                s.anio AS servicio_anio

            FROM cotizaciones c

            LEFT JOIN adjudicados a
                ON a.cotizacion_id = c.id
                AND COALESCE(a.eliminado, 0) = 0

            LEFT JOIN servicios s
                ON s.adjudicado_id = a.id

            WHERE COALESCE(c.eliminado, 0) = 0
              AND (
                    c.folio LIKE :folio
                    OR c.req LIKE :req
                    OR c.proveedor LIKE :proveedor

                    OR a.folio LIKE :adjudicado_folio
                    OR a.req LIKE :adjudicado_req

                    OR s.folio LIKE :servicio_folio
                    OR s.req LIKE :servicio_req
              )

            ORDER BY c.anio DESC, c.id DESC
            LIMIT {$limitePorModulo}
        ";

        $stmt = $db->prepare($sqlCotizaciones);

        $stmt->execute([
            ':folio' => $busqueda,
            ':req' => $busqueda,
            ':proveedor' => $busqueda,
            ':adjudicado_folio' => $busqueda,
            ':adjudicado_req' => $busqueda,
            ':servicio_folio' => $busqueda,
            ':servicio_req' => $busqueda
        ]);

        $resultados = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $registro) {
            $idCotizacion = (int) $registro['cotizacion_id'];

            if (!isset($resultados[$idCotizacion])) {
                $resultados[$idCotizacion] = [
                    'id' => $idCotizacion,
                    'folio' => $registro['cotizacion_folio'] ?? '',
                    'req' => $registro['cotizacion_req'] ?? '',
                    'anio' => $registro['cotizacion_anio'] ?? null,
                    'proveedor' => $registro['proveedor'] ?? '',
                    'modulos' => [],
                    'estados' => [],
                    'ids' => [
                        'cotizacion' => $idCotizacion,
                        'adjudicado' => null,
                        'servicio' => null
                    ],
                    'urls' => [
                        'cotizacion' => null,
                        'adjudicado' => null,
                        'servicio' => null
                    ]
                ];
            }

            $resultado = &$resultados[$idCotizacion];

            if (!in_array('Cotización', $resultado['modulos'], true)) {
                $resultado['modulos'][] = 'Cotización';

                $resultado['estados']['cotizacion'] =
                    $registro['cotizacion_estatus'] ?? 'Sin estado';
            }

            if (!empty($registro['adjudicado_id'])) {
                if (!in_array('Adjudicado', $resultado['modulos'], true)) {
                    $resultado['modulos'][] = 'Adjudicado';

                    $resultado['estados']['adjudicado'] =
                        $registro['adjudicado_pago'] ?? 'Sin estado';
                }

                $resultado['ids']['adjudicado'] =
                    (int) $registro['adjudicado_id'];
            }

            if (!empty($registro['servicio_id'])) {
                if (!in_array('Servicio', $resultado['modulos'], true)) {
                    $resultado['modulos'][] = 'Servicio';

                    $resultado['estados']['servicio'] = 'Registrado';
                }

                $resultado['ids']['servicio'] =
                    (int) $registro['servicio_id'];
            }

            unset($resultado);
        }

        /*
        |--------------------------------------------------------------------------
        | Adjudicaciones sin cotización relacionada
        |--------------------------------------------------------------------------
        */

        $sqlAdjudicados = "
            SELECT
                a.id,
                a.folio,
                a.req,
                a.anio,
                a.pago
            FROM adjudicados a
            LEFT JOIN cotizaciones c
                ON c.id = a.cotizacion_id
                AND COALESCE(c.eliminado, 0) = 0
            WHERE COALESCE(a.eliminado, 0) = 0
              AND c.id IS NULL
              AND (
                    a.folio LIKE :folio
                    OR a.req LIKE :req
              )
            ORDER BY a.anio DESC, a.id DESC
            LIMIT {$limitePorModulo}
        ";

        $stmt = $db->prepare($sqlAdjudicados);
        $stmt->execute([
            ':folio' => $busqueda,
            ':req' => $busqueda
        ]);

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $registro) {
            $id = (int) $registro['id'];

            $resultados['adjudicado_' . $id] = [
                'id' => $id,
                'folio' => $registro['folio'] ?? '',
                'req' => $registro['req'] ?? '',
                'anio' => $registro['anio'] ?? null,
                'proveedor' => '',
                'modulos' => ['Adjudicado'],
                'estados' => [
                    'adjudicado' => $registro['pago'] ?? 'Sin estado'
                ],
                'ids' => [
                    'cotizacion' => null,
                    'adjudicado' => $id,
                    'servicio' => null
                ],
                'urls' => [
                    'cotizacion' => null,
                    'adjudicado' => null,
                    'servicio' => null
                ]
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Servicios sin adjudicación relacionada
        |--------------------------------------------------------------------------
        */

        $sqlServicios = "
            SELECT
                s.id,
                s.folio,
                s.req,
                s.anio
            FROM servicios s
            LEFT JOIN adjudicados a
                ON a.id = s.adjudicado_id
                AND COALESCE(a.eliminado, 0) = 0
            WHERE a.id IS NULL
              AND (
                    s.folio LIKE :folio
                    OR s.req LIKE :req
              )
            ORDER BY s.anio DESC, s.id DESC
            LIMIT {$limitePorModulo}
        ";

        $stmt = $db->prepare($sqlServicios);
        $stmt->execute([
            ':folio' => $busqueda,
            ':req' => $busqueda
        ]);

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $registro) {
            $id = (int) $registro['id'];

            $resultados['servicio_' . $id] = [
                'id' => $id,
                'folio' => $registro['folio'] ?? '',
                'req' => $registro['req'] ?? '',
                'anio' => $registro['anio'] ?? null,
                'proveedor' => '',
                'modulos' => ['Servicio'],
                'estados' => [
                    'servicio' => 'Registrado'
                ],
                'ids' => [
                    'cotizacion' => null,
                    'adjudicado' => null,
                    'servicio' => $id
                ],
                'urls' => [
                    'cotizacion' => null,
                    'adjudicado' => null,
                    'servicio' => null
                ]
            ];
        }

        return array_values($resultados);
    }
}
