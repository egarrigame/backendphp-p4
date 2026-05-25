<?php
/**
 * Bloque Personalizado: Listado de Servicios por Zonas
 * Lee un archivo JSON del servidor y pinta una tabla.
 */

// 1. Definir la ruta física del archivo dentro del servidor (Evita problemas de Docker/Red)
$json_path = WP_CONTENT_DIR . '/uploads/servicios_zonas.json';

// Comprobar si el archivo físico existe en esa ruta
if ( ! file_exists( $json_path ) ) {
    echo '<p style="color:red;">Error: No se encuentra el archivo JSON en el servidor.</p>';
    return;
}

// 2. Leer el contenido del archivo directamente (file_get_contents es nativo de PHP)
$json_string = file_get_contents( $json_path );

// 3. Decodificar el JSON para transformarlo en un array de PHP
$datos_zonas = json_decode( $json_string, true );

// Comprobar si el JSON está vacío o mal formateado
if ( empty( $datos_zonas ) ) {
    echo '<p style="color:orange;">No hay datos de servicios disponibles actualmente o el JSON es incorrecto.</p>';
    return;
}
?>

<div class="wp-block-custom-listado-zonas" style="margin: 2rem auto; max-width: 800px;">
    <h3 style="color: #480808; text-align: center; margin-bottom: 1rem; border-bottom: 2px solid #480808; padding-bottom: 0.5rem; text-transform: uppercase;">
        Resumen de Servicios por Zona
    </h3>
    
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background-color: #f4f4f4; border-bottom: 2px solid #ddd;">
                <th style="padding: 12px; font-weight: bold;">Zona</th>
                <th style="padding: 12px; font-weight: bold; text-align: center;">Total servicios</th>
                <th style="padding: 12px; font-weight: bold; text-align: right;">Porcentaje</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // 6. El Bucle (Loop) dinámico: Recorremos cada objeto del JSON
            foreach ( $datos_zonas as $item ) : 
                // Aseguramos que existan las claves antes de pintarlas para evitar 'Notices' de PHP
                $zona       = isset($item['zona']) ? esc_html($item['zona']) : 'Desconocida';
                $servicios  = isset($item['total_servicios']) ? intval($item['total_servicios']) : 0;
                $porcentaje = isset($item['porcentaje']) ? floatval($item['porcentaje']) : 0;
            ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px; font-weight: 500;"><?php echo $zona; ?></td>
                    <td style="padding: 12px; text-align: center;"><?php echo $servicios; ?></td>
                    <td style="padding: 12px; text-align: right; color: #666;"><?php echo $porcentaje; ?>%</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>