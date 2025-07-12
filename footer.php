</main> 

<!-- Footer -->
<footer class="footer">
    <div class="footer-content">
        <!-- Sección principal del footer -->
        <div class="footer-main">
            <!-- Logo y descripción -->
            <div class="footer-section">
                <div class="footer-logo">
                    <img src="img-MATEX.png" alt="Logo Matex" />
                    <h3>Matex</h3>
                </div>
                <p class="footer-description">
                    Tu tienda de confianza para todo lo relacionado con el mate. 
                    Calidad premium y tradición argentina en cada producto.
                </p>
            </div>

            <!-- Contacto -->
            <div class="footer-section">
                <h4>Contacto</h4>
                <div class="contact-info">
                    <p>📍 San Clemente del Tuyú, Buenos Aires</p>
                    <p>📞 +54 2252 45-6409</p>
                    <p>✉️ info@matex.com.ar</p>
                </div>
            </div>

            <!-- Redes sociales -->
            <div class="footer-section">
                <h4>Síguenos</h4>
                <div class="social-links">
                    <a href="https://www.instagram.com/lazo.th" target="_blank" rel="noopener noreferrer" class="social-link instagram">
                        <img src="img-instagram.png" alt="Instagram" class="social-icon">
                        Instagram
                    </a>
                    <a href="https://wa.me/5492252456409" target="_blank" rel="noopener noreferrer" class="social-link whatsapp">
                        <img src="img-whatsapp.png" alt="WhatsApp" class="social-icon">
                        WhatsApp
                    </a>
                    <a href="https://www.tiktok.com/lazo_2804" target="_blank" rel="noopener noreferrer" class="social-link tiktok">
                        <img src="img-tiktok.png" alt="TikTok" class="social-icon">
                        TikTok
                    </a>
                    <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer" class="social-link facebook">
                        <img src="img-facebook.webp" alt="Facebook" class="social-icon">
                        Facebook
                    </a>
                </div>
            </div>
        </div>

        <!-- Línea divisoria -->
        <hr class="footer-divider">

        <!-- Footer inferior -->
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Matex. Todos los derechos reservados.</p>
            <div class="footer-bottom-links">
                <a href="#">Términos y Condiciones</a>
                <a href="#">Política de Privacidad</a>
                <a href="#">Envíos</a>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Estilos del Footer */
    .footer {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #f8fafc;
        padding: 3rem 0 1rem;
        margin-top: 4rem;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .footer-main {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2.5rem;
        margin-bottom: 2rem;
    }

    .footer-section h4 {
        color: #22c55e;
        font-size: 1.2rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 1rem;
    }

    .footer-logo img {
        height: 40px;
        width: auto;
        border-radius: 8px;
    }

    .footer-logo h3 {
        font-size: 1.8rem;
        font-weight: bold;
        background: linear-gradient(45deg, #22c55e, #16a34a);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }

    .footer-description {
        color: #cbd5e1;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 0.5rem;
    }

    .footer-links a {
        color: #cbd5e1;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .footer-links a:hover {
        color: #22c55e;
        padding-left: 5px;
    }

    .contact-info p {
        color: #cbd5e1;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .social-links {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .social-link {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #cbd5e1;
        text-decoration: none;
        padding: 0.75rem;
        border-radius: 10px;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.05);
    }

    .social-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .social-icon {
        width: 24px;
        height: 24px;
        object-fit: contain;
    }

    .social-link.instagram:hover {
        background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
        color: white;
    }

    .social-link.whatsapp:hover {
        background: #25d366;
        color: white;
    }

    .social-link.tiktok:hover {
        background: #000000;
        color: white;
    }

    .social-link.facebook:hover {
        background: #1877f2;
        color: white;
    }

    .footer-divider {
        border: none;
        height: 1px;
        background: linear-gradient(90deg, transparent, #374151, transparent);
        margin: 2rem 0;
    }

    .footer-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #374151;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .footer-bottom p {
        color: #94a3b8;
        margin: 0;
    }

    .footer-bottom-links {
        display: flex;
        gap: 1.5rem;
    }

    .footer-bottom-links a {
        color: #94a3b8;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-bottom-links a:hover {
        color: #22c55e;
    }

    /* Responsive del footer */
    @media (max-width: 768px) {
        .footer {
            padding: 2rem 0 1rem;
        }

        .footer-main {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .footer-bottom {
            flex-direction: column;
            text-align: center;
        }

        .footer-bottom-links {
            flex-wrap: wrap;
            justify-content: center;
        }

        .social-links {
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
        }
    }
</style>

</body>
</html>