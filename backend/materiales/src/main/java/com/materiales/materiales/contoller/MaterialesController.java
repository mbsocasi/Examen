package com.materiales.materiales.contoller;

import com.materiales.materiales.models.entities.Materiales;
import com.materiales.materiales.services.MaterialesService;
import jakarta.validation.Valid;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Optional;

@RestController
@RequestMapping("/api/materiales")
public class MaterialesController {

    @Autowired
    private MaterialesService service;

    /**
     * Get all materials
     */
    @GetMapping
    public ResponseEntity<List<Materiales>> findAll() {
        return ResponseEntity.ok(service.findAll());
    }

    /**
     * Get a material by ID
     */
    @GetMapping("/{id}")
    public ResponseEntity<Materiales> findById(@PathVariable Long id) {
        return service.findById(id)
                .map(ResponseEntity::ok)
                .orElse(ResponseEntity.notFound().build());
    }

    /**
     * Create a new material
     */
    @PostMapping
    public ResponseEntity<Materiales> save(@Valid @RequestBody Materiales material) {
        return ResponseEntity.ok(service.save(material));
    }

    /**
     * Update an existing material
     */
    @PutMapping("/{id}")
    public ResponseEntity<Materiales> update(@PathVariable Long id, @Valid @RequestBody Materiales material) {
        return ResponseEntity.ok(service.update(id, material));
    }

    /**
     * Delete a material by ID (with success message)
     */
    @DeleteMapping("/{id}")
    public ResponseEntity<Map<String, String>> deleteById(@PathVariable Long id) {
        Optional<Materiales> materialExistente = service.findById(id);
        if (materialExistente.isPresent()) {
            service.deleteById(id);
            Map<String, String> response = new HashMap<>();
            response.put("message", "Material eliminado correctamente");
            return ResponseEntity.ok(response);
        } else {
            return ResponseEntity.notFound().build();
        }
    }
}
