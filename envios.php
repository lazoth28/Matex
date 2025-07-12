<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Envíos - Matex</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2d3748;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-radius: 20px;
            color: white;
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .content {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .section {
            margin-bottom: 2.5rem;
        }

        .section h2 {
            color: #f59e0b;
            font-size: 1.4rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f59e0b;
        }

        .section h3 {
            color: #1f2937;
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
            margin-top: 1.5rem;
        }

        .section p {
            margin-bottom: 1rem;
            text-align: justify;
        }

        .section ul {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .section li {
            margin-bottom: 0.5rem;
        }

        .shipping-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .shipping-table th,
        .shipping-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .shipping-table th {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
            font-weight: 600;
        }

        .shipping-table tr:nth-child(even) {
            background: #f9fafb;
        }

        .shipping-table tr:hover {
            background: #f3f4f6;
        }

        .highlight {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            padding: 1rem;
            border-radius: 10px;
            border-left: 4px solid #f59e0b;
            margin: 1rem 0;
        }

        .free-shipping {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid #22c55e;
            margin: 1rem 0;
        }

        .free-shipping h3 {
            color: #16a34a;
            margin-bottom: 1rem;
        }

        .shipping-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 1.5rem 0;
        }

        .shipping-method {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #f59e0b;
            transition: transform 0.3s ease;
        }

        .shipping-method:hover {
            transform: translateY(-2px);
        }

        .shipping-method h4 {
            color: #f59e0b;
            margin-bottom: 0.5rem;
        }

        .back-link {
            display: inline-block;
            margin-top: 2rem;
            padding: 12px 24px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .back-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245, 158, 11, 0.4);
        }

        .last-updated {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .header h1 {
                font-size: 2rem;
            }

            .content {
                padding: 1.5rem;
            }

            .shipping-methods {
                grid-template-columns: 1fr;
            }

            .shipping-table {
                font-size: 0.9rem;
            }

            .shipping-table th,
            .shipping-table td {
                padding: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Política de Envíos</h1>
            <p>Matex - Llevamos la tradición del mate hasta tu puerta</p>
        </div>

        <div class="content">
            <div class="section">
                <h2>1. Información General</h2>
                <p>En <strong>Matex</strong> nos especializamos en productos de mate de calidad premium y nos comprometemos a que lleguen a tu hogar en perfectas condiciones. Realizamos envíos a todo el territorio argentino con diferentes opciones para que elijas la que mejor se adapte a tus necesidades.</p>
                <p>Todos nuestros envíos se realizan con el máximo cuidado para preservar la calidad de nuestros productos hasta que lleguen a tus manos.</p>
            </div>

            <div class="free-shipping">
                <h3>🚚 Envío Gratis</h3>
                <p>¡Disfruta de <strong>envío gratuito</strong> en compras superiores a $50.000! Válido para todo el país. Una excelente oportunidad para completar tu kit matero ideal.</p>
            </div>

            <div class="section">
                <h2>2. Métodos de Envío</h2>
                <div class="shipping-methods">
                    <div class="shipping-method">
                        <h4>📦 Correo Argentino</h4>
                        <p>Servicio estándar con cobertura nacional. Ideal para envíos económicos con seguimiento.</p>
                        <p><strong>Tiempo:</strong> 5-10 días hábiles</p>
                    </div>
                    <div class="shipping-method">
                        <h4>🚛 OCA</h4>
                        <p>Envío rápido y seguro con amplia cobertura. Perfecta para pedidos urgentes.</p>
                        <p><strong>Tiempo:</strong> 3-7 días hábiles</p>
                    </div>
                    <div class="shipping-method">
                        <h4>📮 Retiro en Sucursal</h4>
                        <p>Retira tu pedido en la sucursal más cercana cuando te resulte conveniente.</p>
                        <p><strong>Tiempo:</strong> 2-5 días hábiles</p>
                    </div>
                    <div class="shipping-method">
                        <h4>🏠 Retiro en Local</h4>
                        <p>Retira tu pedido directamente en nuestro local en San Clemente del Tuyú.</p>
                        <p><strong>Tiempo:</strong> Inmediato</p>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2>3. Costos de Envío</h2>
                <p>Los costos de envío varían según el destino, peso y método de envío seleccionado:</p>
                
                <table class="shipping-table">
                    <thead>
                        <tr>
                            <th>Zona</th>
                            <th>Correo Argentino</th>
                            <th>OCA</th>
                            <th>Retiro en Sucursal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>CABA</td>
                            <td>$2.500</td>
                            <td>$3.200</td>
                            <td>$1.800</td>
                        </tr>
                        <tr>
                            <td>GBA</td>
                            <td>$2.800</td>
                            <td>$3.500</td>
                            <td>$2.000</td>
                        </tr>
                        <tr>
                            <td>Buenos Aires Interior</td>
                            <td>$3.200</td>
                            <td>$4.000</td>
                            <td>$2.500</td>
                        </tr>
                        <tr>
                            <td>Córdoba, Santa Fe, Entre Ríos</td>
                            <td>$3.800</td>
                            <td>$4.500</td>
                            <td>$3.000</td>
                        </tr>
                        <tr>
                            <td>Resto del País</td>
                            <td>$4.500</td>
                            <td>$5.500</td>
                            <td>$3.500</td>
                        </tr>
                    </tbody>
                </table>
                
                <p><strong>Nota:</strong> Los precios son orientativos y pueden variar según el peso y volumen del pedido.</p>
            </div>

            <div class="section">
                <h2>4. Tiempos de Procesamiento</h2>
                <h3>4.1 Preparación del Pedido</h3>
                <p>Una vez confirmado el pago, nuestro equipo procesa tu pedido:</p>
                <ul>
                    <li><strong>Días hábiles:</strong> 1-2 días para preparar el envío</li>
                    <li><strong>Fines de semana:</strong> Los pedidos se procesan el lunes siguiente</li>
                    <li><strong>Feriados:</strong> Se procesan el primer día hábil posterior</li>
                </ul>

                <h3>4.2 Horarios de Despacho</h3>
                <ul>
                    <li><strong>Lunes a Viernes:</strong> 9:00 - 17:00 hs</li>
                    <li><strong>Sábados:</strong> 9:00 - 13:00 hs</li>
                    <li><strong>Domingos y Feriados:</strong> Cerrado</li>
                </ul>
            </div>

            <div class="section">
                <h2>5. Áreas de Cobertura</h2>
                <p>Realizamos envíos a todo el territorio argentino:</p>
                
                <h3>5.1 Cobertura Nacional</h3>
                <ul>
                    <li>Ciudad Autónoma de Buenos Aires (CABA)</li>
                    <li>Provincia de Buenos Aires</li>
                    <li>Todas las provincias argentinas</li>
                    <li>Localidades rurales (consultar disponibilidad)</li>
                </ul>

                <h3>5.2 Localidades Especiales</h3>
                <p>Para envíos a localidades rurales o de difícil acceso, contactanos para consultar disponibilidad y costo del envío.</p>
            </div>

            <div class="section">
                <h2>6. Seguimiento del Envío</h2>
                <p>Una vez despachado tu pedido, recibirás:</p>
                <ul>
                    <li><strong>Email de confirmación</strong> con el número de seguimiento</li>
                    <li><strong>SMS/WhatsApp</strong> con el link de seguimiento</li>
                    <li><strong>Actualizaciones</strong> sobre el estado de tu envío</li>
                </ul>
                
                <p>Puedes rastrear tu pedido en:</p>
                <ul>
                    <li>Sitio web del transportista</li>
                    <li>Nuestra página de seguimiento</li>
                    <li>Contactando a nuestro equipo de atención al cliente</li>
                </ul>
            </div>

            <div class="section">
                <h2>7. Recepción del Pedido</h2>
                <h3>7.1 Horarios de Entrega</h3>
                <ul>
                    <li><strong>Lunes a Viernes:</strong> 9:00 - 18:00 hs</li>
                    <li><strong>Sábados:</strong> 9:00 - 13:00 hs (según zona)</li>
                </ul>

                <h3>7.2 Requisitos para la Entrega</h3>
                <ul>
                    <li>Presencia del destinatario o persona autorizada</li>
                    <li>Documento de identidad</li>
                    <li>Número de pedido</li>
                </ul>

                <h3>7.3 Intentos de Entrega</h3>
                <p>Si no te encuentras en el domicilio:</p>
                <ul>
                    <li>Se realizarán hasta 3 intentos de entrega</li>
                    <li>Podrás retirar el paquete en la sucursal más cercana</li>
                    <li>Coordinar nueva fecha de entrega</li>
                </ul>
            </div>

            <div class="section">
                <h2>8. Productos Frágiles</h2>
                <p>Nuestros productos de mate, especialmente los mates de cerámica y vidrio, reciben un embalaje especial:</p>
                <ul>
                    <li>Embalaje con materiales de protección</li>
                    <li>Etiquetas de "FRÁGIL" en el paquete</li>
                    <li>Seguro de transporte incluido</li>
                    <li>Garantía por daños durante el envío</li>
                </ul>
            </div>

            <div class="section">
                <h2>9. Problemas con el Envío</h2>
                <h3>9.1 Paquete Dañado</h3>
                <p>Si tu paquete llega dañado:</p>
                <ul>
                    <li>No rechaces el paquete</li>
                    <li>Toma fotos del daño</li>
                    <li>Contactanos inmediatamente</li>
                    <li>Gestionaremos el reemplazo o reembolso</li>
                </ul>

                <h3>9.2 Paquete Perdido</h3>
                <p>En caso de pérdida del paquete:</p>
                <ul>
                    <li>Iniciamos investigación con el transportista</li>
                    <li>Tiempo de espera: 15 días hábiles</li>
                    <li>Reemplazo o reembolso completo</li>
                </ul>
            </div>

            <div class="section">
                <h2>10. Cambios de Dirección</h2>
                <p>Si necesitas cambiar la dirección de envío:</p>
                <ul>
                    <li>Contactanos antes del despacho</li>
                    <li>Una vez despachado, contacta al transportista</li>
                    <li>Pueden aplicar costos adicionales</li>
                </ul>
            </div>

            <div class="section">
                <h2>11. Retiro en Local</h2>
                <p>Si prefieres retirar tu pedido en nuestro local:</p>
                <ul>
                    <li><strong>Dirección:</strong> San Clemente del Tuyú, Buenos Aires</li>
                    <li><strong>Horarios:</strong> Lunes a Viernes 9:00-18:00, Sábados 9:00-13:00</li>
                    <li><strong>Requisitos:</strong> DNI y número de pedido</li>
                    <li><strong>Plazo:</strong> 30 días para retirar desde la confirmación</li>
                </ul>
            </div>

            <div class="section">
                <h2>12. Contacto para Envíos</h2>
                <p>Para consultas sobre envíos, contáctanos:</p>
                <ul>
                    <li><strong>Email:</strong> info@matex.com.ar</li>
                    <li><strong>WhatsApp:</strong> +54 9 2252 45-6409</li>
                    <li><strong>Teléfono:</strong> +54 2252 45-6409</li>
                    <li><strong>Horario de atención:</strong> Lunes a Viernes 9:00-18:00</li>
                </ul>
            </div>

            <div class="highlight">
                <p><strong>Compromiso Matex:</strong> Nos comprometemos a que tu experiencia de compra sea completa, desde la selección del producto hasta que llegue a tu hogar. Cada envío es cuidadosamente preparado para mantener la calidad premium de nuestros productos.</p>
            </div>

            <div class="last-updated">
                <p>Última actualización: Julio 2025</p>
            </div>
        </div>

        <a href="index.php" class="back-link">← Volver al inicio</a>
    </div>
</body>
</html>