package com.cursos.cursos.controllers;

import com.cursos.cursos.models.Materiales;
import com.cursos.cursos.models.entities.Cursos;
import com.cursos.cursos.services.CursosService;
import jakarta.validation.Valid;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Optional;

@RestController
@RequestMapping("/api/cursos")
public class CursosController {

    @Autowired
    private CursosService cursosService;

    @GetMapping
    public ResponseEntity<List<Cursos>> findAll() {
        return ResponseEntity.ok(cursosService.findAll());
    }

    @GetMapping("/{id}")
    public ResponseEntity<Cursos> findById(@PathVariable Long id) {
        return cursosService.findById(id)
                .map(ResponseEntity::ok)
                .orElse(ResponseEntity.notFound().build());
    }

    @PostMapping
    public ResponseEntity<Cursos> save(@Valid @RequestBody Cursos curso) {
        return ResponseEntity.status(201).body(cursosService.save(curso));
    }

    @PutMapping("/{id}")
    public ResponseEntity<Cursos> update(@PathVariable Long id, @Valid @RequestBody Cursos updatedCurso) {
        return cursosService.findById(id)
                .map(existingCurso -> {
                    existingCurso.setNombre(updatedCurso.getNombre());
                    existingCurso.setDescripcion(updatedCurso.getDescripcion());
                    existingCurso.getCursoMateriales().clear();
                    existingCurso.getCursoMateriales().addAll(updatedCurso.getCursoMateriales());
                    return ResponseEntity.ok(cursosService.save(existingCurso));
                })
                .orElse(ResponseEntity.notFound().build());
    }


    @DeleteMapping("/{id}")
    public ResponseEntity<Map<String, String>> deleteById(@PathVariable Long id) {
        Optional<Cursos> cursoExistente = cursosService.findById(id);
        if (cursoExistente.isPresent()) {
            cursosService.deleteById(id);
            Map<String, String> response = new HashMap<>();
            response.put("message", "Curso eliminado correctamente");
            return ResponseEntity.ok(response);
        } else {
            return ResponseEntity.notFound().build();
        }
    }


    @GetMapping("/materiales")
    public ResponseEntity<List<Materiales>> getAllMaterials() {
        return ResponseEntity.ok(cursosService.getAllMaterials());
    }


    @PostMapping("/{cursoId}/materiales")
    public ResponseEntity<Cursos> addMaterial(@PathVariable Long cursoId, @Valid @RequestBody Materiales material) {
        return cursosService.addMaterial(cursoId, material)
                .map(ResponseEntity::ok)
                .orElse(ResponseEntity.notFound().build());
    }


    @DeleteMapping("/{cursoId}/materiales/{materialId}")
    public ResponseEntity<Map<String, String>> removeMaterial(@PathVariable Long cursoId, @PathVariable Long materialId) {
        Optional<Cursos> cursoActualizado = cursosService.removeMaterial(cursoId, materialId);
        if (cursoActualizado.isPresent()) {
            Map<String, String> response = new HashMap<>();
            response.put("message", "Material eliminado correctamente del curso");
            return ResponseEntity.ok(response);
        } else {
            return ResponseEntity.notFound().build();
        }
    }


    @GetMapping("/materiales/{materialId}")
    public ResponseEntity<List<String>> findCursoNamesByMaterial(@PathVariable Long materialId) {
        List<String> cursoNombres = cursosService.findCursoNamesByMaterialId(materialId);
        if (cursoNombres.isEmpty()) {
            return ResponseEntity.notFound().build();
        }
        return ResponseEntity.ok(cursoNombres);
    }
    @GetMapping("/{cursoId}/materialess")
    public ResponseEntity<List<String>> findMaterialNamesByCurso(@PathVariable Long cursoId) {
        List<String> materialNombres = cursosService.findMaterialNamesByCursoId(cursoId);
        if (materialNombres.isEmpty()) {
            return ResponseEntity.notFound().build();
        }
        return ResponseEntity.ok(materialNombres);
    }
}
