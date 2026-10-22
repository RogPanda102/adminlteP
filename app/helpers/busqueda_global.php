
<?php

require_once __DIR__ . '/../config/database.php';

if (!function_exists('buscarGlobalmente')) {

    function buscarGlobalmente(
        string $termino,
        int $limitePorModulo = 5
    ): array {
        $termino = trim($termino);

        if ($termino === '') {
            return [];
        }

        $limitePorModulo = max(1, min($limitePorModulo, 10));
        $busqueda = '%' . $termino . '%';

        $db = Database::connect();

        $consultas = [
            [
                'tipo' => 'Cotización',
                'sql' => "
                    SELECT id, folio, req, anio
                    FROM cotizaciones
                    WHERE COALESCE(eliminado, 0) = 0
                      AND (folio LIKE :folio OR req LIKE :req)
                    ORDER BY anio DESC, id DESC
                    LIMIT {$limitePorModulo}
                "
            ],
            [
                'tipo' => 'Adjudicado',
                'sql' => "
                    SELECT id, folio, req, anio
                    FROM adjudicados
                    WHERE COALESCE(eliminado, 0) = 0
                      AND (folio LIKE :folio OR req LIKE :req)
                    ORDER BY anio DESC, id DESC
                    LIMIT {$limitePorModulo}
                "
            ],
            [
                'tipo' => 'Servicio',
                'sql' => "
                    SELECT id, folio, req, anio
                    FROM servicios
                    WHERE folio LIKE :folio OR req LIKE :req
                    ORDER BY anio DESC, id DESC
                    LIMIT {$limitePorModulo}
                "
            ]
        ];

        $resultados = [];

        foreach ($consultas as $consulta) {
            $stmt = $db->prepare($consulta['sql']);

            $stmt->execute([
                ':folio' => $busqueda,
                ':req' => $busqueda
            ]);

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $registro) {
                $resultados[] = [
                    'tipo' => $consulta['tipo'],
                    'id' => (int) $registro['id'],
                    'folio' => $registro['folio'] ?? '',
                    'req' => $registro['req'] ?? '',
                    'anio' => $registro['anio'] ?? null
                ];
            }
        }

        return $resultados;
    }
}
