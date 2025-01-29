package com.cursos.cursos.services;

import com.cursos.cursos.models.Materiales;
import com.cursos.cursos.models.entities.Cursos;

import java.util.List;
import java.util.Optional;

public interface CursosService {

    List<Cursos> findAll();
    Optional<Cursos> findById(Long id);
    Cursos save(Cursos curso);
    void deleteById(Long id);

    // Métodos relacionados con materiales
    Optional<Cursos> addMaterial(Long cursoId, Materiales material);
    Optional<Cursos> removeMaterial(Long cursoId, Long materialId);
    List<Materiales> getAllMaterials();

    // Nuevo método para buscar cursos por material
    List<String> findCursoNamesByMaterialId(Long materialId);
    List<String> findMaterialNamesByCursoId(Long cursoId);


}
