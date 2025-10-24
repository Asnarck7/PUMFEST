# PUMFEST
El proyecto consiste en el desarrollo de una página web para la gestión de eventos y la venta de entradas. La plataforma contará con una interfaz amigable y llamativa que permita a los usuarios visualizar los eventos disponibles, registrarse como asistentes y adquirir boletos de manera ágil y segura.
⚠️ Copyright © 2025 [sql, php, html, js, css, kevin Julian Guerrero Penagos, html, js, css, Heiber Andrés Lozano Rodríguez,html, js, css,  César David Gómez Vergara]

Este código es solo para fines demostrativos. No se permite su copia, modificación ni uso sin autorización.



=======================================================================================================================================================




Descripcion del proyecto
Descripción del Programa
El proyecto consiste en el desarrollo de una página web para la gestión de eventos y la venta de entradas. La plataforma contará con una interfaz amigable y llamativa que permita a los usuarios visualizar los eventos disponibles, registrarse como asistentes y adquirir boletos de manera ágil y segura.
Existen tipos de usuarios principales:
Asistentes: podrán registrarse, iniciar sesión, navegar por los eventos publicados, seleccionar el evento de interés, elegir la cantidad y tipo de entradas, realizar el pago y recibir un ticket digital único con código QR en su perfil y correo electrónico.


Organizadores: son usuarios especiales que, tras un proceso de verificación Por el administrador, y por su puesto para entrar como organizador sólo puede por un enlace que solo el organizador pueda entrar y no por medio de un botón dentro de la interfaz tendrán acceso a funcionalidades avanzadas como la creación, edición y eliminación de eventos. También podrán definir los precios, categorías de entradas, número de puestos disponibles, consultar la lista de asistentes con detalle de sus compras, y asignar 
Administrador: lleva una tarea importante en la gestion de la pagina donde puede validar a un organizador y puede ver las compras los asistentes y sus compras, puedes ajustar ciertas funcionalidades de la pagina web
patrocinadores a sus eventos. Los patrocinadores son solo información del evento y no interactúan directamente con la plataforma ni actúan como usuarios.


9.1 Objetivo
El objetivo de la plataforma es digitalizar y simplificar la experiencia de organización y asistencia a eventos, permitiendo que:
Los organizadores gestionen todos los aspectos de sus eventos (creación, publicación, entradas disponibles, precios, validación y asignación de patrocinadores).


Los asistentes tengan una interfaz intuitiva para consultar eventos, adquirir boletos y recibir sus tickets digitales de forma rápida y segura.


Se centralice el ciclo de vida de un evento en una sola aplicación: desde la publicación, compra y validación de entradas, hasta el control de acceso el día del evento.


9.2 Alcance
El sistema cubrirá las siguientes funcionalidades principales:
Gestión de Organizadores: registro, verificación y creación de eventos con control sobre cupos, precios, categorías de entradas y asignación de patrocinadores.


Eventos Públicos: catálogo accesible donde los usuarios pueden buscar, filtrar y consultar detalles de cada evento (descripción, lugar, fecha, mapa del sitio, organizador, patrocinadores).


Venta de Entradas: proceso de compra simple con cálculo automático del total, pasarela de pago simulada y confirmación de compra.


Ticket Digital: generación automática de entradas digitales con código QR, almacenadas en el perfil del usuario y enviadas por correo electrónico.


Check-in y Validación: escaneo de códigos QR en los accesos, marcando tickets como “usados” y registrando en la plataforma un log de validación que evita duplicaciones y fraudes. Cada intento de validación queda registrado, diferenciando entre tickets válidos, inválidos o ya utilizados.


Requerimientos No Funcionales: seguridad en datos y transacciones, interfaz adaptable a dispositivos, rendimiento bajo alta demanda, alta disponibilidad y facilidad de mantenimiento y escalabilidad.
9.3 Público Objetivo
La plataforma está diseñada para:
Organizadores de eventos (empresas, promotores, instituciones, emprendedores) que necesiten una herramienta confiable para publicar y gestionar sus actividades, incluyendo la opción de asignar patrocinadores a sus eventos.


Asistentes y público en general que buscan acceder de manera sencilla a la información de eventos, adquirir entradas en línea y contar con un sistema de tickets digitales seguro y práctico.


Personal de logística que podrá realizar el control de acceso a los eventos mediante la validación de códigos QR, con registros automáticos de cada validación para mayor control y transparencia.
