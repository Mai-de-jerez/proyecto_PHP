<?php
$titulo = "Sonido Interior | Contacto";
$pagina = "contacto";

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/menu.php';
?>

<main class="contenedor">

    <div class="encabezado-pagina">
        <h2>Contacto</h2>
        <div class="linea-adorno-centro"></div>
        <p>¿Tienes alguna duda sobre nuestros cuencos o necesitas asesoramiento? Escríbenos y te responderemos encantados.</p>
    </div>

    <?php if (isset($_SESSION['contacto_status'])): ?>
        <?php if ($_SESSION['contacto_status'] === 'success'): ?>
            <p style="text-align: center; color: #356b2f; font-weight: bold; margin-bottom: 20px;">¡Mensaje enviado correctamente! Te responderemos lo antes posible.</p>
        <?php else: ?>
            <p style="text-align: center; color: #b03030; font-weight: bold; margin-bottom: 20px;">Ha habido un problema al enviar el mensaje. Inténtalo de nuevo.</p>
        <?php endif; ?>
        <?php unset($_SESSION['contacto_status']); ?>
    <?php endif; ?>

    <div class="contacto-grid">
        
        <!-- DATOS DE CONTACTO -->
        <aside class="tarjeta-beneficio contacto-info">
            <h3>Información de contacto</h3>
            <p>Estamos al otro lado para resolver cualquier pregunta sobre nuestros instrumentos.</p>
            
            <div class="contacto-item">
                <div class="punto-icono">📍</div>
                <div>
                    <h4>Dirección</h4>
                    <p>C/ Armonía, 12 — Barcelona</p>
                </div>
            </div>

            <div class="contacto-item">
                <div class="punto-icono">📞</div>
                <div>
                    <h4>Teléfono</h4>
                    <p>+34 644 123 456</p>
                </div>
            </div>

            <div class="contacto-item">
                <div class="punto-icono">✉️</div>
                <div>
                    <h4>Email</h4>
                    <p>hola@sonidointerior.com</p>
                </div>
            </div>

            <div class="contacto-item">
                <div class="punto-icono">🕒</div>
                <div>
                    <h4>Horario</h4>
                    <p>Lunes a Viernes: 10:00 - 18:00</p>
                </div>
            </div>
        </aside>

        <!-- FORMULARIO -->
        <section class="tarjeta-beneficio contacto-form">
            <form action="controllers/mensajes/procesar-contacto.php" method="POST">
                <div class="campo-form">
                    <label for="nombre">Nombre completo *</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
                </div>

                <div class="campo-form">
                    <label for="email">Correo electrónico *</label>
                    <input type="email" id="email" name="email" placeholder="tu@email.com" required>
                </div>

                <div class="campo-form">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="600000000">
                </div>

                <div class="campo-form">
                    <label for="asunto">Asunto</label>
                    <input type="text" id="asunto" name="asunto" placeholder="¿En qué te podemos ayudar?">
                </div>

                <div class="campo-form">
                    <label for="mensaje">Mensaje *</label>
                    <textarea id="mensaje" name="mensaje" placeholder="Escribe tu mensaje aquí..." required></textarea>
                </div>

                <button type="submit" class="boton principal bloque">Enviar mensaje</button>
            </form>
        </section>

    </div>

</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>