<?php
require_once 'config.php';

// Obtener todos los cursos
$cursos = callAPI('GET', API_BASE_URL_CURSOS, false) ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sistema Académico</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="cursos.php">Cursos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="materiales.php">Materiales</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Gestión de Cursos</h2>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#nuevoCursoModal">
            Nuevo Curso
        </button>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($cursos): ?>
                    <?php foreach ($cursos as $curso): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($curso['id']); ?></td>
                            <td><?php echo htmlspecialchars($curso['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($curso['descripcion']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editarCurso(<?php echo $curso['id']; ?>)">Editar</button>
                                <button class="btn btn-sm btn-danger" onclick="eliminarCurso(<?php echo $curso['id']; ?>)">Eliminar</button>
                                <button class="btn btn-sm btn-info" onclick="mostrarCursoConMaterial(<?php echo $curso['id'];?>)">Materiales del curso</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Nuevo Curso -->
    <div class="modal fade" id="nuevoCursoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="nuevoCursoForm">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarCurso()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Curso -->
    <div class="modal fade" id="editarCursoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editarCursoForm">
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="editNombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" id="editDescripcion" name="descripcion" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="actualizarCurso()">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="notificationToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="toastTitle">Notificación</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const editarCursoModal = new bootstrap.Modal(document.getElementById('editarCursoModal'));
        const toast = new bootstrap.Toast(document.getElementById('notificationToast'));

        function mostrarNotificacion(titulo, mensaje, esError = false) {
            document.getElementById('toastTitle').textContent = titulo;
            document.getElementById('toastMessage').textContent = mensaje;
            document.getElementById('notificationToast').classList.toggle('bg-danger', esError);
            document.getElementById('notificationToast').classList.toggle('text-white', esError);
            toast.show();
        }

        function guardarCurso() {
            const formData = new FormData(document.getElementById('nuevoCursoForm'));
            const data = Object.fromEntries(formData.entries());

            fetch('<?php echo API_BASE_URL_CURSOS; ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al crear el curso');
                }
                return response.json();
            })
            .then(data => {
                mostrarNotificacion('Éxito', 'Curso creado correctamente');
                document.getElementById('nuevoCursoForm').reset();
                bootstrap.Modal.getInstance(document.getElementById('nuevoCursoModal')).hide();
                location.reload();
            })
            .catch(error => {
                mostrarNotificacion('Error', error.message, true);
                console.error('Error:', error);
            });
        }

        function editarCurso(id) {
            fetch(`<?php echo API_BASE_URL_CURSOS; ?>/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener los datos del curso');
                }
                return response.json();
            })
            .then(curso => {
                document.getElementById('editId').value = curso.id;
                document.getElementById('editNombre').value = curso.nombre;
                document.getElementById('editDescripcion').value = curso.descripcion;
                editarCursoModal.show();
            })
            .catch(error => {
                mostrarNotificacion('Error', error.message, true);
                console.error('Error:', error);
            });
        }

        function actualizarCurso() {
            const formData = new FormData(document.getElementById('editarCursoForm'));
            const data = Object.fromEntries(formData.entries());
            const id = data.id;

            fetch(`<?php echo API_BASE_URL_CURSOS; ?>/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al actualizar el curso');
                }
                return response.json();
            })
            .then(data => {
                mostrarNotificacion('Éxito', 'Curso actualizado correctamente');
                editarCursoModal.hide();
                location.reload();
            })
            .catch(error => {
                mostrarNotificacion('Error', error.message, true);
                console.error('Error:', error);
            });
        }

        function eliminarCurso(id) {
            if (confirm('¿Está seguro de eliminar este curso?')) {
                fetch(`<?php echo API_BASE_URL_CURSOS; ?>/${id}`, {
                    method: 'DELETE'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al eliminar el curso');
                    }
                    mostrarNotificacion('Éxito', 'Curso eliminado correctamente');
                    location.reload();
                })
                .catch(error => {
                    mostrarNotificacion('Error', error.message, true);
                    console.error('Error:', error);
                });
            }
        }
    </script>
    <script>
function mostrarCursoConMaterial(cursoId) {
    const url = `http://localhost:8002/api/cursos/${cursoId}/materialess`;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener los materiales del curso');
            }
            return response.json();
        })
        .then(data => {
            let mensaje = `Materiales del curso ID ${cursoId}:\n`;
            if (Array.isArray(data) && data.length > 0) {
                mensaje += data.join("\n"); // Mostrar los materiales en líneas separadas
            } else {
                mensaje += "No hay materiales disponibles.";
            }
            alert(mensaje);
        })
        .catch(error => {
            alert("Error al obtener los datos: " + error.message);
        });
}

</script>
</body>
</html> 