<?php
require_once 'config.php';

// Obtener todos los materiales
$materiales = callAPI('GET', API_BASE_URL_MATERIALES, false) ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Materiales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sistema Académico</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="cursos.php">Cursos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="materiales.php">Materiales</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Gestión de Materiales</h2>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#nuevoMaterialModal">
            Nuevo Material
        </button>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($materiales): ?>
                    <?php foreach ($materiales as $material): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($material['id']); ?></td>
                            <td><?php echo htmlspecialchars($material['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($material['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($material['tipo']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editarMaterial(<?php echo $material['id']; ?>)">Editar</button>
                                <button class="btn btn-sm btn-danger" onclick="eliminarMaterial(<?php echo $material['id']; ?>)">Eliminar</button>
                                <button class="btn btn-sm btn-info">Curso con este material</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Nuevo Material -->
    <div class="modal fade" id="nuevoMaterialModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="nuevoMaterialForm">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-control" name="tipo" required>
                                <option value="LIBRO">Libro</option>
                                <option value="VIDEO">Video</option>
                                <option value="DOCUMENTO">Documento</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarMaterial()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Material -->
    <div class="modal fade" id="editarMaterialModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editarMaterialForm">
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="editNombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" id="editDescripcion" name="descripcion" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-control" id="editTipo" name="tipo" required>
                                <option value="LIBRO">Libro</option>
                                <option value="VIDEO">Video</option>
                                <option value="DOCUMENTO">Documento</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="actualizarMaterial()">Actualizar</button>
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
        const editarMaterialModal = new bootstrap.Modal(document.getElementById('editarMaterialModal'));
        const toast = new bootstrap.Toast(document.getElementById('notificationToast'));

        function mostrarNotificacion(titulo, mensaje, esError = false) {
            document.getElementById('toastTitle').textContent = titulo;
            document.getElementById('toastMessage').textContent = mensaje;
            document.getElementById('notificationToast').classList.toggle('bg-danger', esError);
            document.getElementById('notificationToast').classList.toggle('text-white', esError);
            toast.show();
        }

        function guardarMaterial() {
            const formData = new FormData(document.getElementById('nuevoMaterialForm'));
            const data = Object.fromEntries(formData.entries());

            fetch('<?php echo API_BASE_URL_MATERIALES; ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al crear el material');
                }
                return response.json();
            })
            .then(data => {
                mostrarNotificacion('Éxito', 'Material creado correctamente');
                document.getElementById('nuevoMaterialForm').reset();
                bootstrap.Modal.getInstance(document.getElementById('nuevoMaterialModal')).hide();
                location.reload();
            })
            .catch(error => {
                mostrarNotificacion('Error', error.message, true);
                console.error('Error:', error);
            });
        }

        function editarMaterial(id) {
            fetch(`<?php echo API_BASE_URL_MATERIALES; ?>/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener los datos del material');
                }
                return response.json();
            })
            .then(material => {
                document.getElementById('editId').value = material.id;
                document.getElementById('editNombre').value = material.nombre;
                document.getElementById('editDescripcion').value = material.descripcion;
                document.getElementById('editTipo').value = material.tipo;
                editarMaterialModal.show();
            })
            .catch(error => {
                mostrarNotificacion('Error', error.message, true);
                console.error('Error:', error);
            });
        }

        function actualizarMaterial() {
            const formData = new FormData(document.getElementById('editarMaterialForm'));
            const data = Object.fromEntries(formData.entries());
            const id = data.id;

            fetch(`<?php echo API_BASE_URL_MATERIALES; ?>/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al actualizar el material');
                }
                return response.json();
            })
            .then(data => {
                mostrarNotificacion('Éxito', 'Material actualizado correctamente');
                editarMaterialModal.hide();
                location.reload();
            })
            .catch(error => {
                mostrarNotificacion('Error', error.message, true);
                console.error('Error:', error);
            });
        }

        function eliminarMaterial(id) {
            if (confirm('¿Está seguro de eliminar este material?')) {
                fetch(`<?php echo API_BASE_URL_MATERIALES; ?>/${id}`, {
                    method: 'DELETE'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al eliminar el material');
                    }
                    mostrarNotificacion('Éxito', 'Material eliminado correctamente');
                    location.reload();
                })
                .catch(error => {
                    mostrarNotificacion('Error', error.message, true);
                    console.error('Error:', error);
                });
            }
        }
    </script>
</body>
</html> 